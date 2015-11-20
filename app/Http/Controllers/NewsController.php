<?php

namespace Biboro\Http\Controllers;

use Illuminate\Http\Request;

use Biboro\Http\Requests;
use Biboro\Http\Controllers\Controller;

use Biboro\Edisonthk\Exception\MissingLoginedUserInfo;
use Biboro\Edisonthk\Exception\NotAllowedToEdit;
use Biboro\Edisonthk\Exception\SnippetFoundInWorkbook;

class NewsController extends Controller
{
    private $news;
    private $snippet;
    private $pagination;

    public function __construct(
        \Biboro\Edisonthk\NewsService $news,
        \Biboro\Edisonthk\SnippetService $snippet,
        \Biboro\Edisonthk\PaginationService $pagination 
    ) {
        $this->pagination = $pagination;
        $this->snippet = $snippet;
        $this->news = $news;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $newsQuery = $this->news->getQuery();

        $pagination = $this->pagination->makeWithQuery($newsQuery, $request);

        $news = $pagination["data"];

        $this->news->tidy($news);

        unset($pagination["data"]);

        return response()->json([
                "pagination" => $pagination,
                "news" => $news,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        // $profile = $this->profile->getByAccountId($id);

        return response()->json();
    }
}
