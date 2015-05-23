<?php namespace App\Edisonthk;

class CSVService {

	public function getCSVInArray($file) {
		$logs = [];
		$file = fopen($file, 'r');
		while (($line = fgetcsv($file)) !== FALSE) {
		  $logs[] = $line;
		}
		fclose($file);

		return $logs;
	}
}