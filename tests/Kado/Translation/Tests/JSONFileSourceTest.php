<?php

namespace Kado\Translation\Tests;


use Kado\IO\Vfs\VfsHandler;
use Kado\IO\Vfs\VfsManager;
use Kado\Locale\Locale;
use Kado\Translation\Sources\JSONFileSource;
use Kado\Translation\Tests\Fixtures\ArrayCallbackLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;


class JSONFileSourceTest extends TestCase
{


    /** @type JSONFileSource */
    private JSONFileSource $srcDe;
    /** @type JSONFileSource */
    private JSONFileSource $srcFr;
    /** @type ArrayCallbackLogger */
    private ArrayCallbackLogger $log;

    public function setUp() : void
    {

        parent::setUp();

        $this->log = new ArrayCallbackLogger();

        $this->srcDe = new JSONFileSource(
            \dirname( __DIR__, 3 ) . '/data/translations',
            new Locale( 'de', 'DE', 'utf-8' ),
            null,
            $this->log
        );

        $this->srcFr = new JSONFileSource(
            'my://data/translations',
            new Locale( 'fr', 'FR', 'utf-8' ),
            VfsManager::Create()->addHandler(
                new VfsHandler( 'MyVFS', 'my', '://', \dirname( __DIR__, 3 ) ) ),
            $this->log
        );

    }

    public function testInitLogs()
    {

        $this->assertSame(
            [ LogLevel::INFO, 'Init JSON file translation source for folder "'
                                    . dirname( __DIR__, 3 )
                                    . '/data/translations".',
                [ 'Class' => 'Kado\\Translation\\Sources\\JSONFileSource' ] ],
            $this->log->getMessage( 0 )
        );

    }
    public function testRead()
    {

        $this->assertSame( 'Ein Beispieltext', $this->srcDe->read( 'A example text' ) );
        $this->assertSame( 'Ein anderer Beispieltext', $this->srcDe->read( 'An other example text' ) );
        $this->assertSame( [ "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag" ], $this->srcDe->read( 'weekdays' ) );
        $this->assertSame( 'Bar', $this->srcDe->read( 'Foo', 'Bar' ) );
        $this->assertFalse( $this->srcDe->read( 'Foo' ) );

    }
    public function testReload()
    {

        $this->srcDe->setOption( 'file', null );
        $this->srcDe->setOption( 'folder', null );
        $this->srcDe->reload();
        $this->assertSame(
            [  LogLevel::NOTICE,
                'Reload data fails because there is no folder/file defined',
                [ 'Class' => 'Kado\\Translation\\Sources\\AbstractFileSource' ] ],
            $this->log->lastMessage()
        );
        $this->srcFr->setOption( 'file', \dirname(__DIR__, 3 ) . '/data/translations/de_DE.json' );

    }
    public function testSetLocale()
    {

        $this->srcDe->setLocale( new Locale( 'ru', 'RU' ) );
        $this->srcDe->reload();
        $this->assertSame(
            [  LogLevel::WARNING,
                'Unable to load JSON translations file. Invalid JSON format!',
                [ 'Class' => 'Kado\\Translation\\Sources\\JSONFileSource' ] ],
            $this->log->getMessage( $this->log->countMessages() - 2 )
        );

    }

}

