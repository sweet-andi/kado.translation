<?php


declare( strict_types = 1 );


namespace Kado\Translation\Tests\Fixtures;


use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;


class ArrayCallbackLogger implements LoggerInterface
{


    use LoggerTrait;

    private array $_messages;
    private int $_messageCount;


    public function __construct()
    {
        $this->_messages = [];
        $this->_messageCount = 0;
    }


    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log( $level, $message, array $context = [] ): void
    {
        $this->_messages[] = [ $level, $message, $context ];
        $this->_messageCount++;
    }

    public function lastMessage() : ?array
    {
        if ( $this->_messageCount < 1 ) { return null; }
        return $this->_messages[ $this->_messageCount - 1 ];
    }

    public function countMessages() : int
    {
        return $this->_messageCount;
    }

    public function getMessage( int $index ) : ?array
    {
        if ( ! $this->hasMessage( $index ) ) { return null; }
        return $this->_messages[ $index ];
    }

    public function hasMessage( int $index ) : bool
    {
        return $index > -1 && $index < $this->_messageCount;
    }


}

