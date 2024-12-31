<?php

namespace App\Http\Middleware;

use App\Helpers\ApiFormatter;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use Closure;
use PhpParser\Node\Expr\Cast\String_;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return response()->json(ApiFormatter::createJson(401, 'Authorization Header not provided'), 401);
        }

        try {
            //Verifikasi token yang dikirimkan
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(ApiFormatter::createJson(401, 'unauthorized'), 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token Has Expired'), 401);
        } catch (TokenInvalidException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token Is Invalid'), 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token Has been Blacklist'), 401);
        } catch (JWTException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token Could not be parsed'), 401);
        }
        return $next($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        return null;
    }
}
