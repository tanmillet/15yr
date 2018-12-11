<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use Illuminate\Database\Eloquent\Model;
/**
 * Description of UserMatchLog
 *
 * @author 七彩P1
 */
class UserMatch extends Model {
    //put your code here
    public  $table="user_match";
    public  $connection="mysql_two";
}
