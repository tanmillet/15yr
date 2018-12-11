<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Models\Game;
use App\Models\Game\base\userGame;
use App\Models\Game\base\userInfo;
/**
 * Description of GameUserModel
 *
 * @author 七彩P1
 */
class GameUserModel {
    /*
     * 获取用户信息
     */
    public function getUserInfo($uid){
        $uTable = (new userInfo)->getTable();
        $users = userInfo::query();
        $ugTable = (new userGame)->getTable();
        $users->leftjoin($ugTable ,$uTable .".uid","=",$ugTable.".uid");
        $users->where($ugTable .".uid",$uid);

        return $users->first();
    }
}
