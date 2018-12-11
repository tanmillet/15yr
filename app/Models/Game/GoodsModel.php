<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Models\Model;

use App\Models\Game\base\GoodsPrice;
use App\Models\Game\base\BackPage;
use App\Models\Game\base\Goods;
/**
 * Description of GoodsModel
 *
 * @author 七彩P1
 */
class GoodsModel  extends Model{
    public function showSection($goodsInfo = array()){
        $goods = array();
        $mgoods = new Goods();
        $mgoodPrice = new GoodsPrice();
        $mbackPage = new BackPage();
        $goods['show']['goods_type'] = $mgoods->goods_type;
        $goods['show']['is_buy'] = $mgoods->is_buy;
        $goods['show']['show_type'] = $mgoods->show_type;
        $goods['show']['is_show'] = $mgoods->is_show;
        $goods['show']['bp_isshow'] = $mgoods->bp_isshow;
        $goods['show']['valid_time_type'] = $mgoods->valid_time_type;
        $goods['show']['buy_type'] = $mgoodPrice->buy_type;
        $goods['show']['bp_type'] = $mbackPage->bp_type;
        $goods['show']['is_vip'] = $mgoods->is_vip;
        $goods['show']['game'] = $mgoods->game;
        $goods['show']['ticket_show_type'] = $mgoods->ticket_show_type_arr;
        
        $goods_price = array();
        foreach($goods['show'] as $key=>$val){
            $goods['default'][$key] = isset($goodsInfo[$key])?$goodsInfo[$key]:"";
        }
        
        $goodsId = isset($goodsInfo['id'])?$goodsInfo['id']:0;
        $goods['show']['goods_price'] = $this->getGoodPrice($goodsId);
        return $goods;
    }
    
   //获取道具价格
    public function getGoodPrice($goodId){
        $mgoodPrice = new GoodsPrice();
        $goodsPrice = array();
        if($goodId){
            $goodsPriceObj = $mgoodPrice->where("goods_id",$goodId)->get()->toArray();
            $goodsPriceClt = collect($goodsPriceObj);
        }else{
            $goodsPriceClt = array();
        }
        foreach($mgoodPrice->buy_type as $key =>$val){
            
            if($goodsPriceClt){
                $gPrice = $goodsPriceClt->where("buy_type",$key)->first();
                $goodsPrice[$key]["price"] = $gPrice["price"];
                $goodsPrice[$key]["sham_price"] = $gPrice["sham_price"];
                $goodsPrice[$key]["three_gid"] = $gPrice["three_gid"];
            }else{
                $goodsPrice[$key]["price"] = 0;
                $goodsPrice[$key]["sham_price"] = 0;
                $goodsPrice[$key]["three_gid"] = "";
            }
        }
        return $goodsPrice;
    }
}
