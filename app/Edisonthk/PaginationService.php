<?php namespace Biboro\Edisonthk;

use Illuminate\Http\Request;

class PaginationService {
    
    public function __construct()
    {

    }

    public function make(Request $request)
    {
        return [
            "skip" => $request->get("skip", 0),
            "take" => 25
        ];
    }

    public function makeAsEager(Request $request) 
    {

        $pagination = $this->make($request);

        return function($query) use($pagination) {
            $query->skip($pagination["skip"])->take($pagination["take"]);
        };
    }

    public function makeWithQuery($query, Request $request)
    {
        $pagination = $this->make($request);

        $model = $query->skip($pagination["skip"])->take($pagination["take"]);
        $pagination["count"] = $model->count();
        $pagination["data"]  = $model->get();
            

        return $pagination;
    }

}