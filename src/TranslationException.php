<?php
/**
 * @author         Sweet Andi
 * @copyright    © 2026, Sweet Andi
 * @license        MIT
 * @since          2026-03-30
 * @version        1.0.0
 */


declare( strict_types = 1 );


namespace Kado\Translation;


use \Kado\KadoException;


/**
 * The translation base exception.
 */
class TranslationException extends KadoException
{


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * TranslationException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param null|\Throwable $previous
     */
    public function __construct( string $message, int $code = 0, ?\Throwable $previous = null )
    {

        parent::__construct( $message, $code, $previous );

    }

    #endregion


}

