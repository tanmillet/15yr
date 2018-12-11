<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game\base\BackPage;
use App\Models\Game\base\Goods;
use App\Models\Game\MoneyModel;
use Illuminate\Support\Facades\DB;
use App\Models\Game\base\UseGoodsLog;
use App\Models\Game\base\ObGoods;
//use \App\Log\Facades\Logger;
//use App\Models\EmailModel;
use App\Models\Game\SokectModel;
//use App\Models\UserAddressModel;
/**
 * Description of UseGoodsModel
 *
 * @author 七彩P1
 */
class UseGoodsModel extends Model {
    /*
     * 使用道具uid用户id $backpackId背包表ID num使用道具数量 ref在哪里使用的来源
     * $callBack回调函数
     * $ref 1是购买时候直接使用    4是定时任务跑批
     * $search_ref  查找来源 也用来区分哪个活动
     */
    public $use_errorcode = "";
    public function UseGoods($uid, $backpackId, $ref, $num = 1,$search_ref=0) {
        $ret = true;
        //去背包里面查找是否有该道具
        //Logger::info("用户id" . $uid . "--背包表ID" . $backpackId . "使用来源" . $ref . "使用数量" . $num, "useGoods");
        $baseBackPack = new BackPage();
        $backPackInfoObj = $baseBackPack->where("id", $backpackId)->first();
        if (!$backPackInfoObj) {
            $this->use_errorcode ="USEGOODSNONUM";
            //Logger::info("用户id" . $uid . "--背包表ID" . $backpackId . "使用来源" . $ref . "使用数量" . $num . "背包不存在该道具", "useGoods");
            return false;
        }
        $backPackInfo = $backPackInfoObj->toArray();
        $baseGoods = new Goods();

        $obGoods = (new ObGoods())->where("id", $backPackInfo['obgoods_id'])->first(); //获取使用道具信息
        if (!$backPackInfoObj || !$obGoods) {
            $this->use_errorcode ="GOODSNOTEXIT";
            //Logger::info("用户id" . $uid . "--背包表ID" . $backpackId . "使用来源" . $ref . "使用数量" . $num . "道具信息不存在", "useGoods");
            return false;
        }
        $obGoods = $obGoods->toArray();
        $backPackInfo = $backPackInfo + $obGoods;
        $backPackInfo['search_ref'] = $search_ref;
        $backPackInfo['ref'] = $ref;
        $backGoodsInfo['bpid'] = $backpackId;
        
        //实物道具没有填写地址
        /*if(in_array($backPackInfo["gtype"], $baseGoods->checkAddressType)){
            $adr = (new UserAddressModel)->addressInfo($uid, $backPackInfo['game']);
            if(!isset($adr['is_write']) || !$adr['is_write']){
                $this->use_errorcode = config("errorcode.ADDRESSISNOTWRITE");
                return FALSE;
            }
           
        }*/
        
        //变更背包道具
        $data = time();
        $useGoodsLog = [
            "uid" => $uid,
            "obgoods_id" => $backPackInfo['obgoods_id'],
            "created_at" => date("Y-m-d H:i:s", $data),
            "before_num" => $backPackInfo['bpg_num'],
            "change_num" => $num,
            "use_ref" => $ref,
            "after_num" => $backPackInfo["bpg_num"] - $num,
            "search_ref" => $search_ref,
        ];
        $useGoodsLog = (new UseGoodsLog())->insert($useGoodsLog); //道具使用记录
        if (array_key_exists($backPackInfo['gtype'], $baseGoods->goods_type_getCoin)) {//添加货币类道具
            $countGoodsAttr = $this->countGoodsAttr($num, $backPackInfo['gcoin'], $backPackInfo['gat_coin'], $backPackInfo['gvalid_time_type'], $backPackInfo['gvalid_time'], 1);
            $ret = $this->useGoodsCoin($uid, $backPackInfo['gtype'], $countGoodsAttr['num'],$ref,$search_ref);
        } else {//添加使用道具
            $countGoodsAttr = $this->countGoodsAttr($num, $backPackInfo['gcoin'], $backPackInfo['gat_coin'], $backPackInfo['gvalid_time_type'], $backPackInfo['gvalid_time']);
            $backPackInfo['num'] = $countGoodsAttr['num']; //获得的个数
            if (array_key_exists($backPackInfo['gtype'], $baseGoods->useGoodsCallBack)) {
               // \App\Log\Facades\//Logger::info("用户id" . $uid . "添加使用道具回调操作方法" . json_encode($baseGoods->useGoodsCallBack[$backPackInfo['gtype']]), "useGoods");
                $class = $baseGoods->useGoodsCallBack[$backPackInfo['gtype']]['class'];
                $model = $baseGoods->useGoodsCallBack[$backPackInfo['gtype']]['method'];
                call_user_func(function($data)use(&$class, &$model, &$ret) {
                    //try {
                        if ($model) {
                            $classAtrr = new $class();
                            $ret = $classAtrr->{$model}($data);
                        } else {
                            $ret = new $class($data);
                        }
                    //} catch (\Exception $e) {
                    //    \App\Log\Facades\//Logger::info("添加使用道具回调操作方法失败" . $e->getMessage() . "数据" . json_encode($data), "useGoods");
                    //}
                }, $backPackInfo);
                //\App\Log\Facades\//Logger::info("用户id" . $uid . "添加使用道具回调操作方法返回结果" . json_encode($ret), "useGoods");
            }
        }

        $upt['bpg_num'] = DB::raw("bpg_num -" . $num);
        if (!$backPackInfo['bpg_use_at'] || $backPackInfo["bpg_over_at"] < time()) {
            $upt['bpg_use_at'] = time();
            $upt["bpg_over_at"] = time() + $countGoodsAttr['bpg_over_at'];
        } else {
            $upt["bpg_over_at"] = DB::raw("bpg_over_at +" . $countGoodsAttr['bpg_over_at']);
        }
        $baseBackPack->where("id", $backpackId)->where("bpg_num", ">=", $num)->update($upt); //修改道具使用时间
        $baseBackPack->where("id", $backpackId)->where("bpg_num", 0)->where("bpg_over_at", "<=", time())->delete(); //删除已经使用完的道具
        //根据道具类型 整合道具  例如保险箱的道具  5天跟30天可以叠加
        $this->superpositionGoods($uid, $backpackId);

        //$backPackInfo['email_content'] && (new EmailModel())->sendEmail($uid, $backPackInfo['email_content']);
        if (in_array($backPackInfo['gtype'], $baseGoods->notice_goods_type)) {//让server 通知前段
            $send_data['uid'] = $uid;
            $send_data['msgtype'] = config("socket.sendMsgType.changGoods");
            $send_data['data']['type'] = $backPackInfo['gtype'];
            $send_data['data']['gname'] = $backPackInfo['gname'];
            $send_data['data']['other_id'] = isset($ret['realGoodsId']) ? $ret['realGoodsId'] : 0;
            $send_data['data']['time'] = $countGoodsAttr['bpg_over_at'];
            (new SokectModel())->sendMsg($send_data);
        }
        if(is_array($ret)){
            !isset($ret["goods_img"]) && $ret["goods_centens"] = $num ==1 ?$backPackInfo['email_content']:$backPackInfo['email_content']."*".$num;
            !isset($ret["goods_img"]) && $ret["goods_img"] = env("CDNURL").$backPackInfo['goods_img'];
        }
        if($ret){//使用成功
            $this->use_errorcode=0;
        }
        return $ret;
    }

    /*
     * 使用添加货币类道具
     */

    public function useGoodsCoin($uid, $bpg_type, $num,$ref,$search_ref) {
        $baseGoods = new Goods();
        $moneyModel = new MoneyModel();
        $type = $baseGoods->goods_type_getCoin[$bpg_type];
        $code = (new UseGoodsLog)->getCode($ref,$search_ref);
        $code = $code?$code:"goodsMake";
        $flag = $moneyModel->operateMoney($uid, $num, $code, $type);
        return $flag;
    }

    /*
     * 根据商品属性去计算 得到道具次数跟道具过期时间
     */

    public function countGoodsAttr($num, $ogcoin, $ogat_coin, $ogvalid_time_type, $ogvalid_time, $countType = 0) {
        $time = $ogvalid_time;
        $dsTime = $dayTime = 0;
        /* if($ogvalid_time_type==2){//隔天生效
          $dsTime = strtotime(date("Y-m-d 23:59:59")) - time();//当天剩余秒数
          $dayTime = ($num -1) * 3600 * 24;
          } */

        $reslt["num"] = $num * ($ogcoin + $ogat_coin);
        $resltNum = $reslt["num"];
        if ($countType == 1) {//金币类型的过期时间
            $resltNum = $num;
        }
        $reslt["bpg_over_at"] = $resltNum * $time * 3600 + $dsTime + $dayTime;
        return $reslt;
    }

    /*
     * 定时使用道具
     */

    public function timeUseGoods($gvalid_time_type = 3) {
        $baseBackPack = new BackPage();
        $bpTableName = $baseBackPack->getTable();
        $baseObGoods = new ObGoods();
        $obTableName = $baseObGoods->getTable();
        $backPackInfoObj = $baseBackPack->leftjoin($baseObGoods, $bpTableName . "obgoods_id", "=", $obTableName . ".id")
                ->where($bpTableName . ".bpg_num", ">", 0)
                ->where($obTableName . ".gvalid_time_type", $gvalid_time_type)
                ->select(DB::raw($bpTableName . ".id as backpackId,uid"))
                ->get();
        $backPackInfos = $backPackInfoObj->toArray();
        foreach ($backPackInfos as $bpInfo) {
            $this->UseGoods($bpInfo['uid'], $bpInfo['backpackId'], 4);
        }
    }

    /*
     * 道具叠加
     * uid用户id
     * $backpackId背包id
     */

    public function superpositionGoods($uid, $backpackId) {
        $baseBackPack = new BackPage();
        $baseObGoods = new ObGoods();
        $baseGoods = new Goods();
        $bpTable = $baseBackPack->getTable();
        $obTable = $baseObGoods->getTable();
        $bpoObj = $baseBackPack->leftjoin($obTable, $bpTable . ".obgoods_id", "=", $obTable . ".id")
                        ->where($bpTable . ".id", $backpackId)
                        ->where($bpTable . ".bpg_over_at", ">", time())
                        ->where($bpTable . ".uid", "=", $uid)->first();
        if (!empty($bpoObj)) {
            $pbo = $bpoObj->toArray();
            $ifwhere = $baseGoods->dealIntegGoodsType($pbo, $obTable);
            //Logger::info("用户 {$uid} 背包id{$backpackId}开始合并道具" . json_encode($ifwhere), "mergeGoods");
            if ($ifwhere) {
                $superObj = $baseBackPack->leftjoin($obTable, $bpTable . ".obgoods_id", "=", $obTable . ".id");
                foreach ($ifwhere['where'] as $key => $v) {
                    $superObj = $superObj->whereIn($key, $v);
                }
                $superObj = $superObj->where($bpTable . ".uid", "=", $uid)->select(DB::raw($bpTable . ".id as bpId ," . $bpTable . ".*," . $obTable . ".*"))->get();
                if (!$superObj) {
                    return false;
                }
                $super = $superObj->toArray();
                $update = array();
                //Logger::info("用户 {$uid} 背包id{$backpackId}道具--" . json_encode($super), "mergeGoods");
                $low_bpg_use_at = 0;//最小的开始使用时间
                foreach ($super as $value) {
                    if ($value["bpId"] == $backpackId) {
                        continue;
                    }
                    if ($ifwhere['merge'] == 1) {
                        $syTime =  ($value['bpg_over_at']>time())?($value['bpg_over_at'] - time()):0;//判断剩余时间 
                        $update['bpg_over_at'] = !isset($update['bpg_over_at']) ? $syTime : ($update['bpg_over_at'] + $syTime);
                        $upt['bpg_over_at'] = $value['bpg_use_at'];
                        if($low_bpg_use_at){
                            ($value['bpg_use_at']<$low_bpg_use_at) && $low_bpg_use_at = $value['bpg_use_at'];
                        }else{
                            $low_bpg_use_at = $value['bpg_use_at'];
                        }
                    } else {
                        $update['bpg_num'] = !isset($update['bpg_num']) ? $value['bpg_num'] : $update['bpg_num'] + $value['bpg_num'];
                        $upt['bpg_num'] = 0;
                    }
                    //Logger::info("用户 {$uid} 背包id{$backpackId}道具--道具" . json_encode($value), "mergeGoods");
                    //Logger::info("用户 {$uid} 背包id{$backpackId}道具--修改的选项" . json_encode($upt), "mergeGoods");
                    $baseBackPack->where("id", $value["bpId"])->update($upt); //把叠加的道具修改掉
                }

                //Logger::info("用户 {$uid} 背包id{$backpackId}道具--修改道具" . json_encode($update), "mergeGoods");
                if (!empty($update)) {
                    foreach ($update as $key => $value) {
                        $update[$key] = DB::raw($key . ' + ' . $value);
                    }
                    $uptObj = $baseBackPack->where("id", $backpackId)->update($update);
                }
            }
        }
    }

}
