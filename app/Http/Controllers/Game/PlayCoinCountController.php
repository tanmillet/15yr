<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Count\base\PlayCoinCount;
/**
 * Description of PlayCoinCountController
 *
 * @author 七彩P1
 */
class PlayCoinCountController extends BaseController{
    //put your code here
    public function  index(Request $request){
        $baseCount = new PlayCoinCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseCountInfo = $baseCount->where("game","=",$request->get("game"));
        if($request->has("sdate") !=TRUE){
           $sdate =  date("Y-m-d 00:00",strtotime("-7 day"));
           $request->offsetSet("sdate",$sdate); 
        }
        if($request->has("play_type") !=TRUE){
           $request->offsetSet("play_type",1); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i")); 
        }
        
        $baseCountInfo = $baseCountInfo->where("play_type",$request->get("play_type"));
        $baseCountInfo = $baseCountInfo->where("date","<=",date("Ymd",strtotime($request->get("fdate"))));
        $baseCountInfo = $baseCountInfo->where("date",">=",date("Ymd",strtotime($request->get("sdate"))));
        
        $baseCountInfoObj =$baseCountInfo->orderBy("date","desc")->get();
        foreach($baseCountInfoObj as $value){
            $date = $value["date"];
            $data[$date][$value['room_type']][$value["count_type"]] = $value["num"];
        }
        $dateData = $this->makeDate($request->get("sdate"), $request->get("fdate"));
        
        $count_type_arr = $baseCount->count_type_arr;
        $room_type_arr = $baseCount->room_type_arr;
        
        $showplaygamearr = $baseCount->showgametype[$request->get("game")];
        return view('admin.game.playcoincount.list')->with("data",$data)->with("count_type_arr",$count_type_arr)
                ->with("room_type_arr",$room_type_arr)->with("dateData",$dateData)->with("showplaygamearr",$showplaygamearr);
    }
    
        //设置日期
    public function makeDate($sdate,$fdate){
        $stime = strtotime($sdate);
        $ftime = strtotime($fdate);
        $i = 3600;
        $retDate = array();
        for($j=$ftime;$j>$stime;$j-=$i){
            $k = date("Ymd",$j);
            $retDate[$k] =  array();
        }
        return $retDate;
    }
}
