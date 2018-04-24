<?php 
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function getIPAddress(){
    if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){
        if($_SERVER['SERVER_PORT'] == 80){
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        }else{
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
        }
    }else{
        return false;
    }
}

function getIP(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}