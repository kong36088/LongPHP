<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Library\Session;

use Long\Core\Config;

/**
 * Class SessionDriver
 * @package Long\Library\SessionDriver
 */
abstract Class SessionDriver implements \SessionHandlerInterface {

    /**
     * configurations
     * @var array
     */
    protected $_config = array();

    protected $_success = 0;

    protected $_failure = -1;

    /**
     * Read session ID
     *
     * Used to detect session_regenerate_id() calls because PHP only calls
     * write() after regenerating the ID.
     *
     * @var	string
     */
    protected $_sessionId;

    /**
     * Security ensure
     *
     * @var string
     */
    protected $_fingerprint;


    /**
     * Lock placeholder
     *
     * @var	mixed
     */
    protected $_lock = FALSE;

    public function __construct(array $params = array())
    {
        $this->_config =& $params;

        if (isPHP('7'))
        {
            $this->_success = TRUE;
            $this->_failure = FALSE;
        }
        else
        {
            $this->_success = 0;
            $this->_failure = -1;
        }
    }

    /**
     * Get lock
     *
     * A dummy method allowing drivers with no locking functionality
     * (databases other than PostgreSQL and MySQL) to act as if they
     * do acquire a lock.
     *
     * @param	string	$sessionId
     * @return	bool
     */
    protected function _get_lock($sessionId)
    {
        $this->_lock = TRUE;
        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Release lock
     *
     * @return	bool
     */
    protected function _release_lock()
    {
        if ($this->_lock)
        {
            $this->_lock = FALSE;
        }

        return TRUE;
    }


    /**
     * Cookie destroy
     *
     * Internal method to force removal of a cookie by the client
     * when session_destroy() is called.
     *
     * @return	bool
     */
    protected function _cookieDestroy()
    {
        return setcookie(
            $this->_config['session_cookie_name'],
            NULL,
            1,
            $this->_config['cookie_path'],
            $this->_config['cookie_domain'],
            $this->_config['cookie_secure'],
            TRUE
        );
    }

    /**
     * Fail
     *
     * Drivers other than the 'files' one don't (need to) use the
     * session.save_path INI setting, but that leads to confusing
     * error messages emitted by PHP when open() or write() fail,
     * as the message contains session.save_path ...
     * To work around the problem, the drivers will call this method
     * so that the INI is set just in time for the error message to
     * be properly generated.
     *
     * @return	mixed
     */
    protected function _fail()
    {
        ini_set('session.save_path', Config::get('session_save_path'));
        return $this->_failure;
    }
}