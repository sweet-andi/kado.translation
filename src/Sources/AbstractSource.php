<?php
/**
 * @author         Sweet Andi
 * @copyright    © 2026, Sweet Andi
 * @license        MIT
 * @since          2026-03-30
 * @version        1.0.0
 */


declare( strict_types = 1 );


namespace Kado\Translation\Sources;


use \Kado\Locale\Locale;
use Override;
use \Psr\Log\LoggerInterface;


/**
 * Defines an abstract ISource implementation.
 *
 * @since v0.1.0
 */
abstract class AbstractSource implements ISource
{


    #region // – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –

    /**
     * All options of the Source implementation
     *
     * @type array
     */
    protected array $_options = [];

    #endregion


    #region // – – –   P R O T E C T E D   C O N S T R U C T O R   – – – – – – – – – – – – – – – – –

    /**
     * AbstractSource constructor.
     *
     * @param Locale $locale
     * @param null|LoggerInterface $logger Optional logger
     */
    protected function __construct( Locale $locale, ?LoggerInterface $logger = null )
    {

        $this->_options[ 'locale' ] = $locale;

        $this->setLogger( $logger );

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Gets the current defined locale.
     *
     * @return Locale
     */
    #[Override] public final function getLocale() : Locale
    {

        return $this->_options[ 'locale' ];

    }

    /**
     * Gets the current defined logger.
     *
     * @return LoggerInterface|null
     */
    #[Override] public final function getLogger() : ?LoggerInterface
    {

        return $this->_options[ 'logger' ];

    }

    /**
     * Sets a new locale.
     *
     * @param Locale $locale
     * @return self
     */
    #[Override] public final function setLocale(Locale $locale ) : self
    {

        $this->_options[ 'locale' ] = $locale;

        $this->logInfo( 'Change Translation source locale to "' . $locale . '".', __CLASS__ );

        unset( $this->_options[ 'data' ] );

        return $this;

    }

    /**
     * Sets a new logger or null if no logger should be used.
     *
     * @param LoggerInterface|null $logger
     * @return self
     */
    #[Override] public final function setLogger(?LoggerInterface $logger ) : self
    {

        $this->_options[ 'logger' ] = $logger;

        unset( $this->_options[ 'data' ] );

        return $this;

    }

    /**
     * Gets all options of the translation source.
     *
     * @return array
     */
    #[Override] public final function getOptions() : array
    {

        return $this->_options;

    }

    /**
     * Gets the option value of option with defined name or $defaultValue if the option is unknown.
     *
     * @param string $name         The name of the option.
     * @param mixed  $defaultValue This value is returned if the option not exists.
     *
     * @return mixed
     */
    #[Override] public final function getOption(string $name, mixed $defaultValue = false ) : mixed
    {

        if ( ! $this->hasOption( $name ) )
        {
            return $defaultValue;
        }

        return $this->_options[ $name ];

    }

    /**
     * Gets if an option with defined name exists.
     *
     * @param string $name The option name.
     * @return bool
     */
    #[Override] public final function hasOption(string $name ) : bool
    {

        return \array_key_exists( $name, $this->_options );

    }

    /**
     * Sets a options value.
     *
     * @param string $name
     * @param mixed  $value
     * @return self
     */
    #[Override] public function setOption(string $name, mixed $value ) : self
    {

        $this->_options[ $name ] = $value;

        $this->logInfo( 'Set Translation source option "' . $name . '" to "' . $value . '".', __CLASS__ );

        unset( $this->_options[ 'data' ] );

        return $this;

    }

    #endregion


    #region // – – –   P R O T E C T E D   M E T H O D S   – – – – – – – – – – – – – – – – – – – – –

    protected function logInfo( string $message, string $class ) : void
    {

        $this->_options[ 'logger' ]?->info( $message, [ 'Class' => $class ] );

    }
    protected function logNotice( string $message, string $class ) : void
    {

        $this->_options[ 'logger' ]?->notice( $message, [ 'Class' => $class ] );

    }
    protected function logWarning( string $message, string $class ) : void
    {

        $this->_options[ 'logger' ]?->warning( $message, [ 'Class' => $class ] );

    }

    #endregion


}

