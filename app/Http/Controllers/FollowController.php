<?php

namespace Biboro\Http\Controllers;

use Illuminate\Http\Request;

use Biboro\Http\Requests;
use Biboro\Http\Controllers\Controller;
use Biboro\Edisonthk\Exception;

class FollowController extends Controller
{
    private $follow;

    public function __construct( \Biboro\Edisonthk\FollowService $follow ) {

        $this->follow = $follow;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json($this->follow->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function follow(Request $request)
    {
    
        $type   = $request->get("type");        
        $target = $request->get("target");

        try {
            $this->follow->follow($type, $target);
        } catch(Exception\UserNotFound $e) {
            return response()->json("user not found",404);
        } catch(Exception\TagNotFound $e) {
            return response()->json("tag not found",404);
        } catch(Exception\WorkbookNotFound $e) {
            return response()->json("workbook not found",404);
        } catch(Exception\AlreadyFollowed $e) {
            return response()->json("already followed",200);
        } catch(Exception\UnknownFollowType $e) {
            return response()->json($e->getMessage(), 400);
        }
        
        return response()->json("followed", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function unfollow(Request $request)
    {
        //
        $type   = $request->get("type");        
        $target = $request->get("target");

        $this->follow->unfollow($type, $target);

        return response()->json("unfollowed", 200);
    }
}
