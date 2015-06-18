<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'scores';
    
	public $timestamps = true;

}