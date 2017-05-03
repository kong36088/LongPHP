<?php
/**
 * Longphp
 * Author: William Jiang
 */

namespace Long\Library\Session;

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

    public function __construct(array $params)
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
}