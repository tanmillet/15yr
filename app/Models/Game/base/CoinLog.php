<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of CoinLog
 *
 * @author 七彩P1
 */
class CoinLog extends Model {
    public $table="coinlog";
    public  $connection="mysql_three";
    public function __construct() {
       // $this->table =  "coinlog".$date;
    }
}
