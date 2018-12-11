<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
/**
 * Description of MoneyCountController
 *
 * @author 七彩P1
 */
 
use App\Models\Count\base\MoneyCountInfo;
use Illuminate\Support\Facades\DB;
use App\Models\Game\MoneyModel;
use App\Models\Count\base\BrnnMoneyCount;
class MoneyCountController  extends BaseController {
    //put your code here
    public function  index(Request $request){
        $baseMoneyCountInfo = new MoneyCountInfo;
        $moneyCode = $baseMoneyCountInfo->moneyCode();
        $countCode = $baseMoneyCountInfo->getCountCode();
        $moneyInfoType = $baseMoneyCountInfo->show_arr;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $baseMoneyCountInfo = $baseMoneyCountInfo->where("game","=",$request->get("game"));
        
        if($request->has("money_type") !=TRUE){
            $request->offsetSet("money_type",1);
        }
        $money_type = $request->get("money_type");
        if($request->has("count_type") !=TRUE){
            $request->offsetSet("count_type",3);
        }
        $baseMoneyCountInfo = $baseMoneyCountInfo->where("money_type",$request->get("money_type"));
        
        if($money_type ==1){
            $baseBrnnMoneyCount = new BrnnMoneyCount();
            $baseBrnnMoneyCountInfo = $baseBrnnMoneyCount->where("game","=",$request->get("game"));
        }
        $count_type = $request->get("count_type");
        
        if($request->has("sdate") !=TRUE){
            $count_type == 3 && $request->offsetSet("sdate",date("Y-m-d", strtotime("-1 month")));
            $count_type == 4 && $request->offsetSet("sdate",date("Y-m", strtotime("-1 month")));
            $count_type == 5 && $request->offsetSet("sdate",date("Y", strtotime("-1 year")));
        }
        
        if($request->has("fdate") !=TRUE){
            $count_type == 3 && $request->offsetSet("fdate",date("Y-m-d"));
            $count_type == 4 && $request->offsetSet("fdate",date("Y-m"));
            $count_type == 5 && $request->offsetSet("fdate",date("Y"));
        }
        
        if($count_type ==3){
            $date_type = "yyyy-mm-dd";
            $sdate = date("Ymd",strtotime($request->get("sdate")));
            $fdate = date("Ymd",strtotime($request->get("fdate")));
        
            $count = $baseMoneyCountInfo->select("*","money_count as money_count_all")->where("date",">=",$sdate)->where("date","<=",$fdate)->orderBy("date","desc")->get();
            
            /*if($money_type ==1){
                $count_two =$baseBrnnMoneyCountInfo->select(DB::raw("sum(money) as money_count_all,date,game,type"))
                    ->where("date",">=",$sdate)->where("date","<=",$fdate)
                   ->groupBy(DB::raw("game,type,date"))->orderBy("date","desc")->get();
            }*/
        }elseif ($count_type ==4) {
            $date_type = "yyyy-mm";
            $sdate = date("Ym",strtotime($request->get("sdate")));
            $fdate = date("Ym",strtotime($request->get("fdate")));
            
            $count = $baseMoneyCountInfo->groupBy(DB::raw("game,type,code,money_type,date_format(date,'%Y%m')"))
                        ->select(DB::raw("sum(money_count) as money_count_all,DATE_FORMAT(date,'%Y%m') as date,id,game,pfid,usid,code,money_count,money_type,type"))
                        ->where(DB::raw("DATE_FORMAT(date,'%Y%m')"),">=",$sdate)->where(DB::raw("DATE_FORMAT(date,'%Y%m')"),"<=",$fdate)
                        ->orderBy("date","desc")->get();
            
            /*if($money_type ==1){
                $count_two = $baseBrnnMoneyCountInfo->select(DB::raw("sum(money) as money_count_all,date,game,type"))
                    ->where(DB::raw("DATE_FORMAT(date,'%Y%m')"),">=",$sdate)->where(DB::raw("DATE_FORMAT(date,'%Y%m')"),"<=",$fdate)
                   ->groupBy(DB::raw("game,type,DATE_FORMAT(date,'%Y%m')"))->orderBy("date","desc")->get();
            }*/
        }elseif($count_type ==5){
            $date_type = "yyyy";
            $sdate = date("Y",strtotime($request->get("sdate")));
            $fdate = date("Y",strtotime($request->get("fdate")));
            
           $count =  $baseMoneyCountInfo->select(DB::raw("sum(money_count) as money_count_all,DATE_FORMAT(date,'%Y') as date,id,game,pfid,usid,code,money_count,money_type,type"))
                    ->where(DB::raw("DATE_FORMAT(date,'%Y')"),">=",$sdate)->where(DB::raw("DATE_FORMAT(date,'%Y')"),"<=",$fdate)
                   ->groupBy(DB::raw("game,type,code,money_type,DATE_FORMAT(date,'%Y')"))->orderBy("date","desc")->get();
           
           /*if($money_type ==1){
                $count_two =$baseBrnnMoneyCountInfo->select(DB::raw("sum(money) as money_count_all,date,game,type"))
                    ->where(DB::raw("DATE_FORMAT(date,'%Y')"),">=",$sdate)->where(DB::raw("DATE_FORMAT(date,'%Y')"),"<=",$fdate)
                   ->groupBy(DB::raw("game,type,DATE_FORMAT(date,'%Y')"))->orderBy("date","desc")->get();
            }*/
        }else{
            $date_type ="yyyy-mm-dd";
            $count_two = $count = array();
        }

        $count && $count = $count->toArray();
        
        /*if(isset($count_two) && $count_two){
            $count_two = $count_two->toArray();
        }*/
        $brnn_data = $now_data = array();
        foreach($count as $ke=>$value){
            $now_data[$value['date']][$value['type']][$value['code']][$value['money_type']] = $value['money_count_all'];
        }
        $count_type_arr = ["3"=>"天","4"=>"月","5"=>"年"];
        
        /*foreach($count_two as $value){
            $brnn_data[$value['date']][$value['type']] = $value['money_count_all'];
        }*/
        $data['moneyTypeName'] = (new MoneyModel())->typeName;
        $data['moneyType'] = (new MoneyModel())->type;
        //$moneyCode = config("socket.code");
        /*if($money_type ==1){
            $show_money_type_arr = (new BrnnMoneyCount)->show_money_type_arr;
        }else{
            $show_money_type_arr = array();
        }*/
        $keyData  = $this->makeDate($request->get("sdate"), $request->get("fdate"), $count_type);

        
        foreach($keyData as $date=>$valueData){
            $keyData[$date]  = isset($now_data[$date])?$now_data[$date]:array();
        }
        return view('admin.game.moneycount.list',compact("now_data","count_type_arr","type_arr","date_type","data","moneyInfoType","moneyCode","countCode","keyData"));
    }
    //设置日期
    public function makeDate($sdate,$fdate,$count_type){
        $stime = strtotime($sdate);
        $ftime = strtotime($fdate);
        if($count_type ==4){
            $i = 24*3600*30;
        }elseif($count_type ==5){
            $i = 24*3600*365;
        }else{
            $i = 3600;
        }
        $retDate = array();
        for($j=$ftime;$j>$stime;$j-=$i){
            if($count_type==4){
                $k = date("Ym",$j);
            }elseif($count_type==5){
                $k = date("Y",$j);
            }else{
                $k = date("Ymd",$j);
            }
            $retDate[$k] =  array();
        }
        return $retDate;
    }
}
