<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZhuangGoods
 *
 * @author 抢庄道具
 */
namespace App\Server\Goods;
use App\Models\base\BackPage;
class CallBackGoods {
    //put your code here
    /*
     * 添加抢庄道具
     */
    public function   addZhuangGoods($goodsInfo){
        $uid = $goodsInfo['uid'];
        $num = $goodsInfo['num'];
        (new \App\Models\base\UserGame())->where("uid",$uid)->increment("zgcount",$num);
        return true;
    }
    
    /*
     * 添加救济金的次数
     */
    public function   addMuCountGoods($goodsInfo){
        $uid = $goodsInfo['uid'];
        $num = $goodsInfo['num'];
        (new \App\Models\base\UserGame())->where("uid",$uid)->increment("mucount",$num);
         return true;
    }
     /*
     * 添加VIP
     */
    public function addVipGoods($goodsInfo){
        $uid = $goodsInfo['uid'];
        (new \App\Models\base\UserGame())->where("uid",$uid)->where("uvip","<",$goodsInfo['gis_vip'])->update(array("uvip"=>$goodsInfo['gis_vip']));
         return true;
    }
    
    /*
     * 添加保险箱道具
     */
    public function addStrongBoxGoods($goodsInfo){
        $uid = $goodsInfo['uid'];
        (new \App\Models\base\UserInfo())->where("uid",$uid)->update(array("strong_box"=>1));
         return true;
    }
    
     /*
     * 添加日赛道具
     */
    public function addDayTicket($goodsInfo){
        $uid = $goodsInfo['uid'];
        $num = $goodsInfo['num'];
        (new \App\Models\base\UserGame())->where("uid",$uid)->increment("day_ticket",$num);
         return true;
    }
    
     /*
     * 添加月赛道具
     */
    public function addMonthTicket($goodsInfo){
        $uid = $goodsInfo['uid'];
        $num = $goodsInfo['num'];
        (new \App\Models\base\UserGame())->where("uid",$uid)->increment("month_ticket",$num);
         return true;
    }
    
   /*
     * 惊喜大礼包
     */
    public function drawGoods($goodsInfo){
        $ret  =  (new \App\Models\DrawGoodsModel())->drawGoods($goodsInfo);
        return $ret;
    }
    
    /*
     * 惊喜大礼包
     */
    public function realGoods($goodsInfo){
        $insert['uid'] = $goodsInfo['uid'];
        $insert['num'] = $goodsInfo['num'];
        $insert['ob_goods_id'] = $goodsInfo['ob_goods_id'];
        $insert['created_at'] = date("Y-m-d H:i:s");
        $insert['status'] = 0;
        $insert['game'] = $goodsInfo['game'];
        $realOrder = (new \App\Models\base\RealOrder());
        $insert['real_order'] = $realOrder->getOrder();
        $id = $realOrder->insertGetId($insert);
        $ret["realGoodsId"] = $id;
        return $ret;
    }
    
    
    /*
     * 删除VIP道具
     */
    public function deleteVipGoods($backGoodsInfo){
        $baseBackPage = new BackPage();
        $baseObGoods = new ObGoods();   
        $baseGoods  =  new Goods();
        $deleteBackObj = $baseBackPage->leftjoin($baseObGoods->getTable(),$baseBackPage->getTable().".obgoods_id","=",$baseObGoods->getTable().".id")->
                         where("uid",$backGoodsInfo['uid'])->where($baseObGoods->getTable() .".gtype",$backGoodsInfo['gtype'])->get();//获取已经使用完的道具
        if($deleteBackObj){
            $deleteBack = $deleteBackObj->toArray();
            $isUpt = 0;
            foreach($deleteBack as $deleteBack){
                if($deleteBack['bpg_over_at']>time()){//有多个VIP道具时候 其中有没过期的时候
                    $isUpt =$deleteBack['gis_vip']>$isUpt?$deleteBack['gis_vip']:$isUpt;  
                }
            }
            
           (new \App\Models\base\UserInfo())->where("uid",$backGoodsInfo['uid'])->update(array("uvip"=>$isUpt));
        }
        return TRUE;
    }
    
    /*
     * 删除保险箱道具
     */
    public function deleteStongBoxGoods($backGoodsInfo){
        $baseBackPage = new BackPage();
        $baseObGoods = new ObGoods();   
        $baseGoods  =  new Goods();
        $deleteBackObj = $baseBackPage->leftjoin($baseObGoods->getTable(),$baseBackPage->getTable().".obgoods_id","=",$baseObGoods->getTable().".id")->
                         where("uid",$backGoodsInfo['uid'])->where($baseObGoods->getTable() .".gtype",$backGoodsInfo['gtype'])->get();//获取已经使用完的道具
        if($deleteBackObj){
            $deleteBack = $deleteBackObj->toArray();
            $isUpt = 0;
            foreach($deleteBack as $deleteBack){
                if($deleteBack['bpg_over_at']>time()){//有多个保险箱道具时候 其中有没过期的时候
                    $isUpt =1;
                    break;
                }
            }
            if(!$isUpt){//VIP过期修改 userInfo表里面的is_vip字段
                $strongBox = (new \App\Models\StrongBoxModel());
                $uchip = $strongBox->getStrongBoxInfo($backGoodsInfo['uid']);//获取用户保险箱的金额
                $uchip &&  $strongBox->useStrongBox($backGoodsInfo['uid'],$uchip,0);//把用户保险箱的钱取出来
                (new \App\Models\base\UserInfo())->where("uid",$backGoodsInfo['uid'])->update(array("strong_box"=>0));
            }
        }
        return true;
    }
    
    
    /*
     * 添加实物道具表
     */
    public function addUserGoodsAddress($backGoodsInfo){
        $ugaModel = (\App\Models\UserGoodsAddressModel());
        $insert["bp_id"] = $backGoodsInfo['bpid'];
        $insert["obg_id"] = $backGoodsInfo['obgoods_id'];
        $insert["uid"] = $backGoodsInfo['uid'];
        $insert["send_status"] = 0;
        $insert["num"] = $backGoodsInfo['num'];
        $insert["created_at"] = date("Y-m-d H:i:s");
        $ugaModel->insert($insert);
        return true;
    }
}
