<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasUuids;

    protected $table='answers';
    protected $primaryKey='id';
    public $incrementing = false;
    public $timestamps = false;    
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'respondId',
        'askId',
        'answer',
        'correctAnswer'
    ];
    
    protected $appends=[
        'totalAnswerIndex',
        'totalCorrectAnswer',
        'totalAnswers'
    ];
    
    protected $hidden = ['respondId'];


    public function getTotalAnswerIndexAttribute(){
        return Answer::where('askId', $this->askId)->where('answer', $this->answer)->count();
    }
    
    public function getTotalCorrectAnswerAttribute(){
        return Answer::where('askId', $this->askId)->where('answer', $this->correctAnswer)->count();
    }
    public function getTotalAnswersAttribute(){
        return Answer::where('askId', $this->askId)->count();
    }

    public static function countForRespond($respondId){
        return Answer::where('respondId', $respondId)->count();
    }

    public static function askIdsForRespond($respondId){
        return Answer::where('respondId', $respondId)->select('askId')->pluck('askId')->toArray();
    }
    
}
