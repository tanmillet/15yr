<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

use App\Models\Count\base\PayProfileCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\PayCountModel;
/**
 * Description of PayCountController
 *
 * @author 七彩P1
 */
class PayProfileCountController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $PayCount = new PayProfileCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $basePayCount = $PayCount->where("game",$request->get("game"));
        if($request->has("pfid") ==TRUE){
            $basePayCount = $basePayCount->where("pfid",$request->get("pfid"));
        }
        if($request->has("usid") ==TRUE){
            $basePayCount = $basePayCount->where("game",$request->get("usid"));
        }
        if($request->has("count_type") !=TRUE || !$request->get("count_type")){
            $request->offsetSet("count_type","3"); 
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
        
        
        if($request->has("obuy_type") ==TRUE && $request->get("obuy_type")){
            $basePayCount = $basePayCount->where("obuy_type",$request->get("obuy_type"));
        }
        
         if($request->has("pfid") ==TRUE && $request->get("pfid")){
            $basePayCount = $basePayCount->where("pfid",$request->get("pfid"));
        }
        if($request->has("obuy_ref") ==TRUE && $request->get("obuy_ref")){
            $basePayCount = $basePayCount->where("obuy_ref",$request->get("obuy_ref"));
        }  
        
        if($request->has("data_type") !=TRUE || !$request->get("data_type")){
            $request->offsetSet("data_type","pay_price"); 
        }
        $data_type = $request->get("data_type");
        
        $date_group_flag = 0;
        if($request->has("date_group") ==TRUE && $request->get("date_group")){
            $date_group_flag = 1;
            $basePayCount = $basePayCount->groupBy("date");
            $count = $basePayCount->orderBy("date","asc")->select(
                        DB::raw('SUM('.$data_type.') as num'),"date",
                            DB::raw('SUM(new_pay_pop_num) as new_pay_pop_num,'
                                    . 'SUM(pay_num) as pay_num,'
                                    . 'SUM(pay_pop_num) as pay_pop_num,'
                                    . 'SUM(pay_price) as pay_price,'
                                     . 'SUM(order_num) as order_num,'
                                     . 'SUM(active_use_cout) as active_use_cout,'
                                   ),"game","pfid","obuy_ref"
                    )->get();
        }else{
            $count = $basePayCount->orderBy("date","asc")->select(DB::raw($data_type.' as num'),"date","new_pay_pop_num","pay_num","pay_pop_num","pay_price","order_num","game","active_use_cout","pfid","obuy_ref")->get();
        }
        
        $nowData = $retdata = $x = $data = array();
        foreach($count as $k=>$value){
            $k = $date_group_flag?$value['date']:0;
            $sk =  $date_group_flag?0:0;
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
        $obuy_ref_arr  = (new \App\Models\Game\base\Order())->obuy_ref_arr;
        $count_type_arr = $PayCount->count_type_arr;
        $data_type_arr = $PayCount->data_type_arr;
        $date_type = $dt['date_type'];
        $count = $count->toArray();
        return view('admin.game.payprofile.list',compact('item',"date_group",'obuy_type_arr','count_type_arr','data_type_arr','date_type','count','obuy_ref_arr'));
    }
    
    
    public function getDate($count_type,$sdate,$fdate){
        
        switch ($count_type){

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
            case 6:
                $date_type="yyyy-mm-dd";
                $sdate = date("Ymd000000",strtotime($sdate));
                $fdate = date("Ymd000000",strtotime($fdate));
                $rsdate = date("Ymd",strtotime($sdate));
                $rfdate  = date("Ymd",strtotime($fdate));   
                $show_sdate = date("Y-m-d",strtotime($sdate));
                $show_fdate  = date("Y-m-d",strtotime($fdate));         
                break;
            default:
                $date_type="yyyy-mm-dd hh:ii";
        }
        $ret['date_type'] = $date_type;
        $ret['sdate'] = $rsdate;
        $ret['fdate'] = $rfdate;

        $ret['show_sdate']= $show_sdate;
        $ret['show_fdate']= $show_fdate;
        return $ret;
    }

    
    
            
    
    
    //付费概况
    public function payProfile(){
        (new \App\Models\Count\PayProfileCountModel)->arstianCount();
        echo 123;exit;
    }

}