<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OnlineCount
 *
 * @author 七彩P1
 */
namespace App\Models\Count\base;
use App\Models\Model;

class OnlineCount extends Model{
    //put your code here
    public $table = "onlinecount";
    protected $fillable  = [//创建用户时候可以修改添加的字段
        "game",
        "pfid",
        "usid" ,
        "date" ,
        "hour" ,
        "min",
        "user_num" ,
        "time_num",
    ];
    public $timestamps =FALSE;
}
