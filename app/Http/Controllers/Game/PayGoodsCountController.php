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
use App\Models\Count\base\PayGoodsCount;
/**
 * Description of PayCountController
 *
 * @author 七彩P1
 */
class PayGoodsCountController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $PayGoodsCount = new PayGoodsCount;
        if($request->has("game") !=TRUE){
            $request->offsetSet("game",2);
        }
        $basePayGoodsCount = $PayGoodsCount->where("game",$request->get("game"));
        if($request->has("count_type") !=TRUE || !$request->get("count_type")){
            $request->offsetSet("count_type","3"); 
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
        
        $count = $basePayGoodsCount->orderBy("date","asc")->select("date","obgoods_id","price","goods_name","time_count_all","time_count","pop_count_all","pop_count","count_type")->get();
        $title =$nowData = $retdata = $x = $data = array();
        foreach($count as $k=>$value){
            $k = $value['date'];
            $sk =  $value["obgoods_id"];
            $data[$k][$sk] = $value;
            $title[$sk] = $value['goods_name'];
        }
        $count_type_arr = $PayGoodsCount->count_type_arr;
        $date_type = $dt['date_type'];
        return view('admin.game.paygoods.list',compact("data","title",'obuy_type_arr','count_type_arr','date_type'));
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