<?php
namespace Tecnolaw\Authorization\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Token;


class AuthenticateAdminMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$this->auth->guard($guard)->guest()) {
        	if ($this->auth->user()->token_model->back_office==0) {
	            return response([
	                //'message'=> trans('auth.check.message.token'),
	                'message'=> 'token no autorizado para acceder al back office',
	                'data' => []
	            ], 401);
        	}
        }
        return $next($request);
    }
}