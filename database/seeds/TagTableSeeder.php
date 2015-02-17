<?php

class TagTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('tag_table')->delete();
		DB::table("tag_table")->insert(array('id'=>1,'name'=>'java','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-06-22 23:30:26'));
	DB::table("tag_table")->insert(array('id'=>2,'name'=>'ruby','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2013-12-01 16:27:00'));
	DB::table("tag_table")->insert(array('id'=>3,'name'=>'php','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-09-05 16:29:51'));
	DB::table("tag_table")->insert(array('id'=>4,'name'=>'c','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-09-09 01:15:10'));
	DB::table("tag_table")->insert(array('id'=>5,'name'=>'mysql','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-08-28 15:20:35'));
	DB::table("tag_table")->insert(array('id'=>6,'name'=>'html','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-08-10 11:53:32'));
	DB::table("tag_table")->insert(array('id'=>7,'name'=>'javascript','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-10-04 11:05:56'));
	DB::table("tag_table")->insert(array('id'=>8,'name'=>'css','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-08-10 11:53:32'));
	DB::table("tag_table")->insert(array('id'=>9,'name'=>'ubuntu','created_at'=>'2013-09-11 00:07:45','updated_at'=>'2014-08-28 15:20:28'));
	DB::table("tag_table")->insert(array('id'=>10,'name'=>'jquery','created_at'=>'2013-09-12 07:29:05','updated_at'=>'2014-09-13 15:36:11'));
	DB::table("tag_table")->insert(array('id'=>11,'name'=>'android','created_at'=>'2013-09-12 07:29:05','updated_at'=>'2014-06-14 02:03:30'));
	DB::table("tag_table")->insert(array('id'=>12,'name'=>'rails','created_at'=>'2013-09-19 14:42:07','updated_at'=>'2014-04-12 17:51:12'));
	DB::table("tag_table")->insert(array('id'=>13,'name'=>'node-js','created_at'=>'2013-09-19 14:42:07','updated_at'=>'2013-09-19 14:42:07'));
	DB::table("tag_table")->insert(array('id'=>14,'name'=>'vba','created_at'=>'2014-02-15 15:51:31','updated_at'=>'2014-03-24 17:21:12'));
	DB::table("tag_table")->insert(array('id'=>15,'name'=>'objective-c','created_at'=>'2014-04-12 17:45:11','updated_at'=>'2014-06-02 01:01:55'));}

}
