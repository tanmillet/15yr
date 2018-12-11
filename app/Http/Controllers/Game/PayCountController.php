<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

use App\Models\Count\base\PayCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\PayCountModel;
/**
 * Description of PayCountController
 *
 * @author 七彩P1
 */
class PayCountController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $PayCount = new PayCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $basePayCount = $PayCount->where("game",$request->get("game"));
        if($request->has("pfid") ==TRUE){
            $basePayCount = $basePayCount->where("pfid",$request->get("pfid"));
        }
        if($request->has("usid") ==TRUE){
            $basePayCount = $basePayCount->where("usid",$request->get("usid"));
        }
        if($request->has("count_type") !=TRUE || !$request->get("count_type")){
            $request->offsetSet("count_type","1"); 
        }
         $basePayCount = $basePayCount->where("count_type",$request->get("count_type"));
         
        $time = time();
        if($request->has("sdate") !=TRUE){
           $stime =$time-8*3600;
           $sdate =  date("Y-m-d H:i",$stime);
           $request->offsetSet("sdate",date("Y-m-d H:i",$stime)); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i",$time)); 
        }
        $dt =    $this->getDate($request->get("count_type"),$request->get("sdate"),$request->get("fdate"));
        $request->offsetSet("sdate",$dt['show_sdate']); 
        $request->offsetSet("fdate",$dt['show_fdate']); 
        
        $basePayCount = $basePayCount->where( DB::raw("CONCAT(date,hour_min)"),">=",$dt['sdate'].$dt['shour_min']);
        $basePayCount = $basePayCount->where( DB::raw("CONCAT(date,hour_min)"),"<=",$dt['fdate'].$dt['fhour_min']);
        
        if($request->has("obuy_type") ==TRUE && $request->get("obuy_type")){
            $basePayCount = $basePayCount->where("obuy_type",$request->get("obuy_type"));
        }
        

           
        if($request->has("data_type") !=TRUE || !$request->get("data_type")){
            $request->offsetSet("data_type","pay_price"); 
        }
        $data_type = $request->get("data_type");
        
        $date_group_flag = 0;
        if($request->has("date_group") ==TRUE && $request->get("date_group")){
            $date_group_flag = 1;
            $basePayCount = $basePayCount->groupBy("date","hour_min");
            $count = $basePayCount->orderBy("date","asc")->orderBy("hour_min","asc")->select(
                        DB::raw('SUM('.$data_type.') as num'),"date","hour_min",
                            DB::raw('SUM(new_pay_pop_num) as new_pay_pop_num,'
                                    . 'SUM(pay_num) as pay_num,'
                                    . 'SUM(pay_pop_num) as pay_pop_num,'
                                    . 'SUM(pay_price) as pay_price,'
                                     . 'SUM(order_num) as order_num,'
                                   ),"game","pfid","usid","obuy_type"
                    )->get();
        }else{
            $count = $basePayCount->orderBy("date","asc")->orderBy("hour_min","asc")->select(DB::raw($data_type.' as num'),"date","hour_min","new_pay_pop_num","pay_num","pay_pop_num","pay_price","order_num","game","pfid","usid","obuy_type")->get();
        }
        $nowData = $retdata = $x = $data = array();
        foreach($count as $k=>$value){
            $k = $date_group_flag?$value['date']:0;
            $sk =  $date_group_flag?date("H:i",strtotime($value['hour_min'])):date("Y-m-d H:i", strtotime(($value['date'].$value['hour_min'])));
            $data[$k][$sk] = (int)$value['num'];
            if(!isset($x[$sk])){
                $x[$sk] = 0;
            }
        }
        ksort($x);
        $i=0;
        foreach($data as  $date=>$v){
            $diff = array_diff_key($x, $v);
            foreach($diff as $k=>$a){
                $data[$date][$k] = 0;
            }
            ksort($data[$date]);
            $retdata[$i]['name'] = $date;
            $retdata[$i]['data'] = array_values($data[$date]);
            $i++;
        }
        
        $date_group = ["正常曲线","1"=>"时间对比"];
        $x =  array_keys($x);
        
        $obuy_type_arr = (new \App\Models\Game\base\Order())->obuy_type_arr;
        $count_type_arr = $PayCount->count_type_arr;
        $data_type_arr = $PayCount->data_type_arr;
        $date_type = $dt['date_type'];
        $count = $count->toArray();
        return view('admin.game.paycount.list',compact('item',"date_group",'obuy_type_arr','count_type_arr','data_type_arr','date_type','count'));
    }
    
    
    public function getDate($count_type,$sdate,$fdate){
        
        switch ($count_type){
            case 1:
                $date_type="yyyy-mm-dd hh:ii";
                $sdate = date("YmdHi00",strtotime($sdate));
                $fdate = date("YmdHi00",strtotime($fdate));
                $rsdate = date("YmdHi",strtotime($sdate));
                $rfdate  = date("YmdHi",strtotime($fdate));
                $show_sdate = date("Y-m-d H:i",strtotime($sdate));
                $show_fdate  = date("Y-m-d H:i",strtotime($fdate));                
                break;
            case 2:
                $date_type="yyyy-mm-dd hh";
                $sdate = date("YmdH0000",strtotime($sdate));
                $fdate = date("YmdH0000",strtotime($fdate));
                $rsdate = date("YmdH",strtotime($sdate));
                $rfdate  = date("YmdH",strtotime($fdate));  
                $show_sdate = date("Y-m-d H",strtotime($sdate));
                $show_fdate  = date("Y-m-d H",strtotime($fdate));      
                break;
            case 3:
                $date_type="yyyy-mm-dd";
                $sdate = date("Ymd000000",strtotime($sdate));
                $fdate = date("Ymd000000",strtotime($fdate));
                $rsdate = date("Ymd",strtotime($sdate));
                $rfdate  = date("Ymd",strtotime($fdate));   
                $show_sdate = date("Y-m-d",strtotime($sdate));
                $show_fdate  = date("Y-m-d",strtotime($fdate));      
                break;
            case 4:
                $date_type="yyyy-mm";
                $sdate = date("Ym01000000",strtotime($sdate));
                $fdate = date("Ym01000000",strtotime($fdate));
                $rsdate = date("Ym",strtotime($sdate));
                $rfdate  = date("Ym",strtotime($fdate));
                $show_sdate = date("Y-m",strtotime($sdate));
                $show_fdate  = date("Y-m",strtotime($fdate));      
                break;
            case 5:
                $date_type="yyyy";
                $sdate = date("Y0101000000",strtotime($sdate));
                $fdate = date("Y0101000000",strtotime($fdate));
                $rsdate = date("Y",strtotime($sdate));
                $rfdate  = date("Y",strtotime($fdate));
                $show_sdate = date("Y",strtotime($sdate));
                $show_fdate  = date("Y",strtotime($fdate));      
                break;
            default:
                $date_type="yyyy-mm-dd hh:ii";
        }
        $ret['date_type'] = $date_type;
        $ret['sdate'] = $rsdate;
        $ret['fdate'] = $rfdate;
        $ret['shour_min']= date("Hi",strtotime($sdate));
        $ret['fhour_min']= date("Hi",strtotime($fdate));
        $ret['show_sdate']= $show_sdate;
        $ret['show_fdate']= $show_fdate;
        return $ret;
    }

    
    
    //获取当天事实 数据
    public function nowData(Request $request){
        $time = time();
        $baseOrder = new \App\Models\Game\base\Order;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        if($request->has("sdate") !=TRUE){
           $stime = strtotime(date("Y-m-d",$time));
           $request->offsetSet("sdate",date("Y-m-d H:i:s",$stime)); 
        }
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i:s",$time)); 
        }
        
       
        
        $sdate = $request->get("sdate");
        $fdate = $request->get("fdate");
        $where["order_status"] =$baseOrder->order_status_arr['finish'];
        $basePayCount =$baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)->where("game",$request->get("game"))
                //->select(DB::raw("sum(price_all) as pay_price ,count(id) as pay_num,count(is_old_pay) as new_pay_pop_num,count(DISTINCT uid ) as pay_pop_num"
                 ->select(DB::raw(""
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then price_all else 0 end ) as pay_price,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as pay_num,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then is_old_pay else 0 end ) as new_pay_pop_num,"
                        . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                        . ",count(id) as order_num,game,pfid,usid,obuy_type"));
                
        if($request->has("obuy_type") ==TRUE && $request->get("obuy_type")){
            $basePayCount = $basePayCount->where("obuy_type",$request->get("obuy_type"))->groupBy("game","pfid","usid");
        }else{
            $basePayCount = $basePayCount->groupBy("game","pfid","usid","obuy_type");
        }        
        $payCount =  $basePayCount ->get();  
        $payCount = $payCount->toArray();
        
        $obuy_type_arr = (new \App\Models\Game\base\Order())->obuy_type_arr;
        
        return view('admin.game.paycount.nowlist',compact('obuy_type_arr','payCount'));
    }
    
    
    //付费概况
    public function payProfile(){
        (new \App\Models\Count\PayProfileCountModel)->arstianCount();
        echo 123;exit;
    }

    //付费排行榜
   public function payRank(Request $request){
       $time = time();
        $baseOrder = new \App\Models\Game\base\Order;
        $oTable = $baseOrder->getTable();
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        if($request->has("sdate") !=TRUE){
           $stime = strtotime(date("Y-m-d",$time));
           $request->offsetSet("sdate",date("Y-m-d H:i:s",$stime)); 
        }
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i:s",$time)); 
        }
        
       
        $baseUserInfo = new \App\Models\Game\base\userInfo;
        $uiTable = $baseUserInfo->getTable();
        $baseUserGame = new \App\Models\Game\base\userGame;
        $ugTable = $baseUserGame->getTable();
        
        $sdate = $request->get("sdate");
        $fdate = $request->get("fdate");
        $where[$oTable.".order_status"] =$baseOrder->order_status_arr['finish'];
        $basePayCount =$baseOrder->leftJoin($uiTable,"{$uiTable}.uid","=","{$oTable}.uid")
                                ->leftJoin($ugTable,"{$ugTable}.uid","=","{$oTable}.uid")
                ->where($oTable.".created_at",">=",$sdate)->where($oTable.".created_at","<",$fdate)->where($oTable.".game",$request->get("game"))->where($where)
                ->whereNotIn("obuy_type",$baseOrder->game_buy_ref)
                 ->select(DB::raw(""   
                        . "sum(  {$oTable}.price_all  ) as pay_price,"
                        . "count( {$oTable}.order  ) as pay_count,"
                        . "{$oTable}.game,{$oTable}.uid,{$uiTable}.urtime,{$uiTable}.uname,{$ugTable}.udiamond,{$ugTable}.uchip,{$ugTable}.utombola,{$ugTable}.lasttime"))
                ->groupBy($oTable.".uid")->orderBy("pay_price","desc")->limit(50);
                
    
        $basePayCount =  $basePayCount ->get();  
        $payCount = $basePayCount->toArray();
        return view('admin.game.paycount.payrank_list',compact('payCount'));
   }
}