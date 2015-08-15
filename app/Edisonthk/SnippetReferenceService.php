<?php namespace App\Edisonthk;

use App\Model\Snippet;
use App\Model\SnippetReference;

class SnippetReferenceService {

    const REFERENCE_BIBORO  = 1;
    const REFERENCE_WEBSITE = 2;
    
    public function referenceFromWebsite(Snippet $snippet, $url) 
    {
        
        $ref = $this->getBySnippet($snippet);
        if(is_null($ref)) {
            $ref = new SnippetReference;
            $ref->snippet_id = $snippet->id;
        }
        
        $ref->method = self::REFERENCE_WEBSITE;
        $ref->target = $url;
        $ref->author = "";
        $ref->save();
        return $ref;

    }

    public function referenceFromBiboro(Snippet $snippet,Snippet $refSnippet) 
    {
        
        $ref = $this->getBySnippet($snippet);
        if(is_null($ref)) {
            $ref = new SnippetReference;
            $ref->snippet_id = $snippet->id;
        }
        
        $ref->method = self::REFERENCE_BIBORO;
        $ref->target = $refSnippet->id;
        $ref->author = "";
        $ref->save();
        return $ref;

    }

    public function getBySnippet(Snippet $snippet)
    {
        return SnippetReference::where("snippet_id","=",$snippet->id)->first();
    }


}