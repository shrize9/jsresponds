<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * Description of Asks
 *
 * @author p_kuzmin
 */
class Asks {
    
    public static function all(){
        return Cache::get('asks', function () {
            return json_decode(file_get_contents(storage_path('asks.json')), true);
        });        
    }
    
    public static function count(){
        return sizeof(self::all());        
    }

    public static function getRandomAsk($excludeAsks=null){
        $asks =self::all();
        if($excludeAsks){
            $asks = array_filter($asks, fn($ask)=>!in_array($excludeAsks,$ask["id"]));
        }
        return $asks[random_int(0, sizeof($asks)-1)];        
    }

    public static function load($askId){
        $asks = array_filter(self::all(), fn($ask)=>$ask["id"] == $askId);
        if(sizeof($asks)>=1){
            return array_pop($asks);        
        }else{
            return null;        
        }
    }
    
}
