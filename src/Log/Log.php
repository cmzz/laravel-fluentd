<?php
declare(strict_types=1);

namespace MemberShareLib\LaravelCommonLib\Log;

/**
 * Class Log
 * @method static void debug(string $message, array $context = [], array $request = [], array $response = [])
 * @method static void info(string $message, array $context = [], array $request = [], array $response = [])
 * @method static void warning(string $message, array $context = [], array $request = [], array $response = [])
 * @method static void error(string $message, array $context = [], array $request = [], array $response = [])
 *
 * @method static void debugWithTag(string $tag, string $message, array $context = [], array $request = [], array $response = [])
 * @method static void infoWithTag(string $tag, string $message, array $context = [], array $request = [], array $response = [])
 * @method static void warningWithTag(string $tag, $message, array $context = [], array $request = [], array $response = [])
 * @method static void errorWithTag(string $tag, string $message, array $context = [], array $request = [], array $response = [])
 *
 */
class Log
{
    public function __call(string $name, array $arguments) : void
    {
        static::writelog($name, $arguments);
    }

    public static function __callStatic(string $name, array $arguments) : void
    {
        static::writelog($name, $arguments);
    }

    public static function writelog(string $name, array $arguments) : void
    {
        $argOffset = 0;

        if (ends_with($name, 'WithTag')) {
            $tag = data_get($arguments, '0', '');
            $message = sprintf('%s.%s', config('app.name'), $tag);
            $name = str_replace('WithTag', '', $name);
            $argOffset = 1;
        } else {
            $message = config('app.name');
        }

        $logData['request_id'] = request()->requestId;
        $logData['message'] = data_get($arguments, (string)(0 + $argOffset), '');
        $logData['context'] = data_get($arguments, (string)(1 + $argOffset), []);
        $logData['request'] = data_get($arguments, (string)(2 + $argOffset), []);
        $logData['response'] = data_get($arguments, (string)(3 + $argOffset), []);

        \Log::$name($message, $logData);
    }
}
