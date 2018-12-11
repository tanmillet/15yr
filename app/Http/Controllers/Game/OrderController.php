<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\Order;
use App\Models\Game\base\OrderGoods;
/**
 * Description of OrderController
 *
 * @author 七彩P1
 */
class OrderController extends BaseController{
    public function index(Request $request){
        $baseOrder = new Order;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseOrderObj = $baseOrder->where("game",$request->get("game"));
        if($request->has("pfid") ==TRUE && $request->get("pfid")){
            $baseOrderObj = $baseOrderObj->where("pfid",$request->get("pfid"));
        }
        if($request->has("usid") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("game",$request->get("usid"));
        }
        $time = time();
        if($request->has("sdate") !=TRUE){
           $request->offsetSet("sdate","2018-04-01 00:00:00"); 
        }
        $baseOrderObj = $baseOrderObj->where("created_at",">=",$request->get("sdate"));
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i:s")); 
        }
        $baseOrderObj = $baseOrderObj->where("created_at","<=",$request->get("fdate"));
        
        if($request->has("obuy_type") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("obuy_type",$request->get("obuy_type"));
        }
        if($request->has("obuy_ref") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("obuy_ref",$request->get("obuy_ref"));
        }
        if($request->has("order_status") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("order_status",$request->get("order_status"));
        }
        if($request->has("pfid") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("pfid",$request->get("pfid"));
        }
        
        if($request->has("order") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("order",$request->get("order"));
        }
        
        if($request->has("uid") ==TRUE){
            $baseOrderObj = $baseOrderObj->where("uid",$request->get("uid"));
        }
        
        $order_status_arr = $baseOrder->order_status_arr;
        $order_status_name_arr = $baseOrder->order_status_name_arr;
        $obuy_ref_arr = $baseOrder->obuy_ref_arr;
        $obuy_type_arr = $baseOrder->obuy_type_arr;
        
        $pager = $baseOrderObj->orderBy('created_at','desc')->paginate();

        return view('admin.game.order.list',compact('pager','obuy_type_arr','obuy_ref_arr','order_status_arr','order_status_name_arr'));
    }
    
    
    public function show($id){
        $baseOrder = new Order;
        $item = $baseOrder->where("id",$id)->first();
        
        $baseOrderGoods = new OrderGoods();
        $orderGoodsInfo = $baseOrderGoods->where("order_id",$id)->get();
        
        $order_status_arr = $baseOrder->order_status_arr;
        $order_status_name_arr = $baseOrder->order_status_name_arr;
        $obuy_ref_arr = $baseOrder->obuy_ref_arr;
        $obuy_type_arr = $baseOrder->obuy_type_arr;
        $send_status_arr = $baseOrderGoods->send_status_arr;
       
        return view('admin.game.order.show',compact('item','obuy_type_arr','obuy_ref_arr','order_status_arr','order_status_name_arr','orderGoodsInfo','send_status_arr'));
    }
}
