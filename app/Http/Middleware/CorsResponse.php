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

        $listAllowed = [
            'http://localhost:3000',
            'http://apptest.biboro.org',
        ];
        $origin = $request->header('origin');
        if(in_array($origin, $listAllowed)) {
            $response->header('Access-Control-Allow-Origin',$origin);
        }

        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $response->header('XSRF-TOKEN', csrf_token());
		return $response;
	}

}
