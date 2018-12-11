<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use App\Models\Count\base\ActiveCount;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
/**
 * Description of ActiveController
 *
 * @author 七彩P1
 */
class ActiveController extends BaseController{
    //put your code here
    public function index(Request $request){
        $baseCount = new ActiveCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseComCount = $baseCount->where("game",$request->get("game"));
 

         
        $time = time();
        if($request->has("sdate") !=TRUE){
           $stime =$time-8*3600*24;
           $sdate =  date("Y-m-d",$stime);
           $request->offsetSet("sdate",$sdate); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d",$time)); 
        }
        if($request->has("active_type") !=TRUE){
           $request->offsetSet("active_type",17); 
        }
        $baseComCount = $baseComCount->where( "active_type",$request->get("active_type"));
        $baseComCount = $baseComCount->where( "date",">=",$request->get("sdate"));
        $baseComCount = $baseComCount->where( "date","<=",$request->get("fdate"));
        
        
        $dataObj = $baseComCount->orderBy("date","desc")->get();
        $data = $dataObj->toArray();
        $ret_data = array();
        foreach($data as $values){
            $ret_data[$values["date"]][$values['type']]["num"] = $values['num'];
            $ret_data[$values["date"]][$values['type']]["pop_num"] = $values['pop_num'];
        }
        $active_type_arr = $baseCount->active_type_arr;
        $type_arr_all = $baseCount->type_arr;
        $type_arr = $type_arr_all[$request->get("active_type")];
        return view('admin.game.activecount.list',compact('ret_data','active_type_arr','type_arr'));
    }
}
