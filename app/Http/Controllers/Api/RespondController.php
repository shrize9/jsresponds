<?php


namespace App\Http\Controllers\Api;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Response;
use \Illuminate\Http\Request;
use App\Models\Respond;
use App\Models\Asks;

/**
 * Description of RespondController
 *
 * @author p_kuzmin
 */
class RespondController extends \App\Http\Controllers\Controller{
    
    public function create(Request $request){
        $responseResult =new \App\Classes\ResponseResult();
        try{
            $body = json_decode($request->getContent(), true);

            if(strlen(trim($body["name"]))==0)
                return Response::json($responseResult->setError("name is empty")->toArray());            
            if(strlen($body["name"])>40)
                return Response::json($responseResult->setError("name is very long. Maximum 40 characters.")->toArray());            
            
            $respond=Respond::create(["name"=>$body["name"],"countQuestion"=>$body["countQuestion"]]);
            return Response::json($responseResult->setData(["token"=>$respond->id])->toArray());
        } catch (\Exception $err){            
            return Response::json($responseResult->setError($err->getMessage())->toArray());            
        }
    }
    
    public function get(Request $request){
        $respondId =$request->header("respondId", null);
                
        if($respondId !=NULL){
            return $this->load($request, $respondId);
        }else{
            return $this->empty($request);
        }
    }

    private function load(Request $request, String $respondId){
        $responseResult =new \App\Classes\ResponseResult();
        
        $respond = Respond::find($respondId);
        if(!$respond){
            return Response::json($responseResult->setError("not find Respond by " .$respondId)->toArray());
        }
        
        $answers = $respond->answers;     
        $responseResult->setData(["respond"=>$respond->toArray(), "countAnswers"=>$answers->count(), "totalAsks"=> Asks::count()]);
        
        return Response::json($responseResult->toArray());
    }
    
    private function empty(Request $request){
        $responseResult =new \App\Classes\ResponseResult();
        $responseResult->setData(
                [
                    "randomAsk"=> Asks::getRandomAsk()["question"], 
                    "avgResponseAsks"=>(int)(Respond::avgAsks()), 
                    "totalAsks"=> \App\Models\Asks::count()
                ]);
        return Response::json($responseResult->toArray());
    }

    
}
