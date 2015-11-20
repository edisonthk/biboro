<?php namespace Biboro\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		// 'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
        'Biboro\Http\Middleware\CorsResponse'
		// 'Biboro\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		
		'auth.basic'     => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest'          => 'Biboro\Http\Middleware\RedirectIfAuthenticated',

		// require login
		'auth'     => 'Biboro\Http\Middleware\Authenticate',
        'auth.autologin' => 'Biboro\Http\Middleware\AutoLogin',
	];

}
