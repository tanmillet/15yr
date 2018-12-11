<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Count\base\ZfbMoneyCount;
use App\Models\Game\base\CoinLog;
use App\Models\Game\base\userInfo;
use \Illuminate\Support\Facades\DB;
use App\Models\Game\base\ServiceChargeLog;
use App\Models\Game\base\RobotCoinLog;
use App\Models\Count\base\DayCount;
/**
 * Description of ZfbMoneyCountModel
 *
 * @author 七彩P1
 */
class ZfbMoneyCountModel extends ZfbMoneyCount{
    //put your code here
    public function arstianCount(){
        $date_table = date("Ymd", strtotime("-1day"));//隔天问题
        $date=  date("Y-m-d", strtotime("-1day"));
 
        $stime = strtotime($date);
        $ftime = time();
        $userInfo = new userInfo();
        $coinLog  = new CoinLog();
        $uTable = $userInfo->getTable();

        $coinLog1 = $coinLog->setTable($coinLog->table.$date_table);
        $cTable = $coinLog1->getTable();
        
        $baseScl = new ServiceChargeLog;
        $baseScl = $baseScl->setTable($baseScl->table.$date_table);
        
        $baseRcl = new RobotCoinLog;
        $baseRcl = $baseRcl->setTable($baseRcl->table.$date_table);
        
        $udb = config("database.connections.".$userInfo->getConnectionName());
        //coin_log没有  机器人记录
        $connection = DB::connection($coinLog->connection);
        
        $baseDayCount = new DayCount();
        foreach(config("game.game") as $vgame){
            $game = $vgame['value'];
            $dayCountObj= $baseDayCount->where("game",$game)->where("date",$date) ->first();
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $dayCountObj->register_pop?$dayCountObj->register_pop:0;
            $insert['type']  = 1;// 新增用户
            $this->insert($insert);
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $dayCountObj->active_pop?$dayCountObj->active_pop:0;
            $insert['type']  = 2;// 活跃用户
            $this->insert($insert);
            
            
            $coinLogCountObj = $connection->table($cTable)->leftJoin($udb['database']."." .$uTable ,$cTable.".uid","=",$uTable.".uid")
                            ->select(DB::raw("count(DISTINCT ".$cTable.".uid) as xiazhu_pop,sum(changecoin) as money"))->where($cTable.".code",7)->where($cTable.".time",">=",$stime)->where($cTable.".time","<",$ftime)
                            ->where($cTable.".gameid",3)->where($cTable.".gametype",1301)->where($uTable.".game",$game)->where("moneytype",1)->where("changecoin","<",0)
                            ->first();
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $coinLogCountObj['xiazhu_pop']?$coinLogCountObj['xiazhu_pop']:0;
            $insert['type']  = 3;// 下注用户

            $this->insert($insert);
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $coinLogCountObj['money']?abs($coinLogCountObj['money']):0;
            $insert['type']  = 4;// 下注金币总量 
            $this->insert($insert);

            $coinLogCountObj = $connection->table($cTable)->leftJoin($udb['database']."." .$uTable ,$cTable.".uid","=",$uTable.".uid")
                            ->select(DB::raw("count(DISTINCT ".$cTable.".uid) as xiazhu_pop,sum(changecoin) as money"))->where($cTable.".code",6)->where($cTable.".time",">=",$stime)->where($cTable.".time","<",$ftime)
                            ->where($cTable.".gameid",3)->where($cTable.".gametype",1301)->where($uTable.".game",$game)->where("moneytype",1)->where("changecoin","<",0)
                            ->first();            
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $coinLogCountObj['xiazhu_pop']?$coinLogCountObj['xiazhu_pop']:0;
            $insert['type']  = 5;// 上庄用户
            $this->insert($insert);
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $coinLogCountObj['money']?abs($coinLogCountObj['money']):0;
            $insert['type']  = 6;// 上庄金币总量 
            $this->insert($insert);
            //玩家服务费
            $coinLogThreeObj = $baseScl->select(DB::raw("game,sum(charge) as money,banker"))->where("gameid",3)->where("gametype",1301)->where("time",">=",$stime)->where("time","<",$ftime)
                            ->where("uid",">=",$userInfo->aiUserId)
                            ->groupBy("banker")->get();
            $all_money = 0;
            foreach($coinLogThreeObj as $coinLogThree){
                $insert['game']  = $game;
                $insert['date']  = $date_table;
                $insert['money']  = $coinLogThree->money?abs($coinLogThree->money):0;
                $insert['type']  = $coinLogThree->banker==2?7:8;// 7闲家服务费  8庄家服务费
                $all_money+=$insert['money'];
                $this->insert($insert);
            }
            //机器人不区分游戏
            $coinLogThreeObj1 = $baseScl->select(DB::raw("game,sum(charge) as money,banker"))->where("gameid",3)->where("gametype",1301)->where("time",">=",$stime)->where("time","<",$ftime)
                            ->where("uid","<",$userInfo->aiUserId)
                            ->groupBy("banker")->get();
            foreach($coinLogThreeObj1 as $coinLogThree){
                $insert['game']  = $game;
                $insert['date']  = $date_table;
                $insert['money']  = $coinLogThree->money?abs($coinLogThree->money):0;
                $insert['type']  = $coinLogThree->banker==2?12:13;// 机器人 7闲家服务费  8庄家服务费
                $all_money+=$insert['money'];
                $this->insert($insert);
            }
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $all_money;
            $insert['type']  = 9;// 7闲家服务费  8庄家服务费
            $this->insert($insert);
            
             //机器人不区分游戏
            $baseRclObj = $baseRcl->select(DB::raw(" game,sum(coin) as money"))->where("gameid",3)->where("gametype",1301)->where("time",">=",$stime)->where("time","<",$ftime)->where("code",">",0)
                            ->first();
            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $baseRclObj['money']?$baseRclObj['money']:0;;
            $insert['type']  = 10;//机器人赢
            $this->insert($insert);
            
            
            $coinLogCountObj = $connection->table($cTable)->leftJoin($udb['database']."." .$uTable ,$cTable.".uid","=",$uTable.".uid")
                ->select(DB::raw("sum(changecoin) as money"))->where($cTable.".code",6)->where($cTable.".time",">=",$stime)->where($cTable.".time","<",$ftime)
                ->where($cTable.".gameid",3)->where($cTable.".gametype",1301)->where($uTable.".game",$game)->where("moneytype",1)->where("changecoin",">",0)
                ->first();            
            $insert['game']  = $game;
            $insert['date']  = $date_table;
            $insert['money']  = $coinLogCountObj['money']?$coinLogCountObj['money']:0;
            $insert['type']  =11;// 下庄金币数量
            $this->insert($insert);
            
        }
    }
}
