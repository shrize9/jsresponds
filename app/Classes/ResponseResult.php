<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Classes;
/**
 * Description of ResponseResult
 *
 * @author p_kuzmin
 */
class ResponseResult {
    private $result;
    
    public function __construct() {
     $this->result =["error"=>true];   
    }
    
    public function setData($data){
        $this->result["error"]=false;
        unset($this->result["message"]);
        $this->result["data"]=$data;
        
        return $this;
    }
    
    public function setOk(){
        $this->result["error"]=false;
        unset($this->result["message"]);
        unset($this->result["data"]);
        
        return $this;
    }

    public function setError($message) {
        $this->result["error"]=true;
        unset($this->result["data"]);
        $this->result["message"]=$message;
        
        return $this;
    }
    
    public function toArray() {
        return $this->result;
    }

}
