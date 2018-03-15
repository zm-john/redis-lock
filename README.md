# Redis Lock

> Use redis feature to implement lock.
> I use this to control concurrent.

## Laravel

### laravel 5.5+
you can publish config, then set redis connection in .env `REDIS_LOCK_CONNECTION`.
```
php artisan vendor:publish
```

### laravel 5.1 ~ 5.4
if you want to config your webhook, you need add `Quhang\RedisLock\Laravel\ServiceProvider` to `config/app.php`
```
'providers' => [
    // ...
    Quhang\RedisLock\Laravel\ServiceProvider::class,
    // ...
]
```
add `Quhang\RedisLock\Laravel\Facades\Lock` to `config/app.php`
```
'aliases' => [
    // ...
    'Lock' => Quhang\RedisLock\Laravel\Facades\Lock::class,
    // ...
]
```

### usage

#### `get` method will return true or false.
```
    try {
        $rest = \Quhang\RedisLock\Laravel\Facades\Lock::get($key);
        if ($rest) {
            // get lock success
        } else {
            // get lock fail
        }
    } finally {
        \Quhang\RedisLock\Laravel\Facades\Lock::release($key);
    }
```

#### `tryToGet` method will be block until get lock or timeout.
```
    try {
        \Quhang\RedisLock\Laravel\Facades\Lock::tryToGet($key, $timeout = 10);
    } catch (Quhang\RedisLock\TimeoutException $e) {
        // timeout exception
    } finally {
        \Quhang\RedisLock\Laravel\Facades\Lock::release($key);
    }
```


## Other

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
