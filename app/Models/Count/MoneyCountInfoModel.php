<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;

use App\Models\Count\base\MoneyCountInfo;
use App\Models\Game\base\CoinLog;
use App\Models\Game\base\userInfo;
use \Illuminate\Support\Facades\DB;
use App\Models\Game\base\RobotCoinLog;
use App\Models\Game\base\ServiceChargeLog;
use App\Models\Game\base\QclandlordWinlog;

/**
 * Description of MoneyCountInfoModel
 *
 * @author 七彩P1
 */
class MoneyCountInfoModel {

    //put your code here
    public function arstianCount() {
        $sdate = date("Y-m-d", strtotime("-1day"));
        $stime = strtotime($sdate . " 00:00:00");
        $ftime = strtotime($sdate . " 23:59:59");
        $date = date("Ymd", strtotime("-1day"));
        $userInfo = new userInfo();
        $coinLog = new CoinLog();
        $moneyCountInfo = new MoneyCountInfo();
        $uTable = $userInfo->getTable();

        $coinLog1 = $coinLog->setTable($coinLog->table . $date);
        $cTable = $coinLog1->getTable();
        $qclWinLog = new QclandlordWinlog();
        //$cTable = $cTable.$date;
        //  dd(config("database.connections.".$userInfo->getConnectionName()));
        $udb = config("database.connections." . $userInfo->getConnectionName());
        //coin_log没有  机器人记录
        $connection = DB::connection($coinLog->connection);
        $coinLogCountObj = $connection->table($cTable)->leftJoin($udb['database'] . "." . $uTable, $cTable . ".uid", "=", $uTable . ".uid")->where("changecoin", ">", 0)
                        ->select(DB::raw($uTable . ".game,moneytype,code,sum(changecoin) as money_count"))->groupBy($uTable . ".game", "moneytype", "code")->get();
        //["1"=>"金币总发放","2"=>"金币总消耗","3"=>"金币净消耗","4"=>"业务发放","5"=>"业务消耗",
        //"6"=>"业务净消耗","7"=>"机器人净赢","8"=>"人均金币数","9"=>"金币总量","10"=>"活跃金币总量"]
        //"4"=>"业务发放","5"=>"业务消耗",
        $serverCode = $moneyCountInfo->codeServer; //服务费code
        $cardCode = $moneyCountInfo->codeCard; //用户牌局变动code

        $cardCount = $serverCount = $allCount_two = $allCount = array();
        $countCode = $moneyCountInfo->countCode;
        foreach ($coinLogCountObj as $coinLogCount) {
            if (in_array($coinLogCount['code'], array_keys($countCode)) && in_array(1, $countCode[$coinLogCount['code']])) {
                $insert['game'] = $coinLogCount['game'];
                $insert['date'] = $date;
                $insert['code'] = $coinLogCount['code'];
                $insert['money_count'] = (string) $coinLogCount['money_count'];
                $insert['money_type'] = $coinLogCount['moneytype'];
                $insert['type'] = 4;
                $allCount[$coinLogCount['game']][1][$insert['money_type']] = isset($allCount[$coinLogCount['game']][1][$insert['money_type']]) ? ($allCount[$coinLogCount['game']][1][$insert['money_type']] + $coinLogCount['money_count']) : $coinLogCount['money_count'];

                $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] = isset($allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']]) ? $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $moneyCountInfo->insert($insert);
            }
            if (in_array($coinLogCount['code'], $cardCode)) {
                $cardCount[$coinLogCount['game']][$coinLogCount['moneytype']] = isset($cardCount[$coinLogCount['game']][$coinLogCount['moneytype']]) ? $cardCount[$coinLogCount['game']][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] = isset($allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']]) ? $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : 0 + $coinLogCount['money_count']; //计算机器人打牌输赢 
                $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] = isset($allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']]) ? $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] + $coinLogCount['money_count'] : 0 + $coinLogCount['money_count'];
            }
        }

        $coinLogTwoObj = $connection->table($cTable)->leftJoin($udb['database'] . "." . $uTable, $cTable . ".uid", "=", $uTable . ".uid")->where("changecoin", "<", 0)->select(DB::raw($uTable . ".game,pfid,usid,moneytype,code,sum(changecoin) as money_count"))->groupBy($uTable . ".game", "moneytype", "code")->get();
        foreach ($coinLogTwoObj as $coinLogCount) {
            if (in_array($coinLogCount['code'], array_keys($countCode)) && in_array(2, $countCode[$coinLogCount['code']])) {
                $insert['game'] = $coinLogCount['game'];
                $insert['date'] = $date;
                $insert['code'] = $coinLogCount['code'];
                $insert['money_count'] = (string) abs($coinLogCount['money_count']);
                $insert['money_type'] = $coinLogCount['moneytype'];
                $insert['type'] = 5;
                $allCount[$coinLogCount['game']][2][$insert['money_type']] = isset($allCount[$coinLogCount['game']][2][$insert['money_type']]) ? $allCount[$coinLogCount['game']][2][$insert['money_type']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] = isset($allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']]) ? $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $moneyCountInfo->insert($insert);
            }
            if (in_array($coinLogCount['code'], $serverCode)) {
                $serverCount[$coinLogCount['game']][$coinLogCount['moneytype']] = isset($serverCount[$coinLogCount['game']][$coinLogCount['moneytype']]) ? $serverCount[$coinLogCount['game']][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] = isset($allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']]) ? $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] = isset($allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']]) ? $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
            }
            if (in_array($coinLogCount['code'], $cardCode)) {
                $cardCount[$coinLogCount['game']][$coinLogCount['moneytype']] = isset($cardCount[$coinLogCount['game']][$coinLogCount['moneytype']]) ? $cardCount[$coinLogCount['game']][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : $coinLogCount['money_count'];
                $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] = isset($allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']]) ? $allCount[$coinLogCount['game']][2][$coinLogCount['moneytype']] + $coinLogCount['money_count'] : 0 + $coinLogCount['money_count']; //计算机器人打牌输赢 ----本来是计算出玩家的输赢 然后  就能计算出机器人的
                $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] = isset($allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']]) ? $allCount_two[$coinLogCount['game']][$coinLogCount['moneytype']][$coinLogCount['code']] + $coinLogCount['money_count'] : 0 + $coinLogCount['money_count'];
            }
        }
        $allCountOne = array();

        $scl = new ServiceChargeLog();
        $scl1 = $scl->setTable($scl->table . $date);
        //牌局服务费
        foreach ($serverCount as $game => $all) {
            foreach ($all as $moneytype => $money_count) {
                $insert['game'] = $game;
                $insert['date'] = $date;
                $insert['code'] = 117780;
                $insert['money_count'] = (string) abs($money_count); //负数 取绝对值
                $insert['money_type'] = $moneytype;
                $insert['type'] = 5;
                $moneyCountInfo->insert($insert);
            }
        }


        //娱乐场服务费
        $scl1Obj = $scl1->select(DB::raw("game,sum(charge) as money_count"))->get();
        foreach (config("game.game") as $game_value) {
            $vgame = $game_value['value'];
            foreach ($scl1Obj as $x => $all) {
                $insert['game'] = $vgame;
                $insert['date'] = $date;
                $insert['code'] = 117777;
                $insert['money_count'] = (string) abs($all['money_count']); //获取的是正式 然后转换成负数同意
                $insert['money_type'] = 1;
                $insert['type'] = 5;
                $all['game'] = $vgame;
                $money_count = ( 0 -$all['money_count']);
                $allCount[$all['game']][2][1] = isset($allCount[$all['game']][2][1]) ? $allCount[$all['game']][2][1] + $money_count : $money_count; //计算总送出
                $allCount_two[$all['game']][1][117777] = isset($allCount_two[$all['game']][1][117777]) ? $allCount_two[$all['game']][1][117777] + $money_count : $money_count; //计算单个净消耗
                $moneyCountInfo->insert($insert);
            }
        }
        //娱乐场机器人陪打
        $rcl = new RobotCoinLog();
        $rcl1 = $rcl->setTable($rcl->table . $date);
        $rcl1Obj = $rcl->select(DB::raw("game,sum(coin) as money_count"))->where("code", ">", 0)->get();
        foreach (config("game.game") as $game_value) {
            $vgame = $game_value['value'];
            foreach ($rcl1Obj as $all) {
                $insert['game'] = $vgame;
                $insert['date'] = $date;
                $insert['code'] = 117778;
                $insert['money_count'] = (string) $all['money_count'];
                $insert['money_type'] = 1;
                $insert['type'] = 5;
                $all['game'] = $vgame;
                $allCount[$all['game']][2][1] = isset($allCount[$all['game']][2][1]) ? $allCount[$all['game']][2][1] - $all['money_count'] : 0-$all['money_count']; //计算总送出
                $allCount_two[$all['game']][1][117778] = isset($allCount_two[$all['game']][1][117778]) ? $allCount_two[$all['game']][1][117778] - $all['money_count'] : 0-$all['money_count'];
                $moneyCountInfo->insert($insert);
            }
        }
        //牌局机器人陪打赢
        foreach ($cardCount as $game => $all) {
            foreach ($all as $moneytype => $money_count) {
                $insert['game'] = $game;
                $insert['date'] = $date;
                $insert['code'] = 117779;
                $insert['money_count'] = (string) (0 - $money_count); //玩家赢的钱就是 机器人输的前
                $insert['money_type'] = 1;
                $insert['type'] = 5;
                $moneyCountInfo->insert($insert);
            }
        }
        /* $clObj = (new $qclWinLog)->select(DB::raw("game,sum(wlwin) as money_count"))->where("wltime",">=",$stime)->where("wltime","<=",$ftime)->where("uid",">=",$userInfo->aiUserId)->groupBy("game")->get();
          foreach($clObj as $all){
          $insert['game']  = $all['game'] ;
          $insert['date']  = $date;
          $insert['code']  = 117779;
          $insert['money_count']  = (string)$all['money_count'];
          $insert['money_type']  =1;
          $insert['type']  = 5;

          $allCount[$all['game']][2][1] = isset($allCount[$all['game']][2][1])?$allCount[$all['game']][2][1]+$all['money_count']:$all['money_count'];//计算总送出
          $allCount_two[$all['game']][1][117779] = isset($allCount_two[$all['game']][1][117779])?$allCount_two[$all['game']][1][117779]+$all['money_count']:$all['money_count'];

          $moneyCountInfo->insert($insert);
          } */


        //"1"=>"金币总发放","2"=>"金币总消耗"
        foreach ($allCount as $game => $all) {
            foreach ($all as $type => $count) {
                foreach ($count as $moneytype => $money_count) {
                    $insert['game'] = $game;
                    $insert['date'] = $date;
                    $insert['code'] = -1;
                    
                    $insert['money_type'] = $moneytype;
                    $insert['type'] = $type;
                    if ($type == 2) {
                        $money_count = 0 - $money_count;
						$insert['money_count'] = $money_count;
                    }else{
						$insert['money_count'] = (string) $money_count;
					}
                    $allCountOne[$game][$moneytype] = isset($allCountOne[$game][$moneytype]) ? $allCountOne[$game][$moneytype] + $money_count : $money_count;
                    $moneyCountInfo->insert($insert);
                }
            }
        }

        //3净消耗
        foreach ($allCountOne as $game => $all) {
            foreach ($all as $moneytype => $money_count) {
                $insert['game'] = $game;
                $insert['date'] = $date;
                $insert['code'] = -1;
                $insert['money_count'] = (string) (0 - $money_count);
                $insert['money_type'] = $moneytype;
                $insert['type'] = 3;
                $moneyCountInfo->insert($insert);
            }
        }
        //6 业务净消耗
        foreach ($allCount_two as $game => $all) {
            foreach ($all as $moneytype => $count) {
                foreach ($count as $code => $money_count) {
                    $insert['game'] = $game;
                    $insert['date'] = $date;
                    $insert['code'] = $code;
                    $insert['money_count'] = (string) ($money_count);
                    $insert['money_type'] = $moneytype;
                    $insert['type'] = 6;
                    $moneyCountInfo->insert($insert);
                }
            }
        }
    }

}
