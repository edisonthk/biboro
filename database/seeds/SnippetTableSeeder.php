<?php

class SnippetTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('snippet_tag')->delete();
		DB::table('snippet_table')->delete();
		

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Ng If-Else in AngularJS','created_at'=>'2014-10-04 11:05:56','updated_at'=>'2014-10-04 11:05:56','content'=>'    &lt;div ng-if=&quot;video == video.large&quot;&gt;\n        &lt;!-- code to render a large video block--&gt;\n    &lt;/div&gt;\n    &lt;div ng-if=&quot;video != video.large&quot;&gt;\n        &lt;!-- code to render the regular video block --&gt;\n    &lt;/div&gt;'));
DB::table('snippet_tag')->insert(array('tag_id'=>7,'snippet_id'=>$id,'created_at'=>'2014-10-04 11:05:56','updated_at'=>'2014-10-04 11:05:56'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Handle text input event in AngularJS','created_at'=>'2014-10-04 10:44:24','updated_at'=>'2014-10-04 10:44:24','content'=>'keyup event\n    &lt;input type=&quot;text&quot; ng-keyup=&quot;keyupEvent()&quot;&gt;\n    \n    snippetContollers.controller(&#39;SnippetListCtrl&#39;, [&#39;$scope&#39;,&#39;$http&#39;,function($scope, $http){\n        $scope.keyupEvent = function(){\n            if(event.keyCode == 13){ // enter key }\n        }\n    }]);\n\nGet input value\n    &lt;input type=&quot;text&quot; ng-model=&quot;search_keywords&quot;&gt;\n    \n    snippetContollers.controller(&#39;SnippetListCtrl&#39;, [&#39;$scope&#39;,&#39;$http&#39;,function($scope, $http){\n        $scope.keyupEvent = function(){\n            console.log($scope.search_keywords);\n        }\n    }]);\n'));
DB::table('snippet_tag')->insert(array('tag_id'=>7,'snippet_id'=>$id,'created_at'=>'2014-10-04 10:44:24','updated_at'=>'2014-10-04 10:44:24'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'prompt confirm dialog when closing window','created_at'=>'2014-09-13 15:36:11','updated_at'=>'2014-09-13 15:36:11','content'=>'    var handler = function (event) {\n      var message = &#39;この画面から離れるとデータが復帰できません。&#39;;\n      if (typeof event == &#39;undefined&#39;) {\n        event = window.event;\n      }\n      if (event) {\n        event.returnValue = message;\n      }\n      return message;\n    }\n    \n    $(&quot;input[type=&#39;submit&#39;]&quot;).click(function(){ window.onbeforeunload = null; });\n    $(&quot;:input&quot;).focus(function(){ window.onbeforeunload = handler; })'));
DB::table('snippet_tag')->insert(array('tag_id'=>7,'snippet_id'=>$id,'created_at'=>'2014-09-13 15:36:11','updated_at'=>'2014-09-13 15:36:11'));
DB::table('snippet_tag')->insert(array('tag_id'=>10,'snippet_id'=>$id,'created_at'=>'2014-09-13 15:36:11','updated_at'=>'2014-09-13 15:36:11'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Backup database by import and export','created_at'=>'2014-03-14 00:56:42','updated_at'=>'2014-09-10 18:28:49','content'=>'To export:\n     mysqldump -u mysql_user -p DATABASE_NAME &gt; backup.sql\n\nTo import:\n     mysql -u mysql_user -p DATABASE &lt; backup.sql\n\nMore detail at following link\nhttp://stackoverflow.com/a/7423680/1799322'));
DB::table('snippet_tag')->insert(array('tag_id'=>5,'snippet_id'=>$id,'created_at'=>'2014-03-14 00:56:42','updated_at'=>'2014-09-10 18:28:49'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'reset.css','created_at'=>'2014-01-03 17:01:51','updated_at'=>'2014-09-10 05:07:25','content'=>'    /* v2.0 | 20110126\n      http://meyerweb.com/eric/tools/css/reset/ \n      License: none (public domain)\n    */\n    html, body, div, span, applet, object, iframe,\n    h1, h2, h3, h4, h5, h6, p, blockquote, pre,\n    a, abbr, acronym, address, big, cite, code,\n    del, dfn, em, img, ins, kbd, q, s, samp,\n    small, strike, strong, sub, sup, tt, var,\n    b, u, i, center,\n    dl, dt, dd, ol, ul, li,\n    fieldset, form, label, legend,\n    table, caption, tbody, tfoot, thead, tr, th, td,\n    article, aside, canvas, details, embed, \n    figure, figcaption, footer, header, hgroup, \n    menu, nav, output, ruby, section, summary,\n    time, mark, audio, video {\n    	margin: 0;\n    	padding: 0;\n    	border: 0;\n    	font-size: 100%;\n    	font: inherit;\n    	vertical-align: baseline;\n    }\n    /* HTML5 display-role reset for older browsers */\n    article, aside, details, figcaption, figure, \n    footer, header, hgroup, menu, nav, section {\n    	display: block;\n    }\n    body {\n    	line-height: 1;\n    }\n    ol, ul {\n    	list-style: none;\n    }\n    blockquote, q {\n    	quotes: none;\n    }\n    blockquote:before, blockquote:after,\n    q:before, q:after {\n    	content: &#39;&#39;;\n    	content: none;\n    }\n    table {\n    	border-collapse: collapse;\n    	border-spacing: 0;\n    }\n    h1,h3,h4,h5,th{font-weight:bold;}\n    h1{padding:0;margin:20px 0 10px 0;text-align:left;}\n    h2{font-size:30px;font-weight:200;margin:20px 0 11px;padding:0;padding-bottom:10px;}\n    h1{font-size:36px;font-weight:200;}\n    h3{font-size:24px;font-weight:normal;}\n    h4{font-size:18px;}\n    h5{font-size:14px;font-weight:bold;margin-bottom:2px;}\n    h6{font-size:12px;font-weight:normal;}\n    p{margin:0 0 10px;}'));
DB::table('snippet_tag')->insert(array('tag_id'=>8,'snippet_id'=>$id,'created_at'=>'2014-01-03 17:01:51','updated_at'=>'2014-09-10 05:07:25'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Resized done action in javascript','created_at'=>'2014-09-10 04:00:45','updated_at'=>'2014-09-10 04:00:45','content'=>'resizedDoneAction is callback of resized action finished when there is no resize action within 0.1 seconds.\n\n    function resizedDoneAction(){\n        // resized finished action\n    }\n    \n    var doit;\n    window.onresize = function(){\n      clearTimeout(doit);\n      doit = setTimeout(resizedDoneAction, 100);\n    };'));
DB::table('snippet_tag')->insert(array('tag_id'=>7,'snippet_id'=>$id,'created_at'=>'2014-09-10 04:00:45','updated_at'=>'2014-09-10 04:00:45'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'get current time in string','created_at'=>'2014-09-08 20:21:38','updated_at'=>'2014-09-09 01:15:23','content'=>'Include time.h library before using code below\n\nCode\n    struct tm *ptr;\n    time_t lt;\n    char* str;\n    \n    str = (char*)malloc(sizeof(char)*20);\n    lt = time(NULL);\n    ptr = localtime(&amp;lt);\n    strftime(str, sizeof(char)*20, &quot;%m/%d %T&quot;, ptr);\n    printf(&quot;%s\n&quot;,str);'));
DB::table('snippet_tag')->insert(array('tag_id'=>4,'snippet_id'=>$id,'created_at'=>'2014-09-08 20:21:38','updated_at'=>'2014-09-09 01:15:23'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Migration file in Laravel','created_at'=>'2014-09-05 16:29:51','updated_at'=>'2014-09-05 16:29:51','content'=>'Alter or modify table\n    &lt;?php\n    \n    use Illuminate\Database\Schema\Blueprint;\n    use Illuminate\Database\Migrations\Migration;\n    \n    class UpdateAddColumnFilenameServicePlansTable extends Migration {\n    \n    	/**\n    	 * Run the migrations.\n    	 *\n    	 * @return void\n    	 */\n    	public function up()\n    	{\n    		//\n    		DB::statement(&#39;ALTER TABLE service_customers ADD download_username VARCHAR(255) DEFAULT NULL&#39;);\n    	}\n    \n    	/**\n    	 * Reverse the migrations.\n    	 *\n    	 * @return void\n    	 */\n    	public function down()\n    	{\n    		//\n    		DB::statement(&#39;ALTER TABLE service_customers DROP COLUMN download_username&#39;);\n    	}\n    \n    }\n\nCreate new table\n    &lt;?php\n    \n    use Illuminate\Database\Schema\Blueprint;\n    use Illuminate\Database\Migrations\Migration;\n    \n    class CreateServiceSerialsTable extends Migration {\n    \n    	/**\n    	 * Run the migrations.\n    	 *\n    	 * @return void\n    	 */\n    	public function up()\n    	{	\n    \n    		//\n    		Schema::create(&#39;service_serials&#39;,function(BluePrint $table)\n    		{\n    			$table-&gt;increments(&#39;id&#39;);\n    			$table-&gt;string(&#39;serial_code&#39;);\n    			$table-&gt;integer(&#39;service_plan_id&#39;)-&gt;unsigned();\n                $table-&gt;foreign(&#39;service_plan_id&#39;)-&gt;references(&#39;id&#39;)-&gt;on(&#39;service_plans&#39;)-&gt;onDelete(&#39;cascade&#39;);\n    			$table-&gt;integer(&#39;service_customer_id&#39;)-&gt;unsigned()-&gt;nullable();\n                $table-&gt;foreign(&#39;service_customer_id&#39;)-&gt;references(&#39;id&#39;)-&gt;on(&#39;service_customers&#39;)-&gt;onDelete(&#39;set null&#39;);\n                $table-&gt;timestamps();\n                \n    		});\n    	}\n    \n    	/**\n    	 * Reverse the migrations.\n    	 *\n    	 * @return void\n    	 */\n    	public function down()\n    	{\n    		//\n    		Schema::dropIfExists(&#39;service_serials&#39;);\n    	}\n    \n    }\n    \n'));
DB::table('snippet_tag')->insert(array('tag_id'=>3,'snippet_id'=>$id,'created_at'=>'2014-09-05 16:29:51','updated_at'=>'2014-09-05 16:29:51'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'URL in PHP','created_at'=>'2014-08-31 10:46:17','updated_at'=>'2014-08-31 10:46:17','content'=>'    &quot;//&quot;.$_SERVER[&quot;HTTP_HOST&quot;]$_SERVER[&quot;REQUEST_URI&quot;]\n'));
DB::table('snippet_tag')->insert(array('tag_id'=>3,'snippet_id'=>$id,'created_at'=>'2014-08-31 10:46:17','updated_at'=>'2014-08-31 10:46:17'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Regex on float/double','created_at'=>'2014-08-29 19:50:02','updated_at'=>'2014-08-29 19:50:02','content'=>'    ^[+-]?\d+\.\d+, ?[+-]?\d+\.\d+$'));
DB::table('snippet_tag')->insert(array('tag_id'=>7,'snippet_id'=>$id,'created_at'=>'2014-08-29 19:50:02','updated_at'=>'2014-08-29 19:50:02'));
DB::table('snippet_tag')->insert(array('tag_id'=>3,'snippet_id'=>$id,'created_at'=>'2014-08-29 19:50:02','updated_at'=>'2014-08-29 19:50:02'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Checking Katagana','created_at'=>'2014-07-05 16:47:21','updated_at'=>'2014-08-29 19:46:29','content'=>'Regex version\n    ^[ァ-ヶー]+$\n\nFull version\n    mb_regex_encoding(&quot;UTF-8&quot;);\n    if (preg_match(&quot;/^[ぁ-ん]+$/u&quot;, $string)) {\n        echo &quot;ひらがなのみ&quot;;\n    }\n    if (preg_match(&quot;/^[ァ-ヶー]+$/u&quot;, $string)) {\n        echo &quot;カタカナのみ&quot;;\n    }'));
DB::table('snippet_tag')->insert(array('tag_id'=>3,'snippet_id'=>$id,'created_at'=>'2014-07-05 16:47:21','updated_at'=>'2014-08-29 19:46:29'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'PDO configure database','created_at'=>'2014-08-28 15:20:28','updated_at'=>'2014-08-28 15:20:35','content'=>'These variables define the connection information for your MySQL database\n\n    $username = &quot;myusername&quot;;\n    $password = &quot;mypassword&quot;;\n    $host = &quot;localhost&quot;;\n    $dbname = &quot;mydatabase&quot;;\n    \n     try{\n    	$db = new PDO(&quot;mysql:host={$host};dbname={$dbname};charset=utf8&quot;, $username, $password);\n    }\n    catch(PDOException $ex)\n    {\n    	die(&quot;Failed to connect to the database: &quot; . $ex-&gt;getMessage());\n    }'));
DB::table('snippet_tag')->insert(array('tag_id'=>9,'snippet_id'=>$id,'created_at'=>'2014-08-28 15:20:28','updated_at'=>'2014-08-28 15:20:35'));
DB::table('snippet_tag')->insert(array('tag_id'=>5,'snippet_id'=>$id,'created_at'=>'2014-08-28 15:20:28','updated_at'=>'2014-08-28 15:20:35'));

$id = DB::table('snippet_table')->insertGetId(array('account_id'=>'1','title'=>'Subtraction of time by SQL','created_at'=>'2014-08-23 21:33:08','updated_at'=>'2014-08-23 21:33:08','content'=>'Get the records within 6 months. The &quot;180&quot; means 180 days\n     SELECT * FROM `mytable` WHERE DATEDIFF(NOW(),`created_at_col`) &lt; 180\n\n'));
DB::table('snippet_tag')->insert(array('tag_id'=>5,'snippet_id'=>$id,'created_at'=>'2014-08-23 21:33:08','updated_at'=>'2014-08-23 21:33:08'));


}

}
