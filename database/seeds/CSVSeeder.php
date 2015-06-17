<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CSVSeeder extends Seeder
{

    protected $table = 'table name';
    protected $isDisabled = false;

    public function run()
    {
        if( !$this->isDisabled ) {
            DB::table($this->table)->delete();
        }
        $csvFile = dirname(__FILE__) . '/data/' . $this->table . '.csv';
        $data = $this->csvToArray($csvFile);
        foreach( $data as $row ){
            DB::table($this->table)->insert($row);
        }
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = NULL;
        $data = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 50000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;

                    foreach ($header as $key => $value) {
                        if(empty($value)) {
                            unset($header[$key]);
                        }
                    }
                } else {
                    $newRow = [];
                    
                        foreach ($row as $index => $value) {
                            if(array_key_exists($index, $header)) {
                                $value = str_replace("__((COMMA))__", ",", $value);
                                $value = str_replace("___NEXT_LINE____", "\n", $value);
                                $newRow[$header[$index]] = $value;    
                            }
                        }    
                    
                    
                    $data[] = $newRow;
                }
            }
        }
        return $data;
    }
}
