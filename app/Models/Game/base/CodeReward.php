<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;

use App\Models\Model;

/**
 * Description of CodeReward
 *
 * @author 七彩P1
 */
class CodeReward extends Model {

    public $table = "code_reward";
    public $connection = "mysql_two";
    public $type_arr = [1 => "新手红包码"];

}
