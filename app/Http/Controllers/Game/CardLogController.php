<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use App\Models\Game\base\QclandlordTableLog;
use App\Models\Game\base\QclandlordWinlog;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
/**
 * Description of CardLogController
 *
 * @author 七彩P1
 */
class CardLogController  extends BaseController{
    //put your code here
    public function index(Request $request){
        $TableLog = new QclandlordTableLog;
        $TableWinLog = new QclandlordWinlog;
        $where = array();
        //$where["uid"] = $uid;
        
        if($request->has("sdate") !=TRUE){
           $request->offsetSet("sdate",date("Y-m-d 00:00:00",strtotime("-7 day"))); 
        }
        
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i:s")); 
        }
        
        if($request->has("gameid") !=TRUE){
           $request->offsetSet("gameid",3); 
        }

        
        $stime = strtotime($request->get("sdate"));
        $ftime = strtotime($request->get("fdate"));
        
        $cTableLog =  $TableLog->getTable();
        $cTableWinTableLog =  $TableWinLog->getTable();
        
        $pager = $TableWinLog->leftJoin($cTableLog,$cTableLog.".tlid","=",$cTableWinTableLog.".tlid")
                    ->where($cTableWinTableLog.".wltime",">=", $stime)->where($cTableWinTableLog.".wltime","<", $ftime)
                    ->where($cTableWinTableLog.".gameid","=", $request->get("gameid"))
                    ->select(DB::raw($cTableWinTableLog.".*,".$cTableLog.".*,".$cTableWinTableLog.".gameid"))
           ->orderBy('wltime','desc')->paginate(60);
        return view('admin.game.cardlog.index', compact('pager',"uid"));
    }
}
