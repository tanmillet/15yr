<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of Notice
 *
 * @author 七彩P1
 */
class Notice extends Model{
    public  $connection="mysql_two";
    public $timestamps = true;
    public $table="notice";
}
