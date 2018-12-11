<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\BrnnMoneyCount;
use App\Models\Game\base\CoinLog;
use App\Models\Game\base\userInfo;
use \Illuminate\Support\Facades\DB;
use App\Models\Game\base\ServiceChargeLog;
use App\Models\Game\base\RobotCoinLog;
/**
 * Description of MoneyCountInfoModel
 *
 * @author 七彩P1
 */
class BrnnMoneyCountModel {
    //put your code here
    public function arstianCount(){
        $date_table = date("Ymd", strtotime("-1hour"));//隔天问题
        $date= date("Ymd");
        $hour_min = date("Hi");
        if($hour_min=="0000"){//当他是零界点那就需要他归到昨天
            $hour_min="2400";
            $date = $date_table;
        }
        $stime = strtotime("-1hour");
        $ftime = time();
        $userInfo = new userInfo();
        $coinLog  = new CoinLog();
        $brnnMoneyCount = new BrnnMoneyCount();
        $uTable = $userInfo->getTable();

        $coinLog1 = $coinLog->setTable($coinLog->table.$date_table);
        $cTable = $coinLog1->getTable();
        
        $baseScl = new ServiceChargeLog;
        $baseScl = $baseScl->setTable($baseScl->table.$date_table);
        
        $baseRcl = new RobotCoinLog;
        $baseRcl = $baseRcl->setTable($baseRcl->table.$date_table);
        
        //$cTable = $cTable.$date;
      //  dd(config("database.connections.".$userInfo->getConnectionName()));
        $udb = config("database.connections.".$userInfo->getConnectionName());
        //coin_log没有  机器人记录
        $connection = DB::connection($coinLog->connection);
        $coinLogCountObj = $connection->table($cTable)->leftJoin($udb['database']."." .$uTable ,$cTable.".uid","=",$uTable.".uid")
                            ->select(DB::raw($uTable.".game,count(DISTINCT ".$cTable.".uid) as xiazhu_pop,sum(changecoin) as money"))->where($cTable.".code",6)->where($cTable.".gametype",2)->where($cTable.".uid",">=",$userInfo->aiUserId)
                            ->where($cTable.".time",">=",$stime)->where($cTable.".time","<",$ftime)->groupBy($uTable.".game")
                            ->get();
            //1玩家总上庄金额2玩家总下注金额3服务费4机器人上庄赢5机器人陪打赢
        
        
        //玩家总上庄金额
        $allCount_two = $allCount = array();
        foreach($coinLogCountObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = $coinLogCount['money'];
            $insert['type']  = 1;
            $brnnMoneyCount->insert($insert); 
        }
        //玩家总上庄人数
        $allCount_two = $allCount = array();
        foreach($coinLogCountObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = abs($coinLogCount['xiazhu_pop']);
            $insert['type']  = 7;
            $brnnMoneyCount->insert($insert); 
        }
        
        $coinLogTwoObj = $connection->table($cTable)->leftJoin($udb['database']."." .$uTable ,$cTable.".uid","=",$uTable.".uid")
                            ->select(DB::raw($uTable.".game,count(DISTINCT ".$cTable.".uid) as xiazhu_pop,sum(changecoin) as money"))->where($cTable.".code",7)->where($cTable.".time",">=",$stime)->where($cTable.".time","<",$ftime)->where($cTable.".gametype",2)
                            ->where($cTable.".uid",">=",$userInfo->aiUserId)->groupBy($uTable.".game")
                            ->get();
        //玩家总下注金额
        foreach($coinLogTwoObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = abs($coinLogCount['money']);
            $insert['type']  = 2;
            $brnnMoneyCount->insert($insert); 
        }
        //玩家总下人数
        foreach($coinLogTwoObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = abs($coinLogCount['xiazhu_pop']);
            $insert['type']  = 6;
            $brnnMoneyCount->insert($insert); 
        }
        
        //3服务费
         $coinLogThreeObj = $baseScl->select(DB::raw("game,sum(charge) as money"))->where("gametype",2)->where("time",">=",$stime)->where("time","<",$ftime)->groupBy("gameid")
                            ->get();
        foreach($coinLogThreeObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = $coinLogCount['money'];
            $insert['type']  = 3;
            $brnnMoneyCount->insert($insert); 
        }

        //4机器人上庄赢
        $coinLogFourObj = $baseRcl->select(DB::raw(" game,sum(coin) as money"))->where("gametype",2)->where("code",1)->where("time",">=",$stime)->where("time","<",$ftime)->groupBy("game")
                            ->get();
        
        foreach($coinLogFourObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = $coinLogCount['money'];
            $insert['type']  = 4;
            $brnnMoneyCount->insert($insert); 
        }

        //5机器人陪打赢
        $coinLogFourObj = $baseRcl->select(DB::raw("game,sum(coin) as money"))->where("gametype",2)->where("code",2)->where("time",">=",$stime)->where("time","<",$ftime)->groupBy("game")
                            ->get();
        foreach($coinLogFourObj as $coinLogCount){
            $insert['game']  = $coinLogCount['game'];
            $insert['date']  = $date;
            $insert['hour_min']  = $hour_min;
            $insert['money']  = $coinLogCount['money'];
            $insert['type']  = 5;
            $brnnMoneyCount->insert($insert); 
        }
    }
 
}
