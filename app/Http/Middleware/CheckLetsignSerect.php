<?php

namespace App\Http\Middleware;

use Closure;


class CheckLetsignSerect
{
    public function handle($request, Closure $next)
    {
        $kintoneInfo = config('letsign.kintoneInfo');
        $secret = $request->header('secret', '');
        if ($kintoneInfo['secret'] != $secret) {
            $response = [
                'status' => 2,
                'message' => 'Unauthorized',
            ];

            return response()->json($response, 413);
        } else {
            return $next($request);
        }
    }
}
