<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of userInfo
 *
 * @author 七彩P1
 */
class userInfo extends Model{
    public $table="userinfo";
    public  $connection="mysql_two";
     //机器人ID低于200000都是机器人
    public $aiUserId = 5000000;
}
