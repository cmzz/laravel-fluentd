<?php
/**
 * Project: member-share-lib
 * File: RequestId.php
 *
 * Created by PhpStorm.
 * User: wuzhuo
 * Email: zhuowu@hk01.com
 * Date: 18-10-29
 * Time: 上午10:20
 */

declare(strict_types=1);

namespace MemberShareLib\LaravelCommonLib\Middleware;

use Illuminate\Http\Request;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use Closure;

class RequestId
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('x-request-id')) {
            $request->requestId = $request->header('x-request-id');
        } else {
            $request->requestId = $this->genRequestId();
        }

        return $next($request);
    }

    protected function genRequestId()
    {
        try {
            $id = Uuid::uuid4()->toString();
            return $id;
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
