<?php /** @noinspection ALL */


namespace App\Forwardie\DB;


use UnexpectedValueException;

class Redis extends \Illuminate\Support\Facades\Redis
{
    /**
     * Test Redis Database Connection
     *
     * @return bool
     */
    public static function testConnection()
    {
        try {
            $data = Redis::ping();
            if ($data == 'PONG') {
                return true;
            }
        } catch (UnexpectedValueException $e) {
            return false;
        }
    }

    /**
     * Get key data from redis database
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get($key = '')
    {
        return parent::get($key);
    }

    /**
     * Ping The Redis Database
     *
     * @return mixed
     */
    public static function ping()
    {
        return parent::ping();
    }

    public static function eval(...$args)
    {

    }

    /**
     * Write redis database key => value pair
     *
     * @param string              $key
     * @param string|object|array $value
     * @param int                 $expire
     *
     * @return mixed
     */
    public static function set($key, $value, $expire = null)
    {
        switch (gettype($value)) {
            case 'object':
            case 'array':
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                break;
        }
        if ($expire === null) {
            return parent::set($key, $value);
        }
        return parent::set($key, $value, "EX", $expire);
    }

    /**
     * Config Command
     *
     * @param string $command
     * @param string $value
     *
     * @return mixed
     */
    public static function config($command, $value)
    {
        return parent::config($command, $value);
    }

    /**
     * Check Redis Key is Exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function exists($key)
    {
        $exists = parent::exists($key);
        return !!$exists;
    }

    /**
     * Delete redis database keys
     *
     * @param array|string $keys
     *
     * @return mixed
     */
    public static function del($keys)
    {
        return parent::del($keys);
    }

    /**
     * Set key value and get older value
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    public static function getset($key, $value)
    {
        return parent::getset($key, $value);
    }

    /**
     * Add expiration time to key
     *
     * @param string $key
     * @param int    $second
     *
     * @return mixed
     */
    public static function expire($key, $second)
    {
        return parent::expire($key, $second);
    }

    /**
     * Create Redis Pipeline
     *
     * @param callable $callable {
     * @signature (Redis $pipe)
     *                           }
     *
     * @return mixed
     */
    public static function pipeline($callable)
    {
        return parent::pipeline($callable);
    }

    /**
     * List all keys to matches prefix
     *
     * @param string $prefix
     *
     * @return mixed
     */
    public static function keys($prefix)
    {
        return parent::keys($prefix);
    }

    /**
     * Count of Length
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function llen($key)
    {
        return parent::llen($key);
    }

    /**
     * Hash Delete
     *
     * @param string $hash
     * @param string $field
     *
     * @return mixed
     */
    public static function hdel($hash, $field)
    {
        return parent::hdel($hash, $field);
    }

    /**
     * Remove and Get The First Element in a List
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function lpop($key)
    {
        return parent::lpop($key);
    }

    /**
     * Prepend One or Multiple Values To a List
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public static function lpush($key, ...$value)
    {
        return parent::lpush($key, ...$value);
    }


    /**
     * Trim Redis values in a key from the start
     *
     * @param string $key
     * @param mixed  $start
     * @param mixed  $end
     *
     * @return mixed
     */
    public static function ltrim($key, $start, $end)
    {
        return parent::ltrim($key, $start, $end);
    }

    /**
     * Get Redis values in a key from the start
     *
     * @param string $key
     * @param mixed  $start
     * @param mixed  $end
     *
     * @return mixed
     */
    public static function lrange($key, $start, $end)
    {
        return parent::lrange($key, $start, $end);
    }

    /**
     * Remove Redis values in a key with desired count
     *
     * @param string $key
     * @param mixed  $count
     * @param mixed  $value
     *
     * @return mixed
     */
    public static function lrem($key, $count, $value)
    {
        return parent::lrem($key, $count, $value);
    }

    /**
     * Remove and Get The Last Element in a List
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function rpop($key)
    {
        return parent::rpop($key);
    }

    /**
     * Prepend One or Multiple Values To a List
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public static function rpush($key, ...$value)
    {
        return parent::rpush($key, ...$value);
    }

    /**
     * Hash Set
     *
     * @param string $hash
     * @param string $field
     * @param mixed  $value
     *
     * @return mixed
     */
    public static function hset($hash, $field, $value)
    {
        $value = json_encode($value);
        return parent::hset($hash, $field, $value);
    }

    /**
     * Hash Get
     *
     * @param string $hash
     * @param string $field
     *
     * @return mixed
     */
    public static function hget($hash, $field)
    {
        return parent::hget($hash, $field);
    }

    /**
     * Hash Delete
     *
     * @param string $hash
     * @param string $field
     *
     * @return mixed
     */
    public static function hexists($hash, $field)
    {
        return parent::hexists($hash, $field);
    }

    /**
     * Hash Multi Set
     *
     * @param string $hash
     * @param array  $fields
     *
     * @return mixed
     */
    public static function hmset($hash, $fields)
    {
        foreach ($fields as $key => $value) {
            if (!is_string($value)) {
                $fields[$key] = json_encode($value);
            }
        }
        return parent::hmset($hash, $fields);
    }

    /**
     * Hash Delete
     *
     * @param string $hash
     * @param bool   $nullIfBlank
     *
     * @return mixed
     */
    public static function hgetall($hash, bool $nullIfBlank = false)
    {
        $output = parent::hgetall($hash);
        if (count($output) == 0 && $nullIfBlank === true) {
            return null;
        }
        return $output;
    }

    /**
     * Subscribe to Channel in Redis Database
     *
     * @param string $channel
     *
     * @return mixed
     */
    public static function subscribe($channel)
    {
        return parent::subscribe($channel);
    }

    public static function publish($channel, $message)
    {
        return parent::publish($channel, $message);
    }

    /**
     * Delete Keys to Use Prefix
     *
     * @param string $prefix
     *
     * @return mixed
     */
    public static function deletePrefix($prefix)
    {
        return self::pipeline(function ($pipe) use ($prefix) {
            $keys = self::keys($prefix);
            foreach ($keys as $key) {
                $pipe->del($key);
            }
        });
    }

    /**
     * DELETE ALL REDIS DATABASE !IMPORTANT
     *
     * @param string $appKey
     *
     * @return bool
     */
    public static function flushall($appKey)
    {
        if ($appKey === env('APP_KEY')) {
            return parent::flushall();
        }
        return false;
    }

    public static function getConnection()
    {
        return parent::connection();
    }
}
