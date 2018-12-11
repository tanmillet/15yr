<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers\Game;
use App\Models\Count\base\BrnnMoneyCount;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Count\BrnnMoneyCountModel;
/**
 * Description of BrnnMoneyController
 *
 * @author 七彩P1
 */
class BrnnMoneyController  extends BaseController{
    //put your code here
    public function  index(Request $request){
        $baseCount = new BrnnMoneyCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseCountInfo = $baseCount->where("game","=",$request->get("game"));
        if($request->has("sdate") !=TRUE){
           $sdate =  date("Y-m-d 00:00",strtotime("-1 month"));
           $request->offsetSet("sdate",$sdate); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i")); 
        }
        
        $baseCountInfo = $baseCountInfo->where( DB::raw("CONCAT(date,hour_min)"),">=",date("YmdHi",strtotime($request->get("sdate"))));
        $baseCountInfo = $baseCountInfo->where( DB::raw("CONCAT(date,hour_min)"),"<=",date("YmdHi",strtotime($request->get("fdate"))));
        
        $baseCountInfoObj =$baseCountInfo->select(DB::raw("sum(money) as money_all,date,game,type"))->orderBy("date","desc")
                        ->groupBy("date")->groupBy("type")->get();
        $data = array();
        foreach($baseCountInfoObj as $value){
            $date = $value["date"];
            $data[$date][$value['type']] = $value["money_all"];
        }
        $type_arr = $baseCount->type_arr;
        return view('admin.game.brnnmoneycount.list')->with("type_arr",$type_arr)->with("data",$data);
    }
}
