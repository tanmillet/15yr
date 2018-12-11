<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\MacthCount;
use App\Models\Game\base\MacthRoomCfg;
use App\Models\Game\base\UserMatchLog;
use Illuminate\Support\Facades\DB;
/**
 * Description of MatchCountModel
 *
 * @author 七彩P1
 */
class MatchCountModel extends MacthCount{
    //put your code here
    public function arstianCount(){
        $mrc = new MacthRoomCfg();
        $matchcount = new MacthCount;
        $userMatchLog = (new UserMatchLog);
        $date = date("Y-m-d", strtotime("-1day"));
        foreach (config("game.game") as $game_data) {
            $game = $game_data['value'];
            $dataObj = $userMatchLog->where("game",$game)->where("created_at",">=",strtotime($date." 00:00:00"))
                    ->where("created_at","<=",strtotime($date." 23:59:59"))
                    ->groupBy("mrf_id")->select(DB::raw("count( DISTINCT uid ) as pop_num,count(uid) as num,mrf_id"))->get();
            $data = $dataObj->toArray();
            foreach($data as $value_data){
                $match_count_type = $mrc->where("id",$value_data['mrf_id'])->pluck("count_type")->first();
                $type_arr = $mrc->where("count_type",$match_count_type)->orderBy("id","asc")->pluck("count_type","id");
                $type_arr = $type_arr->toArray();
                $insert["date"] = $date;
                $insert["pop_num"] = $value_data['pop_num'];
                $insert["num"] = $value_data['num'];
                $insert["match_count_type"] = $match_count_type;
                $type = array_search($value_data['mrf_id'], array_keys($type_arr)) ;
                $insert["type"] = $type+1;
                $insert["game"] = $game;
                $matchcount->insert($insert);
            }
        }
    }
}
