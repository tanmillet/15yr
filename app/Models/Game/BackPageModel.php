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
use App\Models\Game\base\ObGoods;
use Illuminate\Support\Facades\DB;
use App\Models\UseGoodsModel;
use App\Models\Game\EmailModel;
use App\Models\Game\base\UserGetGoodsLog;
/**
 * Description of BackPageModel
 *
 * @author 七彩P1
 */
class BackPageModel extends Model{
    //put your code here
    /*
     * 根据道具列表详细情况变更用户背包道具
     * uid 用户id  goodsInfo道具数组 changeStatus变更状态0是添加1是减少 ref变更来源 isUse 是否使用 0不适用 1使用
     * useRef使用来源 使用数量 retFlag 返回的模式
     */
    public function changeUserBackPage($uid,$goodsInfo,$ref=1,$isUse=0,$useRef=0,$usenum=1,$retFlag =0){
        //获取用户
        $baseBackPage = new BackPage();
        $baseGoods = new Goods();
        //查到订单背包道具共享表是否有该道具没有添加
        $obGoods = (new ObGoods())->getInsert($goodsInfo,$ref);
        $ifAttr["obgoods_id"] =$obGoods->id;
        $ifAttr["uid"] =$uid;
        //$num * $goods[$goodsId]['use_number']
        
        $num = isset($goodsInfo['ognumber'])?$goodsInfo['ognumber']:0;
        $uptAttr['bpg_num'] = DB::raw("bpg_num +".$num);//道具数量
        $backPageObj = $baseBackPage->updateOrCreate($ifAttr,$uptAttr);
        $useRet = "";
        $search_ref = isset($goodsInfo['search_ref'])?$goodsInfo['search_ref']:0;
        if($isUse){
            if(!in_array($obGoods->gvalid_time_type, $baseGoods->noDirectType)){//是否 立即使用
                $useRet = (new UseGoodsModel())->UseGoods($uid,$backPageObj->id,$useRef,$usenum,$search_ref);
            }
        }
        $goodsInfo['email_content'] && (new EmailModel())->sendEmail($uid, $goodsInfo['email_content'] ."*".$num ."份");
        $insert["uid"] = $uid;
        $insert["created_at"] = date("Y-m-d H:i:s");
        $insert["get_num"] = $num;
        $insert["ob_goods_id"] = $obGoods->id;
        $insert["goods_id"] = $obGoods->goods_id;
        $insert["ref"] = $useRef;
        $insert["search_ref"] = $search_ref;
        $insert["game"] = $obGoods->game;
        $insert["search_ref_sid"] = isset($goodsInfo['search_ref_sid'])?$goodsInfo['search_ref_sid']:0;
        
        (new UserGetGoodsLog)->insert($insert);
        if($retFlag){
            return  ["bpObj"=>$backPageObj,"useRet" => $useRet];
        }
        return $backPageObj;
    }
    
    
    /*
     * 根据道具列表详细情况变更用户背包道具
     * uid 用户id  goodsInfo道具数组 changeStatus变更状态0是添加1是减少 ref变更来源 isUse 是否使用 0不适用 1使用
     * useRef使用来源 使用数量 retFlag 返回的模式
     */
    public function ObgChangeUserBackPage($uid,$obGoodsInfo,$ref=1,$isUse=0,$useRef=0,$usenum=1,$retFlag =0){
        //获取用户
        $baseBackPage = new BackPage();
        $baseGoods = new Goods();
        //查到订单背包道具共享表是否有该道具没有添加
        $ifAttr["obgoods_id"] =$obGoodsInfo['id'];
        $ifAttr["uid"] =$uid;
        $num = isset($obGoodsInfo['ognumber'])?$obGoodsInfo['ognumber']:0;
        $uptAttr['bpg_num'] = DB::raw("bpg_num +".$num);//道具数量
        $backPageObj = $baseBackPage->updateOrCreate($ifAttr,$uptAttr);
        $useRet = "";
        if($isUse){
            if(!in_array($obGoodsInfo['gvalid_time_type'], $baseGoods->noDirectType)){//是否 立即使用
                $useRet = (new UseGoodsModel())->UseGoods($uid,$backPageObj->id,$useRef,$usenum);
            }
        }
        if($retFlag){
            return  ["bpObj"=>$backPageObj,"useRet" => $useRet];
        }
        return $backPageObj;
    }
    
    /*
     * 删除背包过期道具
     */
    public function deleteTimeOutGoods(){
        $baseBackPage = new BackPage();
        $baseObGoods = new ObGoods();   
        $baseGoods  =  new Goods();
        $deleteBackObj = $baseBackPage->leftjoin($baseObGoods->getTable(),$baseBackPage->getTable().".obgoods_id","=",$baseObGoods->getTable().".id")->
                         where("bpg_num",0)->where("bpg_over_at","<", time())->get();//获取已经使用完的道具
        if(!$deleteBackObj){
           return  TRUE;
        }
        $deleteBack = $deleteBackObj->toArray();
        
        foreach($deleteBack as $backPackInfo){
            $uid = $backPackInfo['uid'];
            \App\Log\Facades\Logger::info("用户id".$uid."删除道具". json_encode($deleteBack),"deleteBackPack");
            if(array_key_exists($backPackInfo['gtype'], $baseGoods->deleteGoodsCallBack)){
                \App\Log\Facades\Logger::info("用户id".$uid."删除道具回调操作方法". json_encode($baseGoods->useGoodsCallBack[$backPackInfo['gtype']]),"deleteBackPack");
                $class =$baseGoods->deleteGoodsCallBack[$backPackInfo['gtype']]['class'];
                $model =$baseGoods->deleteGoodsCallBack[$backPackInfo['gtype']]['method'];
                $ret = call_user_func(function($data)use(&$class,&$model){
                    try {
                        if($model){
                           $classAtrr = new $class(); 
                           $classAtrr->{$model}($data);
                        }else{
                            $classAtrr = new $class($data);
                        }
                    } catch (\Exception $e) {
                       \App\Log\Facades\Logger::info("用户id".$uid."添加使用道具回调操作方法失败".$e->getMessage()."数据".$data,"deleteBackPack");
                    }
                },$backPackInfo);
                \App\Log\Facades\Logger::info("用户id".$uid."添加使用道具回调操作方法返回结果".  json_encode($ret),"deleteBackPack");
            }
        }
        $baseBackPage->where("bpg_num",0)->where("bpg_over_at","<", time())->delete();
    }
    
    
    //获取VIP道具剩余天数
    public function getUserVipDay($uid){
        $day = 0;
        $baseObGoods = new ObGoods();
        $baseBackPage = new BackPage();
        $obTable = $baseObGoods->getTable();
        $bpTable = $baseBackPage->getTable();
        //获取出VIP道具
        $vipGoodsObj =  $baseBackPage->leftjoin($obTable,$bpTable.".obgoods_id",$obTable.".id")
                     ->where($bpTable .".uid",$uid)
                     ->where($obTable .".gis_vip",">","0")
                     ->get();
        if(!$vipGoodsObj){
            return $day;
        }
        $vipGoods = $vipGoodsObj->toArray();
      
        foreach($vipGoods as $goods){//如有道具VIP 取最长时间的返回
            $useTime = floor(($goods['bpg_over_at'] -  $goods['bpg_use_at'])/(24*3600));
            $useTime >$day && $day = $useTime;
        }
        return $day;
    }
    
    
    /*
     * 获取用户背包道具
     */
    public function getUserBackPack($uid,$type=0){
        $baseBackPack = new BackPage();
        $baseObGoods = new ObGoods();
        $bpTable = $baseBackPack->getTable();
        $obTable = $baseObGoods->getTable();
        $where[$bpTable .'.uid'] = $uid;
        $where[$obTable .'.bp_isshow'] = 1;//背包不显示的不发送
        if($type){
            $where[$obTable.'.bp_type'] = $type;
        }
        $bpoObj = $baseBackPack->leftjoin($obTable,$bpTable.".obgoods_id","=",$obTable.".id")
               ->where($where)->where(
                       DB::raw("case when {$bpTable}.bpg_over_at >= ".time() ." then '1'".
                               " when {$bpTable}.bpg_num >= 1 then '1' else '0' end "
                               ), ">",0)
                ->select(DB::raw($bpTable . ".id as bpId,".$bpTable.".*,".$obTable.".*"))
                ->get(); 
        if($bpoObj){
            $ret = $bpoObj->toArray();
        }
        return $ret;
    }
    
    /*
     *获取记牌器
     */
    public function getCardDay($uid,$game=1){
        $baseBackPack = new BackPage();
        $baseObGoods = new ObGoods();
        $bpTable = $baseBackPack->getTable();
        $obTable = $baseObGoods->getTable();
        //获取记牌器的 限时时间
        $time = 0;
        $bpoObj = $baseBackPack->leftjoin($obTable,$bpTable.".obgoods_id","=",$obTable.".id")
               ->where($bpTable.".uid","=",$uid)
               ->where($obTable.".game","=",$game)
               ->where($obTable.".gtype","=",11)
              ->where($obTable.".gvalid_time_type","=",4)
               ->where($bpTable.".bpg_over_at",">",time()) 
                ->select(DB::raw($bpTable . ".id as bpId,".$bpTable.".*,".$obTable.".*"))->first(); 
        if($bpoObj){
            $bpo = $bpoObj->toArray();
            $time = ($bpo['bpg_over_at']-time())>0?$bpo['bpg_over_at']-time():0;
        }
        return $time;
    }

    
    //根据道具ID 获取用户的背包剩余量
    public function UseGoodsIdGetBpInfo($uid,$goods_id){
        $ret = ["status"=>FALSE,"error"=>"","data"=>[]];
        $where['id']= $goods_id;
        $goodsInfo = (new Goods())->where($where)->first();
        if(!$goodsInfo){
            return $ret;
        }
        $goodsInfo = $goodsInfo->toArray();
        $ObGoods = (new ObGoods())->GetObId($goodsInfo);
        if(!$ObGoods){
            $ret["goods_info"] = $goodsInfo;
            return $ret;
        }
        $bwhere['uid'] = $uid;
        $bwhere['obgoods_id'] = $ObGoods['id'];
        $backPage = (new BackPage())->where($bwhere)->first();//获取用户背包信息
        
        if($backPage){
            $backPage = $backPage->toArray();
            $ret = ["status"=>TRUE,"error"=>"","data"=>$backPage,"goods_info"=>$goodsInfo,"ob_goods_info"=>$ObGoods];
        }
        $ret["goods_info"] = $goodsInfo;
        $ret["ob_goods_info"] = $ObGoods;
        return $ret;
    }
}
