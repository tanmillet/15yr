<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use Illuminate\Support\Facades\DB;
use App\Models\Count\base\BwsCount;
use App\Models\Game\base\UserGetGoodsLog;
use App\Models\Game\base\MatchTicketLog;
use App\Models\Game\base\MacthRoomCfg;
/**
 * Description of BwsCount
 *
 * @author 七彩P1
 */
class BwsCountModel {
    public function arstianCount(){
        $date = date("Y-m-d", strtotime("-1day"));
        //宾王赛门票
        $goods_id = 162;
        $game = 2;
        $uggl = new UserGetGoodsLog();
        $BwsCount = new BwsCount();
        $obj = $uggl->where("game",$game)->where("goods_id",$goods_id)->select(DB::raw("sum(get_num) as num,ref,search_ref,search_ref_sid,game"))
        ->groupBy("game")->groupBy("ref")->groupBy("search_ref")->groupBy("search_ref_sid")->where("created_at",">=",$date." 00:00:00")->where("created_at","<",$date." 23:59:59")->get();
        $arr  =  $obj->toArray();
        //["1"=>"server操作","2"=>"","3"=>"订单使用","4"=>"定时使用道具","5"=>"活动来源","6"=>"任务使用","7"=>"抽奖使用","8"=>"用户在背包中使用","9"=>"充值赠送"];
        
        //1绑定2邀请3 10局数4 20局数5 充值 6购买 7  -- 1场  8 -2场 9---3场
        foreach($arr as $data){
            if($data['ref'] ==3){//购买
                $ref = 6;
            }elseif ($data['ref']==5 && $data["search_ref"] == 13 && $data["search_ref_sid"] == 1) {//1绑定
                $ref = 1;
            }elseif ($data['ref']==5 && $data["search_ref"] == 13  && $data["search_ref_sid"] == 2) {//邀请
                $ref = 2;
            }elseif ($data['ref']==5 && $data["search_ref"] == 13  && $data["search_ref_sid"] == 3) {//10局数
                $ref = 3;
            }elseif ($data['ref']==5 && $data["search_ref"] == 13  && $data["search_ref_sid"] == 4) {//20局数
                $ref = 4;
            }elseif ($data['ref']==9 ) {//充值赠送
                $ref = 5;
            }else{//其他
                $ref = 10;
            }
            $insert["ref"]= $ref;
            $insert["num"]= $data["num"];
            $insert["type"]= 1;
             $insert["game"]= $data['game'];
            $insert["date"]= date("Y-m-d",strtotime($date));
            $BwsCount->insert($insert);
        }
        
        
        $mtl = new MatchTicketLog();
         $obj1 = $mtl->where("game",$game)->where("ticket_type",7)->select(DB::raw("sum( case when is_used=0 then 0 - count else count end) as num,times,mrc_id,game"))
        ->groupBy("game")->groupBy("mrc_id")->where("times",">=", strtotime($date." 00:00:00"))->where("times","<",strtotime($date." 23:59:59"))->get();
        $arr  =  $obj1->toArray();
        
        
        foreach($arr as $data){
            if($data['mrc_id'] ==12){//购买
                $ref = 7;
            }elseif ($data['mrc_id'] ==13) {//1绑定
                $ref = 8;
            }else{//其他
                $ref = 9;
            }
            $insert["ref"]= $ref;
            $insert["num"]= $data["num"];
            $insert["type"]= 2;
            $insert["date"]= date("Y-m-d",$data['times']);
            $insert["game"]= $data['game'];
            $BwsCount->insert($insert);
        }
    }
    
        

}
