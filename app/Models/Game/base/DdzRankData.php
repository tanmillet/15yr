<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use Illuminate\Database\Eloquent\Model;
/**
 * Description of DdzRankData
 *
 * @author 七彩P1
 */
class DdzRankData  extends Model{
    //put your code here
    public $table ="ddz_rank_data";
    public  $connection="mysql_two";
    public $timestamps= FALSE;
}
