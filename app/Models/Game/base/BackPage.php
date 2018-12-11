<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of BackPage
 *
 * @author 七彩P1
 */
class BackPage extends Model {
    public $table="backpack";
    public  $connection="mysql_two";
    public $bp_type=["1"=>"游戏道具","2"=>"实物道具"];
    protected $fillable =["uid","obgoods_id","bpg_num","bpg_use_at","bpg_over_at"];
}
