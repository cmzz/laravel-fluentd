<?php

namespace MemberShareLib\LaravelCommonLib\Middleware;

use Closure;
use Illuminate\Http\Response;
use MemberShareLib\Common\Log;

class Logger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // before
        $requestInfo = [
            'path' => sprintf('%s:%s', $request->method(), $request->path()),
            'params' => $request->all(),
            'header' => $request->headers->all(),
        ];

        Log::info('request received', [], $requestInfo);

        /** @var Response $response */
        $response = $next($request);

        // after
        $statusCode = $response->getStatusCode();
        $responseInfo = [
            'status_code' => $statusCode,
            'body' => $response->content()
        ];

        if ($statusCode >= 400) {
            Log::error('request responded error', [], [], $responseInfo);
        } else {
            Log::info('request responded success', [], [], $responseInfo);
        }

        return $response;
    }
}
