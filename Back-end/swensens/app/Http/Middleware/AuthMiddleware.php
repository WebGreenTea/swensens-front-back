<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use stdClass;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Libraries\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Libraries\ActiveSession;


class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->header('Authorization');
            $headers = new stdClass();
            
            $auth = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'), $headers);
            
            // $auth = JWT::decode($token, env('JWT_KEY'), 'HS256');
            if ($auth->iss != env('APP_NAME')) {
                throw new Exception();
            }
            if(!ActiveSession::exist($auth->user_id,$token)){
                throw new Exception();                
            }
            $request->merge([
                'auth_user_id' => $auth->user_id,
                'auth_token' => $token
            ]);
        } catch (ExpiredException $e) {
            return response()->json(['code'=>'AUTH','success'=> false],401);
        } catch (Exception $e) {
            return response()->json(['code'=>'AUTH','success'=> false],401);
        }
        return $next($request);
    }
}
