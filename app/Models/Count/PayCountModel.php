<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Game\base\Order;
use App\Models\Game\base\OrderGoods;
use App\Models\Count\base\PayCount;
use Illuminate\Support\Facades\DB;
/**
 * Description of PayCount
 *
 * @author 七彩P1
 */
class PayCountModel {
    //put your code here
    public function arstianCount($count_type=4){
        $ret = $this->getData($count_type);
        extract($ret);
        
        $basePayCount = new PayCount;
        foreach ($payCount as $data){
            $insert['pay_price'] = $data['pay_price'];
            $insert['pay_num'] = $data['pay_num'];
            $insert['new_pay_pop_num'] = $data['new_pay_pop_num'];
            $insert['pay_pop_num'] = $data['pay_pop_num'];
            $insert['order_num'] = $data['order_num'];
            $insert['game'] = $data['game'];
            $insert['pfid'] = $data['pfid'];
            $insert['usid'] = $data['usid'];
            $insert['obuy_type'] = $data['obuy_type'];
            $insert['date'] = $date;
            $insert['hour_min'] = $hour_min;
            $insert['count_type'] = $count_type;
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
        case 1://分钟
                $time = time();
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",$time - 5*60);
                $date = date("Ymd",strtotime($sdate));
                $hour_min = date("Hi",strtotime($sdate));
              break;  
            case 2://小时
                $time = time();
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",$time -3600);
                $date = date("Ymd",strtotime($sdate));
                $hour_min = date("Hi",strtotime($sdate));
                if($hour_min=="0000"){
                    $hour_min=="2400";
                }
              break;
            case 3://天
                $time = strtotime(date("Y-m-d"));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",$time -3600*24);
                $date = date("Ymd",strtotime($sdate));
                $hour_min = "0000";
              break;
            case 4://月
                $time = strtotime(date('Y-m'));
                //$time = strtotime("2018-06");
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-1 month",$time));
                $date = date("Ym",strtotime("-1 month",$time));
                $hour_min = "0000";
              break;
            case 5://年
                $time = strtotime(date('Y'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-1 year",$time));
                $date = date("Ym",strtotime("-1 year",$time));
                $hour_min = "0000";
              break;
            case 6://周
                $time = strtotime(date('Y'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-7 day",$time));
                $date = date("Ymd",strtotime("-7 day",$time));
                $hour_min = "0000";
              break;
            default:
        }
        $where["order_status"] =$baseOrder->order_status_arr['finish'];
        $payCount =$baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)
                 ->select(DB::raw(""
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then price_all else 0 end ) as pay_price,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as pay_num,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then is_old_pay else 0 end ) as new_pay_pop_num,"
                        . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                        . ",count(id) as order_num,game,pfid,usid,obuy_type"))
                ->groupBy("game","pfid","usid","obuy_type")
                ->get();  
                   
        $payCount = $payCount->toArray();
        $ret['payCount'] = $payCount;
        $ret['date'] = $date;
        $ret['hour_min'] = $hour_min;
        return $ret;
    }
    
    
    
}
