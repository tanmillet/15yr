<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Game\base\Order;
use App\Models\Count\base\PaySceneCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\DayCountModel;
/**
 * Description of PaySceneCountModel
 *
 * @author 七彩P1
 */
class PaySceneCountModel {
        public function arstianCount($count_type=4){
        $ret = $this->getData($count_type);
        extract($ret);
        $dayCountModel = new DayCountModel();
        $basePayCount = new PaySceneCount;
        foreach ($poCount as $data){
            $insert['pay_num'] = $data['pay_num'];
            $insert['pay_pop_num'] = $data['pay_pop_num'];
            $insert['order_num'] = $data['order_num'];
            $insert['game'] = $data['game'];
            $insert['date'] = $date;
            $insert['count_type'] = $count_type;
            
            $insert['scene_type'] =1;
            $insert['type'] = $data['order_ref'];
            $basePayCount->insert($insert);
        }
        
        foreach ($storeCount as $data){
            $insert['pay_num'] = $data['pay_num'];
            $insert['pay_pop_num'] = $data['pay_pop_num'];
            $insert['order_num'] = $data['order_num'];
            $insert['game'] = $data['game'];
            $insert['date'] = $date;
            $insert['count_type'] = $count_type;
            
            $insert['scene_type'] = 2;
            $insert['type'] = 0;
            $basePayCount->insert($insert);
        }
    }
    
    public function nowCount($count_type =1){
        return $this->getData($count_type);
    }
    
    
    
    public function getData($count_type =1){
        $baseOrder = new Order();
        switch ($count_type)
        {
            case 3://天
                $time = strtotime(date("Y-m-d"));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",$time -3600*24);
                $date = date("Ymd",strtotime($sdate));
              break;
            case 4://月
                $time = strtotime(date('Y-m'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-1 month",$time));
                $date = date("Ym",strtotime("-1 month",$time));
              break;
            case 5://年
                $time = strtotime(date('Y'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-1 year",$time));
                $date = date("Ym",strtotime("-1 year",$time));
              break;
            case 6://周
                $time = strtotime(date('Y-m-d'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-7 day",$time));
                $date = date("Ymd",strtotime("-7 day",$time));
              break;
            default:
        }
        $where["order_status"] =$baseOrder->order_status_arr['finish'];
        $poCount =  $baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)->whereNotIn("obuy_type",$baseOrder->game_buy_ref)->where("order_type","1")
                        ->select(DB::raw(""
                               . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as pay_num,"
                               . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                               . ",count(id) as order_num,game,order_ref"))
                       ->groupBy(DB::raw("game,order_ref"))
                       ->get();  
                   
        $poCount = $poCount->toArray();

        $storeCount =  $baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)->whereNotIn("obuy_type",$baseOrder->game_buy_ref)->where("order_type","1")
                        ->select(DB::raw(""
                               . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as pay_num,"
                               . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                               . ",count(id) as order_num,game,order_ref"))
                       ->groupBy(DB::raw("game"))
                       ->get();  
        $storeCount = $storeCount->toArray();                       
        
        $ret['date'] = $date;
        $ret['sdate'] = $sdate;
        $ret['fdate'] = $fdate;
        
        $ret['poCount'] = $poCount;
        $ret['storeCount'] = $storeCount;
        return $ret;
    }
    
    
    
}
