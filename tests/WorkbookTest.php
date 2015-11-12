<?php

use App\Model\Workbook;

class WorkbookTest extends TestCase {

    const API_V1_PREFIX = "/api/v1";

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCreateWithoutSession()
	{
        $countBeforeAdded = Workbook::count();

        $url = self::API_V1_PREFIX.'/workbook';
        $response = $this->call('POST', $url, ['title' => 'thisistest']);

        $countAfterAdded = Workbook::count();

        $this->assertEquals($countBeforeAdded, $countAfterAdded);
        $this->assertNotEquals(200, $response->status());
	}

    public function testPushSnippetWithoutSession()
    {
        $countBeforeAdded = DB::table("snippet_tag")->count();

        $workbookId = 2;
        $snippetId = 15;
        $requestForm = ["snippetId" => $snippetId, "action" => "push"];
        $response = $this->call('PUT', self::API_V1_PREFIX."/workbook/".$workbookId, $requestForm);

        $countAfterAdded = DB::table("snippet_tag")->count();

        $this->assertEquals($countBeforeAdded, $countAfterAdded);
        $this->assertNotEquals(200, $response->status());
    }

    public function testSliceSnippetWithoutSession()
    {

    }

    public function testSliceSnippetWithSession()
    {

        $countBeforeAdded = Workbook::count();

        $url = self::API_V1_PREFIX.'/workbook';
        $response = $this->call('POST', $url, ['title' => 'thisistest']);

        $countAfterAdded = Workbook::count();

        $this->assertEquals($countBeforeAdded + 1, $countAfterAdded);
        $this->assertEquals(200, $response->status());


    }

}
