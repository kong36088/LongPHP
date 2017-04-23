<?php
/**
 * LongPHP
 * Author: William Jiang <william@jwlchian.cn>
 * Date: 2017/4/21
 * Time: 下午5:47
 */

namespace Long\Library;


use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
    }
}