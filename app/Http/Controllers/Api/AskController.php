<?php


namespace App\Http\Controllers\Api;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Response;
use App\Models\Asks;
use App\Models\Answer;
use App\Models\Respond;


/**
 * Description of RespondController
 *
 * @author p_kuzmin
 */
class AskController extends \App\Http\Controllers\Controller{
    
    public function list(Request $request){                
        return Response::json(["error"=>false, "data"=>\App\Models\Asks::all()]);
    }

    public function load(Request $request, String $askId){                
        return Response::json(["error"=>false, "data"=>\App\Models\Asks::load($askId)]);
    }
    
    public function getNext(Request $request, String $respondId){         
        $responseResult =new \App\Classes\ResponseResult();
        $respond = Respond::find($respondId);                
        $excludeAsks =Answer::askIdsForRespond($respondId);
        
        if(!$respond)
            return Response::json($responseResult->setError("respond not found with " .$respondId)->toArray());
        
        if($respond->countQuestion <= sizeof($excludeAsks)){
            return Response::json($responseResult->setError("respond maximum answers count")->toArray());
        }
        return Response::json($responseResult->setData(\App\Models\Asks::getRandomAsk())->toArray());
    }
   
}
