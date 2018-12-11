<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Count\base\BwsCount;
use App\Models\Game\base\UserMatchLog;
use App\Models\Count\BwsCountModel;
use App\Models\Game\base\UserMatch;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\MacthRoomCfg;
/**
 * Description of BwsCountController
 *
 * @author 七彩P1
 */
class BwsCountController extends BaseController{
    //put your code here
    
    
    public function index(Request $request){
        $BwsCount = new BwsCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseBwsCount = $BwsCount->where("game",$request->get("game"));
 

         
        $time = time();
        if($request->has("sdate") !=TRUE){
           $stime =$time-8*3600*24;
           $sdate =  date("Y-m-d",$stime);
           $request->offsetSet("sdate",$sdate); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d",$time)); 
        }
        
        $baseBwsCount = $baseBwsCount->where( "date",">=",$request->get("sdate"));
        $baseBwsCount = $baseBwsCount->where( "date","<=",$request->get("fdate"));
        
        
        $dataObj = $baseBwsCount->get();
        $data = $dataObj->toArray();
        $ret_data = array();
        foreach($data as $values){
            $ret_data[$values["date"]][$values['ref']] = $values['num'];
        }
        

        $ref_arr = $BwsCount->ref_arr;
        return view('admin.game.bwscount.list',compact('item','ref_arr','ret_data'));
    }
    
    
    public function rankList(Request $request){
        $userMatchLog = new UserMatchLog;
        $umlTable = $userMatchLog->getTable(); 
        
        $userMatch = new UserMatch;
        $umTable = $userMatch->getTable();
        
        $userInfo = new userInfo;
        $uiTable = $userInfo->getTable();
        
        $udb = config("database.connections.".$userInfo->getConnectionName());
        $umdb =  config("database.connections.".$userMatch->getConnectionName());
        $baseUserMatchLog = $userMatchLog->leftJoin($umdb['database'].".".$umTable,$umTable.".uid","=",$umlTable.".uid")->leftJoin($udb['database'].".".$uiTable,$uiTable.".uid","=",$umlTable.".uid");
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        
        if($request->has("mrf_id") !=TRUE){
            $request->offsetSet("mrf_id",12);
        }
        $baseUserMatchLog = $baseUserMatchLog->where($umlTable.".mrf_id",$request->get("mrf_id"));
        $baseUserMatchLog = $baseUserMatchLog->where($umlTable.".game",$request->get("game"));
 

         
        if($request->has("date") !=TRUE){
           $request->offsetSet("date",date("Y-m-d")); 
        }
        $stime= strtotime($request->get("date")." 00:00:00");
        $ftime= strtotime($request->get("date")." 23:59:59");
        
        $baseUserMatchLog = $baseUserMatchLog->where( $umlTable.".created_at",">=",$stime);
        $baseUserMatchLog = $baseUserMatchLog->where( $umlTable.".created_at","<=",$ftime);
        
        
        $pager = $baseUserMatchLog->select($umlTable.".rank",$umlTable.".uid",$uiTable.".uname",$umTable.".reward_num",$umTable.".join_num")->orderBy($umlTable.".rank","asc")->paginate();
        
        
        $mrf_id_arr = (new MacthRoomCfg)->getBwsRoomId($request->get("game"));
        return view('admin.game.bwscount.ranklist',compact('pager','mrf_id_arr'));
    }
}
