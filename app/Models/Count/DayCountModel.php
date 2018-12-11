<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\DayCount;
use App\Models\Game\base\UserDayLog;
use Illuminate\Support\Facades\DB;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\Order;
/**
 * Description of DayCountModel
 *
 * @author 七彩P1
 */
class DayCountModel extends DayCount{
    //put your code here
    //获取用户活跃用户数
    public function getActiveUserCount($game,$sdate,$fdate){
        $userDayLog = new UserDayLog();
        $baseUserInfo = new userInfo();
        $userDayLogObj = $userDayLog->where("game",$game)->where("date",">=",$sdate)->where("date","<",$fdate)->select(DB::raw("count(DISTINCT uid) as use_active,date,game"))
                            ->where("uid",">",$baseUserInfo->aiUserId)
                            ->first();
        if($userDayLogObj){
            return $userDayLogObj->toArray();
        }
        return array();
    }
    
    public function arstianCount(){
        $date = date("Ymd", strtotime("-1day"));
        $sdate = date("Y-m-d H:i:s", strtotime("-1day"));
        $fdate = date("Y-m-d H:i:s");
        $userDayLog = new UserDayLog();
        $baseUserInfo = new userInfo();
        $baseOrder = new Order();
        foreach(config("game.game") as $vgame){
            $game = $vgame['value'];
            $userDayLogObj = $userDayLog->where("game",$game)->where("date",$date)->select(DB::raw(" sum(if(is_new_user,1,0)) as register_pop,sum(if(is_new_user,0,1)) as active_pop"))
                            ->where("uid",">",$baseUserInfo->aiUserId)
                            ->first();
         
            $where = array();
            $where["order_status"] =$baseOrder->order_status_arr['finish'];
            $where["game"] = $game;
            $payCount =$baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)->whereIn("obuy_type",$baseOrder->pay_buy_ref)
                     ->select(DB::raw(""
                            . "count( DISTINCT(case when  order_status= {$baseOrder->order_status_arr['finish']} and is_old_pay=0 then uid  end) ) as new_pay_pop_num,"
                            . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                            ))
                    ->first();
            $payCount = $payCount->toArray();
            if(!empty($payCount)){
                $insert["register_pop"] = $userDayLogObj->register_pop?$userDayLogObj->register_pop:0;
                $insert["active_pop"] = $userDayLogObj->active_pop?$userDayLogObj->active_pop:0;
                $insert["game"] = $game;
                $insert["date"] = date("Y-m-d", strtotime($date));
                $insert["new_pay_pop"] = isset($payCount['new_pay_pop_num'])?$payCount['new_pay_pop_num']:0;
                $insert["pay_pop"] = isset($payCount['pay_pop_num']) ?$payCount['pay_pop_num']:0;
                $this->insert($insert);
            }
        }
    }
}
