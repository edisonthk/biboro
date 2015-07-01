<?php namespace App\Edisonthk;

use DateTime;

class ScoreService {

    const TITLE_FULL_MATCH_SCORE = 4;
    const TITLE_ANY_MATCH_SCORE = 0.01;
    const BODY_FULL_MATCH_SCORE = 0.5;
    const BODY_ANY_MATCH_SCORE = 0.001;
    const PENALTY_FOR_OLDEST = 0.01;
    const TAG_SCORE = 2;

    public function calcScore($snippet, $kws) {
        // search from keywords database

        // 
        $title = $snippet->title;
        $body = $snippet->content;

        $snippet->load('tags');

        $score = 0;

        $parsedKeywords = [];
        $parsedKeywords = array_merge($parsedKeywords, $this->extractAlphabetWordsFromJapaneseContent($kws)["words"]);

        // tokenize japanese words
        if(count($parsedKeywords) == 0) {
            $parsedKeywords[] = $kws;
        }else{
            $pattern = "/";
            foreach ($parsedKeywords as $parsedKeyword) {
                $pattern .= $this->escapeRegularExpression($parsedKeyword)."|";
            }
            $pattern = substr($pattern, 0 , -1);
            $pattern .= "/u";
            
            foreach(preg_split($pattern, $kws) as $word) {
                if(strlen($word) > 0) {
                    $parsedKeywords[] = $word;    
                }
                
            }    
        }
        

        foreach ($parsedKeywords as $kw) {
            
            if(strlen($kw) > 1) {
                $score += $this->getContentScore($body, $kw);
            }

            $score += $this->getTitleScore($title, $kw);

            foreach ($snippet->tags as $tag) {
                if(strtolower($tag->name) == strtolower($kw)) {
                    $score += self::TAG_SCORE;
                }
            }

            if($score <= 0) {
                break;
            }

            // if oldest, more penalty on score
            $d1 = new DateTime("now");
            $d2 = new DateTime($snippet->updated_at);
            $diff=$d2->diff($d1);

            $day = intval($diff->format('%d'));
            $score -= $day * self::PENALTY_FOR_OLDEST;
        }
        return $score;
    }

    public function escapeRegularExpression($words) {
        return preg_replace_callback("/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/", function($matches) {
            return "\\".$matches[0];
        }, $words);
    }

    public function getTitleScore($title, $kw) {

        $title = strtolower($title);
        $kw = strtolower($kw);

        $result = $this->extractAlphabetWordsFromJapaneseContent($title);
        $alphWords = $result["words"];
        $jpnWords = $result["extracted"];

        // calc score by alphabet
        $score = 0;
        foreach ($alphWords as $word) {
            if($word === $kw) {
                $score += self::TITLE_FULL_MATCH_SCORE;
            }else if(strpos($word, $kw) !== false || strpos($kw, $word) !== false) {
                $score += self::TITLE_ANY_MATCH_SCORE;
            }
        }

        // calc score by japanese
        if(stripos($jpnWords, $kw) !== false) {
            $score += self::TITLE_FULL_MATCH_SCORE;
        }

        return $score;
    }

    public function getContentScore($content, $kw) {
        


        // extract code
        $result    = $this->extractCode($content);
        $extracted = $result["extracted"];
        $code      = $result["code"];

        $result    = $this->extractImagesFromJapaneseContent($extracted);
        $extracted = $result["extracted"];
        $images    = $result["images"];

        
        $result    = $this->extractLinksFromJapaneseContent($extracted);
        $extracted = $result["extracted"];
        $links     = $result["links"];

        $extracted = $this->filterSystemCharacter($extracted);

        $result    = $this->extractAlphabetWordsFromJapaneseContent($extracted);
        $alphWords = $result["words"];
        $extracted = $result["extracted"];

        $words = [];
        $words = array_merge($words, $this->tokenizeStringFromCode($code));
        $words = array_merge($words, $alphWords);

        $score = 0;
        foreach ($words as $word) {
            if($word === $kw) {
                $score += self::BODY_FULL_MATCH_SCORE;
            }else if(strpos($word, $kw) !== false || strpos($kw, $word) !== false) {
                $score += self::BODY_ANY_MATCH_SCORE;
            }
        }
        
        return $score;
    }

    public function tokenizeJapanseContent($content) {

    }

    public function filterSystemCharacter($content) {
        return str_replace("\n", "", $content);
    }

    public function extractAlphabetWordsFromJapaneseContent($content) {

        $words = [];
        $extracted = preg_replace_callback("/[a-zA-Z]+/", function($matches) use (&$words) {
            $wordsSplitBySpace = preg_split('/\s+/', $matches[0]);
            foreach ($wordsSplitBySpace as $word) {
                if(!empty($word)) {
                    $words[] = $word;        
                }
            }
            
            return "";
        },$content);

        return [
            "words"     => $this->filterEmptyAndDuplicateWordsFromArray($words),
            "extracted" => $extracted,
        ];
    }

    public function extractImagesFromJapaneseContent($content) {
        
        $images = [];

        $extracted = preg_replace_callback('/!\[(.*?)\]\((https?:\/\/(.*?))\)/', function($matches) use(&$images) {
            $images[] = $matches[2];
            return "";
        }, $content);
        
        return [
            "images" => $images,
            "extracted" => $extracted,
        ];
    }

    public function extractLinksFromJapaneseContent($content) {

        $links = [];

        $extracted = preg_replace_callback('/\[(.*?)\]\((https?:\/\/(.*?))\)/', function($matches) use(&$links) {
            $links[] = $matches[2];
            return "";
        }, $content);
        
        return [
            "links" => $links,
            "extracted" => $extracted,
        ];

    }

    public function tokenizeStringFromCode($code) {
        $words = preg_split("/[^a-zA-Z]/", $code);

        // filter empty and duplicate words
        return $this->filterEmptyAndDuplicateWordsFromArray($words);
    }

    public function filterEmptyAndDuplicateWordsFromArray($arr) {
        $duplicateCheck = [];
        foreach ($arr as $index => $value) {
            if(empty($value) || in_array(strtolower($value), $duplicateCheck)) {
                unset($arr[$index]);
            }else{
                $duplicateCheck[] = strtolower($value);
            }
        }
        return $arr;
    }

    public function extractCode($content) {

        $nextLineKey = "___NEXT_LINE___";

        $pattern = '/    (.*?)(\\n|$)/';
        if(strpos($content, '```') !== false){
            $content = str_replace("\n", $nextLineKey, $content);
            $pattern = '/```(.*?)```/';
        }
        $code = "";
        $content = preg_replace_callback($pattern,function($matches) use(&$code){
            $code .= $matches[1]." ";
            return "";
        },$content);

        $content = str_replace($nextLineKey, "\n", $content);

        return [
            "code"  => $code,
            "extracted" => $content,
        ];  
    }
}