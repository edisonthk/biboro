<?php namespace App\Http\Middleware;

use Closure;

class CorsResponse {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin','http://localhost:3000');
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $response->header('XSRF-TOKEN', csrf_token());
		return $response;
	}

}
