<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Respond;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;



class RespondTopResultUpdate implements ShouldQueue
{
    use Queueable,InteractsWithQueue,SerializesModels;

    private $respondId;
    /**
     * Create a new job instance.
     */
    public function __construct($_respondId)
    {
        $this->respondId=$_respondId;
        Log::info("job with id " .$this->respondId);        
    }

    private function getTopPlace(){
        return DB::select("SELECT id, rank FROM( " .
                    "SELECT id, totalCorrectedAnswer, dense_rank() over (ORDER BY totalCorrectedAnswer DESC) as rank ".
                    "FROM responds ".
                    "WHERE finished=1 AND NOT totalCorrectedAnswer IS NULL) ".
                    "WHERE id=?"
                    ,[$this->respondId]);

    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $respond = Respond::find($this->respondId);
            if($respond){
                \App\Classes\BroadcastClient::sendTextToAll( $respond->name .", закончил отвечать");
                sleep(6);

                $totalCorrectedAnswer=0;
                foreach ($respond->answers as $answer){
                    if($answer->answer == $answer->correctAnswer)
                        $totalCorrectedAnswer +=1;
                }

                $respond->finished =1;
                $respond->totalCorrectedAnswer =$totalCorrectedAnswer;
                $respond->save();

                $resultTop =$this->getTopPlace();
                if(count($resultTop)>0){
                    \App\Classes\BroadcastClient::sendMessageToClient(["type"=>"topPlace", "rank"=>$resultTop[0]->rank], $this->respondId);
                }
            }else{
                Log::info("respond with id " .$this->respondId ." not found");
            }
        } catch (\Exception $err){
            Log::alert("Error " .$err->getMessage(), [$err]);
        }        
    }
}
