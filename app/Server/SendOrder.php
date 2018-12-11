<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;
use App\Models\OrderModel;
use App\Models\base\Order;
use App\Log\Facades\Logger;
use App\Models\base\OrderGoods;
use App\Models\BackPageModel;
use Illuminate\Support\Facades\DB;
use App\Models\base\Goods;
use App\Models\UseGoodsModel;
/**
 * Description of SendOrder
 *
 * @author 七彩P1
 */
class SendOrder {
    /*
     * 根据订单完成发货流程
     */
    public function sendOrder($order=0) {
        $backPageModel = new BackPageModel();
        $orderModel = new OrderModel();
        $baseOrder  = new Order();
        $baseOrderGoods  = new OrderGoods();
        $baseGoods = new Goods();
        
        //获取支付成功的还没有开始发货的订单出来
        Logger::info("支付发货开始","sendOrder");
        $owhere["order_status"] =$baseOrder->order_status['payed'];
        $order && $owhere['order'] = $order;
        $orderInfoObj =$baseOrder->where($owhere)->orderBy('pay_at', 'ASC')->limit(50)->get();
        if(!$orderInfoObj){
            Logger::info("未查找到发货订单","sendOrder");
            return true;
        }
        $orderInfos = $orderInfoObj->toArray();
        foreach($orderInfos as $orderInfo){
            Logger::info("获取订单信息". json_encode($orderInfo),"sendOrder");
            $order = $orderInfo['order'];
            $oid = $orderInfo['id'];
            $uid = $orderInfo['uid'];
            $owhere['order'] = $order;
            
            //获取未发货道具信息
            $gwhere["order_id"]= $oid;
            $gwhere["send_status"]= $baseOrderGoods->send_status['noSend'];
            $obGoodsTable =(new \App\Models\base\ObGoods())->getTable();


            $orderGoodsInfoObj =$baseOrderGoods->leftjoin($obGoodsTable,"obgoods_id","=",$obGoodsTable.".id")->where("order_id",$oid)->select(\Illuminate\Support\Facades\DB::raw($baseOrderGoods->getTable() .".id as order_goods_id,".$baseOrderGoods->getTable().".*,".$obGoodsTable.".*"))->get();;
            if(!$orderGoodsInfoObj){
                Logger::info("订单ID".$oid ."未查找需要发货的道具信息","sendOrder");
                return true;
            }

            $orderGoodsInfos =  $orderGoodsInfoObj->toArray();
            //修改订单状态-正在发货状态
            $uptStatus = $baseOrder->where($owhere)->update(array("order_status"=>$baseOrder->order_status['sending']));
            if(!$uptStatus){
                Logger::info("订单ID".$oid ."已在发货","sendOrder");
                return true;
            }


            DB::beginTransaction();
            try {
                foreach($orderGoodsInfos as $orderGoodsInfo){
                    //修改订单道具状态-正在发货状态
                    $oogwhere['id'] = $orderGoodsInfo['order_goods_id'];

                    $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sending'],"send_at"=>date("Y-m-d H:i:s")));
                    if(!$uptStatus){
                        Logger::info("订单ID".$oid."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname'] ."已在发货","sendOrder");
                        continue;
                    }

                    $backPageObj = $backPageModel->changeUserBackPage($uid,$orderGoodsInfo,3);//发送道具
                    if(!$backPageObj){
                        $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sendfail']))->first();//修改订单道具状态发货失败
                        Logger::info("订单ID".$oid ."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname']."发货失败","sendOrder");
                        continue;
                    }
                    if(!in_array($orderGoodsInfo['gvalid_time_type'], $baseGoods->noDirectType)){//是否 立即使用
                        (new useGoodsModel())->UseGoods($uid,$backPageObj->id,3);
                    }
                    Logger::info("订单ID".$oid ."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname']."已发货","sendOrder");
                    //修改订单道具状态-发货成功状态
                    $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sended'],"send_at"=>date("Y-m-d H:i:s")));
                }
                //修改订单状态-发货成功状态
                $baseOrder->where(array("id"=>$orderInfo['id']))->update(array("order_status"=>$baseOrder->order_status['finish'])); 
                DB::commit();
            } catch (\Exception $ex) {
                Logger::info("-------订单ID".$oid."已在发货失败".$ex->getMessage(),"sendOrder");
                DB::rollBack();
            }
        }
    }
    
    
    /*
     * 补发道具
     */
    public function reissueOrder(){
        $baseOrderGoods  = new OrderGoods();
        $baseOrder  = new Order();
        $baseOrderTableName  = $baseOrder->getTable();
        $baseOrderGoodsTableName  = $baseOrderGoods->getTable();
        $obGoodsTable =(new \App\Models\base\ObGoods())->getTable();
        $baseGoods = new Goods();
        $backPageModel = new BackPageModel();
        
        //获取发货失败的订单
        $ogwhere[$baseOrderGoodsTableName .'.send_status'] = $baseOrderGoods->send_status['noSend'];
        $ogwhere[$baseOrderTableName .'.order_status'] = $baseOrder->order_status['sending'];
        $orderGoodsInfoObj =$baseOrderGoods
                                ->leftjoin($baseOrderTableName,"order_id","=",$baseOrderTableName.".id")
                                ->leftjoin($obGoodsTable,"obgoods_id","=",$obGoodsTable.".id")
                                ->where($ogwhere)
                                ->where($baseOrderTableName .".pay_at","<",date("Y-m-d H:i:s",(time()-10*60)))
                                ->select(\Illuminate\Support\Facades\DB::raw("*,".$baseOrderGoodsTableName .".id as order_goods_id"))
                                ->get();
        
        if(!$orderGoodsInfoObj){
            return FALSE;
        }//判断 该订单下还有没有 未发货的
        $str =DB::raw("(select count(1) as num from order_goods  where ".$baseOrderTableName.".id = ".$baseOrderGoodsTableName.".order_id and ".$baseOrderGoodsTableName.".send_status=".$baseOrderGoods->send_status['sending'] .")");
        
        foreach($orderGoodsInfoObj->toArray() as $orderGoodsInfo){
            //修改订单道具状态-正在发货状态
            $uid = $orderGoodsInfo['uid'];
            $oid = $orderGoodsInfo['order_id'];
            
            $oogwhere['id'] = $orderGoodsInfo['order_goods_id'];
            $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sending'],"send_at"=>date("Y-m-d H:i:s")));
            if(!$uptStatus){
                Logger::info("订单ID".$oid."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname'] ."已在发货","reissueOrder");
                continue;
            }
            
            $backPageObj = $backPageModel->changeUserBackPage($orderGoodsInfo['uid'],$orderGoodsInfo,3);//发送道具
            if(!$backPageObj){
                $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sendfail']))->first();//修改订单道具状态发货失败
                Logger::info("订单ID".$oid ."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname']."发货失败","reissueOrder");
                continue;
            }
            if(!in_array($orderGoodsInfo['gvalid_time_type'], $baseGoods->noDirectType)){//是否 立即使用
                (new useGoodsModel())->UseGoods($uid,$backPageObj->id,3);
            }
                    
            Logger::info("订单ID".$oid ."道具ID".$orderGoodsInfo['obgoods_id']."名字".$orderGoodsInfo['gname']."已发货","reissueOrder");
            //修改订单道具状态-发货成功状态
            $uptStatus = $baseOrderGoods->where($oogwhere)->update(array("send_status"=>$baseOrderGoods->send_status['sended'],"send_at"=>date("Y-m-d H:i:s")));
            //修改订单状态-发货成功状态
            $baseOrder->where(array("id"=>$orderGoodsInfo['order_id']))
                    ->where($str,"=",0)
                    ->update(array("order_status"=>$baseOrder->order_status['finish']));
        }
        
    }
}
