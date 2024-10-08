<?php


namespace App\Http\Controllers\Api;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Response;
use App\Models\Asks;
use App\Models\Answer;
use App\Models\Respond;

/**
 * Description of RespondController
 *
 * @author p_kuzmin
 */
class AnswerController extends \App\Http\Controllers\Controller{
    
    public function create(Request $request){
        $responseResult =new \App\Classes\ResponseResult();
        try{
            $body = json_decode($request->getContent(), true);
            
            $respondId =$body["respondId"];
            $ask       =Asks::load($body["askId"]);
            $answerIndex    =$body["answer"];
            $needanswerCorrect=$body["answerCorrect"] ?? false;
            
            $answer =Answer::updateOrCreate([
                    "respondId"=>$respondId, 
                    "askId"=>$ask["id"]
                ],
                [
                    "correctAnswer"=>$ask["correctAnswerIndex"], 
                    "answer"=>$answerIndex
                ]
            );
            
            $respond = Respond::find($respondId);                
            $excludeAsks =Answer::askIdsForRespond($respondId);
            if($respond->countQuestion <= sizeof($excludeAsks)){
                dispatch(new \App\Jobs\RespondTopResultUpdate($respondId));    
                \App\Classes\BroadcastClient::sendTextToAll($respond->name . " закончил тест.");
            }
            
            return Response::json($responseResult->setData(["answer"=>$answer->toArray()])->toArray());
        } catch (\Exception $err){  
            Log::alert($err->getTraceAsString());
            return Response::json($responseResult->setError($err->getMessage())->toArray());            
        }
    }

}
