<?php

namespace Quhang\RedisLock;

class TimeoutException extends \Exception
{
    protected $message = 'time out';
}