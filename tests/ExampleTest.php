<?php

use Faker\Provider\Base as FakerBase;

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		// Mockery::mock('App\Model\Snippet');

        // $accounts = factory(App\Model\Account::class)->times(10)->create();
        // $tags = ["server","AngularJS-watch","range-base_for","JavaScript-canvas","Chrome-App","シリアル通信","React.js","タイマー","端末","Terminal","画像処理","AngularJS-http","express.js","node.js","AngularJS-resources","Android-ListView","Terminal","CSRF","XSRF","JSON"];



        // $snippets = [];
        // foreach ($accounts as $account) {
        //     factory(App\Model\Snippet::class)->times(3)->create([
        //         "account_id" => $account->id,
        //     ])->each(function($snippet) use($tags) {

        //         $snippetTags = [];
        //         foreach(range(0,rand(1,9)) as $index) {
        //             $snippetTags[] = FakerBase::randomElement($tags);
        //         }

        //         echo count($snippetTags)."\n";

        //         $snippet->tagsave($snippetTags);
        //     });
        // }
        

        foreach (App\Model\Snippet::all() as $snippet) {
            echo $snippet->id . " " . $snippet->getCreatorName(). " " . $snippet->tags()->getResults()->count(). "\n";
        }

		$this->assertEquals(1, 1);
	}

}
