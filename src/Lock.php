<?php
declare(ticks = 1);
namespace Quhang\RedisLock;

use Predis\ClientInterface;

class Lock
{
    /**
     * redis client
     * @var [type]
     */
    protected $redis;

    /**
     * default expire
     * @var integer
     */
    protected $expire = 60;

    /**
     * pause time(Millisecond)
     * @var integer
     */
    protected $sleep = 10;

    protected $timeout = false;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * get lock for key
     * @param  string $key
     * @return bool
     */
    public function get($key)
    {
        return $this->redis->setnx($key, 1, 'EX', $this->expire, 'NX') === 1;
    }

    /**
     * release lock
     * @param  string $key
     * @return bool
     */
    public function release($key)
    {
        return $this->redis->del($key) === 1;
    }

    /**
     * try to get lock until timeout
     * @param  string      $key
     * @param  int|integer $timeout seconds later will be timeout
     * @return [type]
     */
    public function tryToGet($key, $timeout = 10)
    {
        $this->alarm($seconds);

        while (!$this->get($key)) {
            $this->sleep();
            $this->checkTimeout();
        }
    }

    protected function checkTimeout()
    {
        if ($this->timeout) {
            $this->timeout = false;
            throw new TimeoutException();
        }
    }

    /**
     * set alarm when need many times to try
     * @param  int    $seconds
     */
    protected function alarm($seconds)
    {
        pcntl_alarm($seconds);
        pcntl_signal(SIGALRM, function () {
            $this->timeout = true;
        });
    }

    protected function sleep()
    {
        usleep($this->sleep * 1000);
    }
}
