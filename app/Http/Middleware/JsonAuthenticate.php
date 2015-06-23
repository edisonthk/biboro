<?php namespace App\Http\Middleware;

use Closure;
use Cookie;
use Session;
use Illuminate\Contracts\Auth\Guard;

class JsonAuthenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Session::has("user") && array_key_exists("id", Session::get("user"))) {
			return $next($request);
		}else{
			return response('Unauthorized.', 401);
		}
	}

}
