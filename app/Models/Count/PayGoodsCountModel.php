<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count;
use App\Models\Game\base\Order;
use App\Models\Game\base\OrderGoods;
use App\Models\Count\base\PayGoodsCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\DayCountModel;
use App\Models\Count\PayGoodsCountModel;
use App\Models\Game\base\ObGoods;
/**
 * Description of PayCount
 *
 * @author 七彩P1
 */
class PayGoodsCountModel {
    //put your code here
    public function arstianCount($count_type=4){
        foreach (config("game.game") as $game_data) {
            $game = $game_data['value'];
            $ret = $this->getData($count_type,$game);
            extract($ret);
            $dayCountModel = new DayCountModel();
            $basePayGoodsCount = new PayGoodsCount;
            $baseObGoods = ( new ObGoods);
            $obGoods = $baseObGoods->pluck("gname","id")->toArray();

            foreach ($payCount as $data){
                $insert['game'] = $data['game'];
                $insert['date'] = $date;
                $insert['count_type'] = $count_type;

                $insert['obgoods_id'] = $data['obgoods_id'];
                $insert['price'] = $data['price'];
                $insert['pfid'] = $data['pfid'];
                $insert['time_count_all'] = $data['time_count_all'];
                $insert['pop_count_all'] = $data['pop_count_all'];
                $insert['time_count'] = $data['time_count']; 
                $insert['pop_count'] =   $data['pop_count'];
                $insert['goods_name'] =   isset($obGoods[$data['obgoods_id']])?$obGoods[$data['obgoods_id']]:"";
                $basePayGoodsCount->insert($insert);
            }
        }
    }
    
    public function nowCount($count_type =3){
        return $this->getData($count_type);
    }
    
    
    
    public function getData($count_type =3,$game){
        $baseOrder = new Order();
        $baseOrderGoods = new OrderGoods();
        $oTable = $baseOrder->getTable();
        $ogTable = $baseOrderGoods->getTable();
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
        $payCount =$baseOrderGoods->leftJoin($oTable,$oTable.".id",$ogTable.".order_id")
                ->where($oTable.".created_at",">=",$sdate)->where($oTable.".created_at","<",$fdate)->whereNotIn($oTable.".obuy_type",$baseOrder->game_buy_ref)
                ->where($oTable.".game",$game)
                 ->select(DB::raw(""
                        . "count( DISTINCT {$oTable}.uid ) as pop_count_all,"
                        . $oTable.".pfid,"
                        . "count(goods_id) as time_count_all,"
                        . "sum( case when  order_status= {$baseOrder->order_status_arr['finish']} then 1 else 0 end ) as time_count,"
                        . "count( DISTINCT (case when  order_status= {$baseOrder->order_status_arr['finish']} then  {$oTable}.uid   end )) as pop_count"
                        . ",game,goods_id,obgoods_id,price"))
                ->groupBy(DB::raw("pfid,obgoods_id,price"))
                ->get();  
                   
        $payCount = $payCount->toArray();
        $ret['payCount'] = $payCount;
        $ret['date'] = $date;
        $ret['sdate'] = $sdate;
        $ret['fdate'] = $fdate;
        return $ret;
    }
    
    
    
}
