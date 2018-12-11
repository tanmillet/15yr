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
class ServiceChargeLog extends Model {
    public $table="service_charge_log";
    public  $connection="mysql_three";
    public function __construct() {
       // $this->table =  "coinlog".$date;
    }
}
