<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/5/3
 * Time: 上午11:53
 */

namespace Long\Library\Session;

use Long\Core\LongException;
use Long\Library\Logger\Log;


class SessionFileDriver extends SessionDriver implements \SessionHandlerInterface
{
    /**
     * @var string
     */
    protected $_filePath;

    /**
     * @var resource
     */
    protected $_fileHandle = null;

    /**
     * session id regx pattern
     * Leave blank now, will be used in future
     * @var string
     */
    protected $_sidRegexp = '';

    /**
     * File new flag
     *
     * @var    bool
     */
    protected $_fileNew;

    public function __construct($params = array())
    {
        parent::__construct($params);

        if (isset($this->_config['session_path'])) {
            $this->_config['session_path'] = rtrim($this->_config['session_path'], '/\\');
            ini_set('session.save_path', $this->_config['session_path']);
        } else {
            Log::debug('Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.');
            $this->_config['save_path'] = rtrim(ini_get('session.save_path'), '/\\');
        }
        Log::debug('SessionFileDriver init');
    }

    /**
     * Close the session
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close()
    {
        if (is_resource($this->_fileHandle)) {
            flock($this->_fileHandle, LOCK_UN);
            fclose($this->_fileHandle);

            $this->_fileHandle = $this->_fileNew = $this->_sessionId = NULL;
        }

        return $this->_success;
    }

    /**
     * Destroy a session
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $sessionId The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($sessionId)
    {
        if ($this->close() === $this->_success) {
            if (file_exists($this->_filePath . $sessionId)) {
                $this->_cookieDestroy();
                return unlink($this->_filePath . $sessionId)
                    ? $this->_success
                    : $this->_failure;
            }

            return $this->_success;
        } elseif ($this->_filePath !== NULL) {
            clearstatcache();
            if (file_exists($this->_filePath . $sessionId)) {
                $this->_cookieDestroy();
                return unlink($this->_filePath . $sessionId)
                    ? $this->_success
                    : $this->_failure;
            }

            return $this->_success;
        }

        return $this->_failure;
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxLifetime <p>
     * Sessions that have not updated for
     * the last maxLifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxLifetime)
    {
        if (!is_dir($this->_config['session_path']) OR ($directory = opendir($this->_config['session_path'])) === FALSE) {
            Log::debug("Session: Garbage collector couldn't list files under directory '" . $this->_config['session_path'] . "'.");
            return $this->_failure;
        }

        $ts = time() - $maxLifetime;

//        $pattern = ($this->_config['match_ip'] === TRUE)
//            ? '[0-9a-f]{32}'
//            : '';
        $pattern = '';

        $pattern = sprintf(
            '#\A%s' . $pattern . $this->_sidRegexp . '\z#',
            preg_quote($this->_config['session_cookie_name'])
        );

        while (($file = readdir($directory)) !== FALSE) {
            // If the filename doesn't match this pattern, it's either not a session file or is not ours
            if (!preg_match($pattern, $file)
                OR !is_file($this->_config['session_path'] . DIRECTORY_SEPARATOR . $file)
                OR ($mtime = filemtime($this->_config['session_path'] . DIRECTORY_SEPARATOR . $file)) === FALSE
                OR $mtime > $ts
            ) {
                continue;
            }

            unlink($this->_config['session_path'] . DIRECTORY_SEPARATOR . $file);
        }

        closedir($directory);

        return $this->_success;
    }

    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $savePath The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @throws LongException
     * @since 5.4.0
     */
    public function open($savePath, $name)
    {
        //TODO mkdir bug
        if (!is_dir($savePath)) {
            if (!mkdir($savePath, 0700, TRUE)) {
                throw new LongException("Session: Configured save path '" . $this->_config['session_path'] . "' is not a directory, doesn't exist or cannot be created.");
            }
        } elseif (!is_writable($savePath)) {
            throw new LongException("Session: Configured save path '" . $this->_config['session_path'] . "' is not writable by the PHP process.");
        }

        $this->_config['session_path'] = $savePath;
        $this->_filePath = $this->_config['session_path'] . DIRECTORY_SEPARATOR
            . $name // we'll use the session cookie name as a prefix to avoid collisions
            . md5($_SERVER['REMOTE_ADDR']);

        return $this->_success;
    }

    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $sessionId The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($sessionId)
    {
        // This might seem weird, but PHP 5.6 introduces session_reset(),
        // which re-reads session data
        if ($this->_fileHandle === null) {
            $this->_fileNew = !file_exists($this->_filePath . $sessionId);

            if (($this->_fileHandle = fopen($this->_filePath . $sessionId, 'c+b')) === FALSE) {
                Log::error("Session: Unable to open file '" . $this->_filePath . $sessionId . "'.");
                return $this->_failure;
            }

            if (flock($this->_fileHandle, LOCK_EX) === FALSE) {
                Log::error("Session: Unable to obtain lock for file '" . $this->_filePath . $sessionId . "'.");
                fclose($this->_fileHandle);
                $this->_fileHandle = NULL;
                return $this->_failure;
            }

            // Needed by write() to detect session_regenerate_id() calls
            $this->_sessionId = $sessionId;

            if ($this->_fileNew) {
                //TODO 权限修改为0600，mac下虚拟机存在权限控制的问题，待修复
                chmod($this->_filePath . $sessionId, 0606);
                $this->_fingerprint = md5('');
                return '';
            }
        }
        // We shouldn't need this, but apparently we do ...
        // See https://github.com/bcit-ci/CodeIgniter/issues/4039
        elseif ($this->_fileHandle === FALSE) {
            return $this->_failure;
        } else {
            rewind($this->_fileHandle);
        }

        $sessionData = '';
        for ($read = 0, $length = filesize($this->_filePath . $sessionId); $read < $length; $read += self::strlen($buffer)) {
            if (($buffer = fread($this->_fileHandle, $length - $read)) === FALSE) {
                break;
            }

            $sessionData .= $buffer;
        }

        $this->_fingerprint = md5($sessionData);
        return $sessionData;
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $sessionId The session id.
     * @param string $sessionData <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($sessionId, $sessionData)
    {
        echo $sessionId . '<br/>';
        var_dump($sessionData);
        // If the two IDs don't match, we have a session_regenerate_id() call
        // and we need to close the old handle and open a new one
        if ($sessionId !== $this->_sessionId && ($this->close() === $this->_failure OR $this->read($sessionId) === $this->_failure)) {
            return $this->_failure;
        }

        if (!is_resource($this->_fileHandle)) {
            return $this->_failure;
        } elseif ($this->_fingerprint === md5($sessionId)) {
            return (!$this->_fileNew && !touch($this->_filePath . $sessionId))
                ? $this->_failure
                : $this->_success;
        }

        if (!$this->_fileNew) {
            ftruncate($this->_fileHandle, 0);
            rewind($this->_fileHandle);
        }

        if (($length = strlen($sessionData)) > 0) {
            for ($written = 0; $written < $length; $written += $result) {
                if (($result = fwrite($this->_fileHandle, substr($sessionData, $written))) === FALSE) {
                    break;
                }
            }

            if (!is_int($result)) {
                $this->_fingerprint = md5(substr($sessionData, 0, $written));
                Log::error('Session: Unable to write data.');
                return $this->_failure;
            }
        }

        $this->_fingerprint = md5($sessionData);
        return $this->_success;
    }
}