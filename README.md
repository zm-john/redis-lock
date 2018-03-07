# Redis Lock

> Use redis feature to implement lock.
> I use this to control concurrent.

## useage

```
$client = new \Predis\Client($config, $option);
$lock = new \Quhang\RedisLock\Lock($client);

// try to get lock for key once
try {
    $lock->get($key);
    // your logic
} finally {
    $lock->release($key);
}

######################################################

// try to get lock for key many times untill timeout
try {
    $lock->tryToGet($key, $timeout = 10);
    // your logic
} catch(\Quhang\RedisLock\TimeoutException $e) {
    // timeout handle
} finally {
    $lock->release($key);
}
```

## license
MIT
