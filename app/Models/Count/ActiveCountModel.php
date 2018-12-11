<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;

use App\Models\Count\base\ActiveCount;
use \Illuminate\Support\Facades\DB;
use App\Models\Game\base\UserGetGoodsLog;
use App\Models\Game\base\Goods;
use App\Models\Game\base\UserActivePrizeLog;
use App\Models\Game\base\Active;
use App\Models\Game\base\LoginPrizeLog;
use App\Models\Game\base\UserDayLog;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\userGame;
use App\Models\Game\base\CodeReward;

/**
 * Description of ActiveCountModel
 *
 * @author 七彩P1
 */
class ActiveCountModel extends ActiveCount {

    //put your code here
    public function arstianCount() {
        $date = date("Y-m-d", strtotime("-1day"));
        $uaplModel = new UserActivePrizeLog;
        $insert = array();
        $active = new Active;
        foreach (config("game.game") as $game_data) {
            $game = $game_data['value'];
            $dataObj = $uaplModel->select(DB::raw("count( DISTINCT uid ) as pop_num,sum(finish_num) as num,active_id,active_level"))->where("game", $game)
                            ->where("created_at", ">=", $date . " 00:00:00")->where("created_at", "<=", $date . " 23:59:59")->groupBy("active_id")->groupBy("active_level")->get();
            $data = $dataObj->toArray();
            foreach ($data as $value_data) {
                $active_type = $active->where("id", $value_data['active_id'])->pluck("active_type")->first();
                $insert["game"] = $game;
                $insert["date"] = $date;
                $insert["active_type"] = $active_type;
                $insert["type"] = $value_data["active_level"];
                $insert["num"] = $value_data["num"];
                $insert["pop_num"] = $value_data["pop_num"];
                $this->insert($insert);
            }

            $dataObj = $uaplModel->select(DB::raw("count( DISTINCT uid ) as pop_num,sum(finish_num) as num,active_id"))->where("game", $game)->groupBy("active_id")
                            ->where("created_at", ">=", $date . " 00:00:00")->where("created_at", "<=", $date . " 23:59:59")->groupBy("active_id")->get();
            $data = $dataObj->toArray();
            foreach ($data as $value_data) {
                $active_type = $active->where("id", $value_data['active_id'])->pluck("active_type")->first();
                $insert["game"] = $game;
                $insert["date"] = $date;
                $insert["active_type"] = $active_type;
                $insert["type"] = 1000;
                $insert["num"] = $value_data["num"];
                $insert["pop_num"] = $value_data["pop_num"];
                $this->insert($insert);
            }
        }
        //中秋节统计
        //$this->midderArstianCount();
        //登录奖励统计
        $this->countLoginPrizeLog();
        //更新新手活动的参与数
        $this->updateNewHandRedPack();
    }

    //中秋节统计
    public function midderArstianCount() {
        //送灯笼个数
        $uggModel = new UserGetGoodsLog();
        $baseGoods = new Goods();
        $goods_id_arr = ["2" => 167]; //灯笼个数
        $date = date("Y-m-d", strtotime("-1day"));
        foreach (config("game.game") as $game_data) {

            $game = $game_data['value'];
            if (!isset($goods_id_arr[$game]) || !$goods_id_arr[$game]) {
                continue;
            }
            $dataObj = $uggModel->select(DB::raw("count( DISTINCT uid ) as pop_num,count(uid) as num"))->where("game", $game)->where("goods_id", $goods_id_arr[$game])
                            ->where("created_at", ">=", $date . " 00:00:00")->where("created_at", "<=", $date . " 23:59:59")->get();
            $data = $dataObj->toArray();
            foreach ($data as $value_data) {
                $insert["game"] = $game;
                $insert["date"] = $date;
                $insert["active_type"] = 17;
                $insert["type"] = 7;
                $insert["num"] = $value_data["num"];
                $insert["pop_num"] = $value_data["pop_num"];
                $this->insert($insert);
            }

            $zi_goods_arr = $baseGoods->where("game", $game)->where("type", 27)->pluck("id");
            //获得字个数
            if ($zi_goods_arr) {
                $dataObj = $uggModel->select(DB::raw("count( DISTINCT uid ) as pop_num,count(uid) as num"))->where("game", $game)->whereIn("goods_id", $zi_goods_arr)
                                ->where("created_at", ">=", $date . " 00:00:00")->where("created_at", "<=", $date . " 23:59:59")->get();
                $data = $dataObj->toArray();
                foreach ($data as $value_data) {
                    $insert["game"] = $game;
                    $insert["date"] = $date;
                    $insert["active_type"] = 17;
                    $insert["type"] = 8;
                    $insert["num"] = $value_data["num"];
                    $insert["pop_num"] = $value_data["pop_num"];
                    $this->insert($insert);
                }
            }
        }
    }

    //登录奖励统计
    public function countLoginPrizeLog() {
        $loginPrizeLog = new LoginPrizeLog();
        $userDayLog = new UserDayLog();
        $lplTable = $loginPrizeLog->getTable();
        $udlTable = $userDayLog->getTable();
        $date = date("Y-m-d", strtotime("-1day"));
        $sdate = date("Ymd", strtotime("-2day"));
        $fdate = date("Ymd", strtotime("-1day"));
        $asdate = 20181123;
        $sdate = ($sdate < $asdate) ? $asdate : $sdate;
        foreach (config("game.game") as $game_data) {
            $game = $game_data['value'];
            $dataObj = $loginPrizeLog->where("game", $game)->where("date", $date)->where("type", 2)->select(DB::raw("count(uid) as num,prize_type"))->groupBy("prize_type")->get();
            if (!$dataObj) {
                continue;
            }
            $data = $dataObj->toArray();
            foreach ($data as $value_data) {
                $insert["game"] = $game;
                $insert["date"] = $date;
                $insert["active_type"] = 999;
                $insert["type"] = $value_data["prize_type"];
                $insert["num"] = $value_data["num"];
                $insert["pop_num"] = $value_data["num"];
                $this->insert($insert);
            }
            $lockObj1 = $loginPrizeLog->select(DB::raw("count(" . $lplTable . ".uid" . ") as num,uid" ))
                        ->where($lplTable . ".date", ">=", date("Y-m-d", strtotime("-2day")))->where($lplTable . ".date", "<=", $date)
                    ->where($lplTable . ".game", "=", $game)->where($lplTable . ".type", "=", 2)
                        ->groupBy($lplTable . ".uid")->having("num", ">", 1)->get();

            $lockObj2 = $loginPrizeLog->select(DB::raw("count(" . $lplTable . ".uid" . ") as num,uid" ))
                        ->where($lplTable . ".date", ">=", date("Y-m-d", strtotime("-3day")))->where($lplTable . ".date", "<=", $date)
                        ->where($lplTable . ".game", "=", $game)->where($lplTable . ".type", "=", 2)
                        ->groupBy($lplTable . ".uid")->having("num", ">", 2)->get();
                        
            $lockObj1 && $lock1Data = $lockObj1->toArray();
            $lockObj2 && $lock2Data = $lockObj2->toArray();
            $lock1 = $lock2 = 0;
             
            $lockObj1 && $lock1 = count($lock1Data);
            $lockObj2 && $lock2 = count($lock2Data);
            
            $insert1["game"] = $game;
            $insert1["date"] = $date;
            $insert1["active_type"] = 999;
            $insert1["type"] = 7;
            $insert1["num"] = $lock1-$lock2;
            $insert1["pop_num"] = $lock1-$lock2;
            $this->insert($insert1);

            $insert1["game"] = $game;
            $insert1["date"] = $date;
            $insert1["active_type"] = 999;
            $insert1["type"] = 8;
            $insert1["num"] = $lock2;
            $insert1["pop_num"] = $lock2;
            $this->insert($insert1);
        }
    }

    public function updateNewHandRedPack() {
        $userInfo = new userInfo();
        $uiTable = $userInfo->getTable();
        $userDayLog = new UserDayLog();
        $udlTable = $userDayLog->getTable();

        $userGame = new userGame();
        $ugTable = $userGame->getTable();
        $date = date("Y-m-d", strtotime("-1day"));

        $sdate = date("Ymd", strtotime("-8day"));
        $fdate = date("Ymd", strtotime("-1day"));
        $stime = strtotime(date("Y-m-d 00:00:00", strtotime("-8day")));
        $ftime = strtotime(date("Y-m-d 23:59:59", strtotime("-1day")));

        $lstime = strtotime(date("Y-m-d 00:00:00", strtotime("-1day")));

        $codeReward = new CodeReward();
        foreach (config("game.game") as $game_data) {
            $game = $game_data['value'];

            $lockObj = $userDayLog->leftJoin($uiTable, $uiTable . ".uid", "=", $udlTable . ".uid")
                            ->leftJoin($ugTable, $ugTable . ".uid", "=", $udlTable . ".uid")
                            ->where($ugTable . ".utime", ">=", $lstime)->where($ugTable . ".utime", "<=", $ftime)
                            ->where($udlTable . ".date", ">=", $sdate)->where($udlTable . ".date", "<=", $fdate)
                            ->where($uiTable . ".urtime", ">=", $stime)->where($uiTable . ".urtime", "<=", $ftime)->where($uiTable . ".game", "=", $game)
                            ->select(DB::raw("count(" . $uiTable . ".uid" . ") as num," . $uiTable . ".uid"))->groupBy($udlTable . ".uid")->having("num", ">", 0)->get();
            $lockData = $lockObj->toArray();
            $count = array("1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0);
            foreach ($lockData as $value) {
                if ($value["num"] == 1) {
                    $count[1] += 1;
                } elseif ($value["num"] == 2) {
                    $count[2] += 1;
                } elseif ($value["num"] == 3) {
                    $count[3] += 1;
                } elseif ($value["num"] == 4) {
                    $count[4] += 1;
                } elseif ($value["num"] == 5) {
                    $count[5] += 1;
                }
            }
            foreach ($count as $type => $num) {
                $insert["game"] = $game;
                $insert["date"] = $date;
                $insert["active_type"] = 20;
                $insert["type"] = $type;

                $update["num"] = $num;
                $this->updateOrCreate($insert, $update);
            }

            $codeRewardObj = $codeReward->where("game", $game)->where("type", 1)->where("created_at", ">=", $lstime)->where("created_at", "<=", $ftime)->select(
                            DB::raw("count(id) as num,sum(if(is_use,1,0)) as reward_num")
                    )->first();
            $codeRewardArr = $codeRewardObj ? $codeRewardObj->toArray() : array();
            $insert1["game"] = $game;
            $insert1["date"] = $date;
            $insert1["active_type"] = 20;
            $insert1["type"] = 6;
            $update1["pop_num"] = isset($codeRewardArr["num"])?$codeRewardArr["num"]:0;
            $update1["num"] = isset($codeRewardArr["num"])?$codeRewardArr["num"]:0;
            $this->updateOrCreate($insert1, $update1);

            $insert2["game"] = $game;
            $insert2["date"] = $date;
            $insert2["active_type"] = 20;
            $insert2["type"] = 7;
            $update2["pop_num"] = isset($codeRewardArr["reward_num"])?$codeRewardArr["reward_num"]:0;
            $update2["num"] = isset($codeRewardArr["reward_num"])?$codeRewardArr["reward_num"]:0;
            $this->updateOrCreate($insert2, $update2);
        }
    }

}
