<?php namespace Biboro\Http\Middleware;

use Closure;
use Session;
use Biboro\Edisonthk\AccountService;

class AutoLogin {

    public $accountServices;

    public function __construct(
            \Biboro\Edisonthk\AccountService $accountServices
        )
    {
        $this->accountServices = $accountServices;
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
        if(!Session::has("user")) {
            if(!$request->isJson() && $request->hasCookie(AccountService::_REMEMBER_TOKEN_KEY)) {

                $this->accountServices->setRequestUri($request->fullUrl());
                return redirect('/account/signin');
            }
        }

        return $next($request);
	}

}
