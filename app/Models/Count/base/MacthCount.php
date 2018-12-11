<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;
use App\Models\Model;
/**
 * Description of MacthCount
 *
 * @author 七彩P1
 */
class MacthCount extends Model{
    public $table = "match_count";
    public $timestamps =FALSE;
    public $match_count_type_arr =[
                                    //"1"=>"一元话费","2"=>"五元话费","3"=>"三元话费","4"=>"二十元话费","5"=>"一百元话费",
                                    //"6"=>"五百元话费","7"=>"万元话费赛","8"=>"宾王卷福利赛","9"=>"神秘手机赛","10"=>"百万金币赛","11"=>"千万宾王券大奖赛",
                                   "12"=>"宾王赛","13"=>"中秋赛"
                                ];
    public $type_arr =[
                                 "12"=>["1"=>"宾王赛第一场","2"=>"宾王赛第二场","3"=>"宾王赛第三场"],
                                 "13"=>["1"=>"中秋赛第一场","2"=>"中秋赛第二场","3"=>"中秋赛第三场","4"=>"中秋赛第四场"],
                                ];
}
