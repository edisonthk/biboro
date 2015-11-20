<?php namespace Biboro\Http\Controllers;

use Session;
use Illuminate\Routing\Controller as BaseController;

class LogController extends BaseController {

	public function __construct(
		\Biboro\Edisonthk\AccountService $account_services,
		\Biboro\Edisonthk\CSVService $csv_service
	) {
		$this->account_services = $account_services;
		$this->csv_service = $csv_service;
	}
				
	public function getKeywordLog() {
		
		if($this->account_services->isAdmin()) {

			$csv_logs = [];

			$dir = storage_path("kw");
			if ($dh = opendir($dir)){
			    while (($file = readdir($dh)) !== false){
			    	if(strpos($file,".csv") !== false) {
			    		$csv_logs[] = $file;
			    	}
			    }
			    closedir($dh);
			}

			$logs = [];
			foreach ($csv_logs as $filename) {
				$path = storage_path()."/kw/".$filename;
				$log = $this->csv_service->getCSVInArray($path);
				
				$logs = array_merge($logs, $log);	
			}
			return response(view('admin.logs', ['logs' => $logs]));
		}

		return response('You account are not authorized', 403);
	}
}