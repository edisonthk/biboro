<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Account;

class FixVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make old version adapt to new version.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Edisonthk\WorkbookService $workbook)
    {
        parent::__construct();

        $this->workbook = $workbook;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $accountWithoutWorkbook = Account::select("accounts.id","accounts.name")
            ->leftJoin("workbooks","workbooks.account_id","=","accounts.id")
            ->whereNull("workbooks.id")
            ->get();

        foreach ($accountWithoutWorkbook as $account) {
            $this->workbook->create($account->name, "", $account->id);
        }
    }
}
