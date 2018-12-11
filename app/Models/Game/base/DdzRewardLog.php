<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use Illuminate\Database\Eloquent\Model;
/**
 * Description of DdzRewardLog
 *
 * @author 七彩P1
 */
class DdzRewardLog extends Model{
    //put your code here
    public $table="ddz_reward_log";
    public  $connection="mysql_two";
}
