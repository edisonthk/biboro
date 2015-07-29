<?php

use App\Model\Workbook;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    protected $baseUrl = "localhost";

    protected $testEnvironment = 'testing';

    public $loginInAsUserId = 23;

    public function login()
    {
        $account = $this->app->make("\App\Edisonthk\AccountService");
        $account->login($this->loginInAsUserId);
    }

    public function setUp()
    {
        parent::setUp();

        $this->login();
    }

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

    public function tearDown()
    {

        $account = $this->app->make("\App\Edisonthk\AccountService");
        if($account->hasLogined()) {
            $workbooks = Workbook::where("account_id","=",$this->loginInAsUserId);
            foreach ($workbooks as $workbook) {
                $workbook->snippets()->detach();
            }
            
            $workbooks->delete();
        }

        parent::tearDown();
    }
}
