<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/5/2
 * Time: 下午3:43
 */

namespace Long\Library\Session;


use Long\Core\Config;
use Long\Library\Logger\Log;

class LongSession implements LongSessionInterface
{
    protected static $_driver = 'file';

    public static function initialize()
    {
        //load configs
        self::$_driver = empty(Config::get('session_driver')) ? Config::get('session_driver') : 'file';

        //configure session
        self::_configure();

        $sessionDriver = __NAMESPACE__ . '\Session' . ucfirst(self::$_driver) . 'Driver';

        $sessionDriver = new $sessionDriver(Config::getAll());
        if ($sessionDriver instanceof \SessionHandlerInterface) {
            session_set_save_handler($sessionDriver, TRUE);
        } else {
            Log::error("Session: Driver '" . self::$_driver . "' doesn't implement SessionHandlerInterface. Aborting.");
            return;
        }


        session_start();
    }

    /**
     * Configuration
     *
     * Handle input parameters and configuration defaults
     *
     * @return    void
     */
    protected static function _configure()
    {

        $expiration = Config::get('session_expiration');
        if (isset($params['cookie_lifetime'])) {
            $params['cookie_lifetime'] = (int)$params['cookie_lifetime'];
        } else {
            $params['cookie_lifetime'] = (!isset($expiration)) ? 0 : (int)$expiration;
        }

        isset($params['cookie_name']) OR $params['cookie_name'] = Config::get('session_cookie_name');

        if (empty($params['cookie_name'])) {
            $params['cookie_name'] = ini_get('session.name');
        } else {
            ini_set('session.name', $params['cookie_name']);
        }

        isset($params['cookie_path']) OR $params['cookie_path'] = Config::get('cookie_path');
        isset($params['cookie_domain']) OR $params['cookie_domain'] = Config::get('cookie_domain');
        isset($params['cookie_secure']) OR $params['cookie_secure'] = (bool)Config::get('cookie_secure');

        session_set_cookie_params(
            $params['cookie_lifetime'],
            $params['cookie_path'],
            $params['cookie_domain'],
            $params['cookie_secure'],
            TRUE // HttpOnly; Yes, this is intentional and not configurable for security reasons
        );

        if (empty($expiration)) {
            $params['expiration'] = (int)ini_get('session.gc_maxlifetime');
        } else {
            $params['expiration'] = (int)$expiration;
            ini_set('session.gc_maxlifetime', $expiration);
        }

        // Security is king
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
    }

    /**
     * Destroy session
     *
     * @return void
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Get session value
     *
     * @param $key
     * @return string|null
     */
    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * get and remove key
     * @param $key
     * @return null|string
     */
    public static function pull($key)
    {
        $value = self::get($key);
        self::remove($key);

        return $value;
    }

    /**
     * get session id
     * @return string
     */
    public static function getId()
    {
        return session_id();
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     * Batch set session
     *
     * @param array $values
     * @return void
     */
    public static function batchSet(array $values)
    {
        foreach ($values as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * @return array
     */
    public static function all()
    {
        return $_SESSION;
    }

    /**
     * @param $key
     * @return void
     */
    public static function remove($key)
    {
        if (isset($_SESSION))
            unset($_SESSION[$key]);
    }

    /**
     * Regenerate session id
     *
     * @param bool $destroy
     * @return void
     */
    public static function regenerate($destroy = FALSE)
    {
        session_regenerate_id($destroy);
    }

    /**
     * delete all session data
     *
     * @return void
     */
    public static function flush()
    {
        session_destroy();
    }
}