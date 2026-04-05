<?php


namespace Kado\Translation\Tests;


use Kado\Locale\Locale;
use Kado\Translation\Sources\ISource;
use Kado\Translation\Sources\PHPFileSource;
use Kado\Translation\Tests\Fixtures\ArrayCallbackLogger;
use Kado\Translation\Translator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;


class TranslatorTest extends TestCase
{


    /** @type ArrayCallbackLogger|LoggerInterface */
    private LoggerInterface|ArrayCallbackLogger $log;
    /** @type Locale */
    private Locale $lcDeDE;
    /** @type Locale */
    private Locale $lcDeAT;
    /** @type Locale */
    private Locale $lcFrFR;
    /** @type ISource|PHPFileSource */
    private ISource|PHPFileSource $src;
    /** @type Translator */
    private Translator $trans;

    public function setUp() : void
    {

        parent::setUp();

        $this->log = new ArrayCallbackLogger();
        $this->lcDeDE = new Locale( 'de', 'DE', 'utf-a' );
        $this->lcDeAT = new Locale( 'de', 'AT' );
        $this->lcFrFR = new Locale( 'fr', 'FR', 'utf-8' );
        $this->src = new PHPFileSource(
            \dirname( __DIR__, 3 ) . '/data/translations', $this->lcFrFR );
        $this->trans = new Translator( $this->lcDeDE );
        Translator::RemoveInstance();

    }

    public function testConstruct()
    {

        $this->assertInstanceOf( Translator::class, $this->trans );

    }
    public function testGetSource()
    {

        $this->assertNull( $this->trans->getSource( 'foo' ) );
        $this->trans->addSource( 'foo', $this->src );
        $this->assertInstanceOf( ISource::class, $this->trans->getSource( 'foo' ) );
        $this->lcDeDE->registerAsGlobalInstance();
        $trans = new Translator();

    }
    public function testRemoveSource()
    {

        $this->assertNull( $this->trans->getSource( 'foo' ) );
        $this->trans->addSource( 'foo', $this->src );
        $this->assertInstanceOf( ISource::class, $this->trans->getSource( 'foo' ) );
        $this->trans->removeSource( 'foo' );
        $this->assertNull( $this->trans->getSource( 'foo' ) );

    }
    public function testCleanSources()
    {

        $this->assertSame( 0, $this->trans->countSources() );
        $this->trans->addSource( 'foo', $this->src );
        $this->trans->addSource( 'bar', $this->src );
        $this->assertSame( 2, $this->trans->countSources() );
        $this->trans->cleanSources();
        $this->assertFalse($this->trans->hasSources() );

    }
    public function testRead()
    {

        $this->trans->addSource( '_', $this->src );

        $this->assertSame( 'Ein Beispieltext', $this->trans->read( 'A example text' ) );
        $this->assertSame( 'Ein anderer Beispieltext', $this->trans->read( 'An other example text', '_' ) );
        $this->assertSame( '…', $this->trans->read( 'A other example text', '_', '…' ) );
        $this->assertSame( '…', $this->trans->read( 'A other example text', null, '…' ) );

    }
    public function testGetSources()
    {

        $this->assertSame( [], $this->trans->getSources() );
        $this->trans->addSource( '_', $this->src );
        $this->assertSame( [ '_' => $this->src ], $this->trans->getSources() );

    }
    public function testGetSourcesIterator()
    {

        $this->assertFalse( $this->trans->getSourcesIterator()->valid() );
        $this->trans->addSource( '_', $this->src );
        $this->assertTrue( $this->trans->getSourcesIterator()->valid() );

    }
    public function testHasSource()
    {

        $this->assertFalse( $this->trans->hasSource( '_' ) );
        $this->trans->addSource( '_', $this->src );
        $this->assertTrue( $this->trans->hasSource( '_' ) );

    }
    public function testGetSourceNames()
    {

        $this->assertSame( [], $this->trans->getSourceNames() );
        $this->trans->addSource( '_', $this->src );
        $this->trans->addSource( '-', $this->src );
        $this->assertSame( [ '_', '-' ], $this->trans->getSourceNames() );

    }
    public function testSetAsGlobalInstance()
    {

        $this->assertFalse( Translator::HasInstance() );
        $this->trans->setAsGlobalInstance();
        $this->assertTrue( Translator::HasInstance() );
        $this->assertSame( $this->trans, Translator::GetInstance() );
        Translator::RemoveInstance();
        $this->assertInstanceOf( Translator::class, Translator::GetInstance() );

    }

}
