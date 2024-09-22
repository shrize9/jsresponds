<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Classes;

use Illuminate\Support\Facades\Log;

/**
 * Description of BroadcastClient
 *
 * @author p_kuzmin
 */
class BroadcastClient {
    const SERVER_BROADCAST_URL ="http://host.docker.internal:9000";
    
    private static function sendMessage($text, $respondId=null){
        $url ="";
        if($respondId ==null)
         $url =BroadcastClient::SERVER_BROADCAST_URL . "/respond/";
        else
         $url =BroadcastClient::SERVER_BROADCAST_URL . "/respond/" .$respondId;
        
        $handle = curl_init($url);
        $params=[
            "message"=>$text
        ];
        
        Log::info('url BroadcastClient: ', [$url]);
        Log::info('body BroadcastClient: ', [$params]);
                
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $result = curl_exec($ch);
        Log::info('result BroadcastClient: ', [$result]);
        curl_close($ch);
        
    }
    
    public static function sendTextToAll($text){
        return self::sendMessage(["type"=>"notify", "text"=>$text], null);
    }
    
    public static function sendMessageToClient($messageBody, $respondId){
        return self::sendMessage($messageBody, $respondId);
    }
}
