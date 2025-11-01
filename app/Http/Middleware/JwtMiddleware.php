<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use App\Traits\ApiResponseTrait;

class JwtMiddleware
{
	use ApiResponseTrait; 
    public function handle($request, Closure $next)
    {   
        try {
            return $next($request);  
        } catch (TokenExpiredException $e) {
			return $this->errorResponse('Token has expired.', 401); 
        } catch (TokenInvalidException $e) {
			return $this->errorResponse('Token is invalid.', 401);  
        } catch (JWTException $e) {
			return $this->errorResponse('Token not provided.', 401);  
        } catch (Exception $e) {
			return $this->errorResponse('Something went wrong.', 500);  
        }

        return $next($request);
    }
}
