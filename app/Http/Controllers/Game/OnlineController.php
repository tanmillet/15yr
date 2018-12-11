<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Count\base\OnlineCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\OnlineCountModel;
/**
 * Description of OnlineController
 *
 * @author 七彩P1
 */
class OnlineController extends BaseController{
    //put your code here
    public function index(Request $request){
        //(new OnlineCountModel)->arstianCount();
        $baseOnlineCount = new OnlineCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseOnlineCount = $baseOnlineCount->where("game",$request->get("game"));
        if($request->has("pfid") ==TRUE){
            $baseOnlineCount = $baseOnlineCount->where("pfid",$request->get("pfid"));
        }
        if($request->has("usid") ==TRUE){
            $baseOnlineCount = $baseOnlineCount->where("game",$request->get("usid"));
        }
        $time = time();
        if($request->has("sdate") !=TRUE){
           $stime =$time-8*3600;
           $request->offsetSet("sdate",date("Y-m-d H:i",$stime)); 
           $sdate =  date("Ymd",$stime);
           $shour_min =  date("Hi",$stime);
        }else{
           $sdate =  date("Ymd", strtotime($request->get("sdate")));
           $shour_min =  date("Hi", strtotime($request->get("sdate")));
        }
        $baseOnlineCount = $baseOnlineCount->where( DB::raw("CONCAT(date,hour_min)"),">=",$sdate.$shour_min);
        //$baseOnlineCount = $baseOnlineCount->where("hour_min",">=",$shour_min);
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i",$time)); 
           $fdate =  date("Ymd",$time);
           $fhour_min =  date("Hi",$time);
        }else{
           $fdate =  date("Ymd", strtotime($request->get("fdate")));
           $fhour_min =  date("Hi", strtotime($request->get("fdate")));
        }
        $baseOnlineCount = $baseOnlineCount->where( DB::raw("CONCAT(date,hour_min)"),"<=",$fdate.$fhour_min);
        $date_group_flag = 0;
        if($request->has("date_group") ==TRUE && $request->get("date_group")){
            $date_group_flag = 1;
            $baseOnlineCount = $baseOnlineCount->groupBy("date","hour_min");
            $count = $baseOnlineCount->orderBy("date","asc")->orderBy("hour_min","asc")->select(DB::raw('SUM(user_num) as num'),"date","hour_min")->get();
        }else{
            $count = $baseOnlineCount->orderBy("date","asc")->orderBy("hour_min","asc")->select(DB::raw('user_num as num'),"date","hour_min")->get();
        }
        
        foreach($count as $k=>$value){
            $value= $value->toArray();
            $k = $date_group_flag?$value['date']:0;
            $sk =  $date_group_flag?date("H:i",strtotime($value['hour_min'])):date("Y-m-d H:i", strtotime(($value['date'].$value['hour_min'])));
            if(isset($data[$k][$sk])){
                $data[$k][$sk] += (int)$value['num'];
            }else{
                $data[$k][$sk] = (int)$value['num'];
            }
            if(!isset($x[$sk])){
                $x[$sk] = 0;
            }
        }
        if(!empty($x)){
            ksort($x);
        }
        
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
        $now_data = array();
        if(!$date_group_flag){
            $now_data =  (new OnlineCountModel)->nowCount($request->get("game"));
        }
        return view('admin.game.online.list',compact('date_group','retdata','x',"now_data"));
    }
}
