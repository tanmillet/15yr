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
use App\Models\Count\base\PaySceneCount;
use App\Models\Count\PaySceneCountModel;
/**
 * Description of PayCountController
 *
 * @author 七彩P1
 */
class PaySceneCountController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $PaySceneCount = new PaySceneCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $basePayGoodsCount = $PaySceneCount->where("game",$request->get("game"));
        if($request->has("count_type") !=TRUE || !$request->get("count_type")){
            $request->offsetSet("count_type","4"); 
        }
         $basePayGoodsCount = $basePayGoodsCount->where("count_type",$request->get("count_type"));
         
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
        $basePayGoodsCount = $basePayGoodsCount->where( "date",">=",$dt['sdate']);
        $basePayGoodsCount = $basePayGoodsCount->where( "date","<=",$dt['fdate']);
        
        $count = $basePayGoodsCount->orderBy("date","asc")->select("date","game","pay_num","pay_pop_num","count_type","order_num","scene_type","type")->get();
        $title = $title =$nowData = $retdata = $x = $data = array();
      //  dd($count);
        $type_arr = $PaySceneCount->type_arr;
        $count && $count = $count->toArray();
        foreach($count as $k=>$value){
            $k = $value['date'];
            $sk =  $value["type"];
            $data[$k][$value['scene_type']][$sk] = $value;
            if(!isset($title[$sk])  ){
               $title[$sk] = isset($type_arr[$sk])?$type_arr[$sk]:$sk;
            }
        }
        $count_type_arr = $PaySceneCount->count_type_arr;
        $date_type = $dt['date_type'];
        return view('admin.game.payscene.list',compact("data",'count_type_arr','date_type','type_arr',"title"));
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
            case 5:
                $date_type="yyyy";
                $sdate = date("Y0101000000",strtotime($sdate));
                $fdate = date("Y0101000000",strtotime($fdate));
                $rsdate = date("Y",strtotime($sdate));
                $rfdate  = date("Y",strtotime($fdate));
                $show_sdate = date("Y",strtotime($sdate));
                $show_fdate  = date("Y",strtotime($fdate));      
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

    
    


}