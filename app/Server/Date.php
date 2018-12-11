<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;

/**
 * Description of Date
 *
 * @author 七彩P1
 */
class Date {
    /*
     * 获取当天剩余时间返回秒
     */
    public static  function remainingTime(){
        $time = time();
        return (strtotime(date("Y-m-d"))+24*3600)-$time;
    }
}
