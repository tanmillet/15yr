<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Server\Socket;
/**
 * Description of SokectModel
 *
 * @author 七彩P1
 */
class SokectModel {
    //put your code here
    public function sendMsg($data,$config="one"){
        $sokectConfig = config("socket.connect.one");
        $socket =new Socket($sokectConfig);
        
        $sendJson = json_encode($data);
        $len = strlen($sendJson);
        $head = str_pad($len,4,"0",STR_PAD_LEFT);//根sever协商发过去的前四个字节是字符串的长度
        $socket->sendRequest($head.$sendJson);
        return true;
    }
}
