<?php
namespace Tecnolaw\Authorization\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
//use App\Token;


class IsAdminMiddleware
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
    	$user = $this->auth->user();
        if ($user!=null) {
        	if ($user->isAdmin!=1) {
	            return response([
	                'errors'=> ['¡Administrador invalido!'],
	                'data' => []
	            ], 401);
        	}
        }
        return $next($request);
    }
}