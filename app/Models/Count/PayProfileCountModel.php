<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Game\base\Order;
use App\Models\Game\base\OrderGoods;
use App\Models\Count\base\PayProfileCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\DayCountModel;
/**
 * Description of PayCount
 *
 * @author 七彩P1
 */
class PayProfileCountModel {
    //put your code here
    public function arstianCount($count_type=3){
        $ret = $this->getData($count_type);
        extract($ret);
        $dayCountModel = new DayCountModel();
        $basePayCount = new PayProfileCount;
        foreach ($payCount as $data){
            $insert['pay_price'] = $data['pay_price'];
            $insert['pay_num'] = $data['pay_num'];
            $insert['new_pay_pop_num'] = $data['new_pay_pop_num'];
            $insert['pay_pop_num'] = $data['pay_pop_num'];
            $insert['order_num'] = $data['order_num'];
            $insert['game'] = $data['game'];
            $insert['date'] = $date;
            $insert['pfid'] = $data["pfid"];
            $insert['count_type'] = $count_type;
            $insert['obuy_ref'] = $data["obuy_ref"];
            $activeUseCount = $dayCountModel->getActiveUserCount($data['game'], $sdate, $fdate);
            $insert['active_use_cout'] =  isset($activeUseCount['use_active'])?$activeUseCount['use_active']:0;
            $insert['order_num'] =   $data['order_num'];
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
            case 6://周
                $time = strtotime(date('Y-m-d'));
                $fdate = date("Y-m-d H:i:s",$time);
                $sdate = date("Y-m-d H:i:s",strtotime("-7 day",$time));
                $date = date("Ymd",strtotime("-7 day",$time));
              break;
            default:
        }
        $where["order_status"] =$baseOrder->order_status_arr['finish'];
        $payCount =$baseOrder->where("created_at",">=",$sdate)->where("created_at","<",$fdate)->whereNotIn("obuy_type",$baseOrder->game_buy_ref)
                 ->select(DB::raw(""
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then price_all else 0 end ) as pay_price,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as pay_num,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then is_old_pay else 0 end ) as new_pay_pop_num,"
                        . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  uid   end )) as pay_pop_num"
                        . ",count(id) as order_num,game,pfid,obuy_ref"))
                ->groupBy("game")->groupBy("pfid")->groupBy("obuy_ref")
                ->get();  
                   
        $payCount = $payCount->toArray();
        $ret['payCount'] = $payCount;
        $ret['date'] = $date;
        $ret['sdate'] = $sdate;
        $ret['fdate'] = $fdate;
        return $ret;
    }
    
    
    
}
