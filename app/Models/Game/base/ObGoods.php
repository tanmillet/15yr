<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of ob_goods
 *
 * @author 七彩P1
 */
class ObGoods extends Model{
    //put your code here
    public $table ="ob_goods"; 
    public  $connection="mysql_two";
    protected $fillable  = [//创建用户时候可以修改添加的字段
        "goods_id",
        "goods_img",
        "gname" ,
        "gremark" ,
        "gtype" ,
        "gcoin",
        "gat_coin" ,
        "gcoin",
        "gvalid_time_type",
        "gvalid_time",
        "gis_vip",
        "bp_isshow",
        "bp_type",
        'email_content',
        'game'
    ];
    /*
     * $ref 1是根据道具表过来的
     * 2是根据背包道具表过来的
     */
    public function convertGoodsInfo($goodsInfo,$ref=1){
        if($ref ==1){
            $reslut["goods_id"] = $goodsInfo['ogoods_id'];
            $reslut["img"] = $goodsInfo['ogoods_img'];
            $reslut["gname"] = $goodsInfo['ogname'];
            $reslut["gremark"] = $goodsInfo['ogremark'];
            $reslut["gtype"] = $goodsInfo['ogtype'];
            $reslut["gcoin"] = $goodsInfo['ogcoin'];
            $reslut["gat_coin"] = $goodsInfo['ogat_coin'];
            $reslut["gvalid_time_type"] = $goodsInfo['ogvalid_time_type'];
            $reslut["gvalid_time"] = $goodsInfo['ogvalid_time'];
            $reslut["gis_vip"] = $goodsInfo['ogis_vip'];
            $reslut["bp_isshow"] = $goodsInfo['bp_isshow'];
            $reslut["bp_type"] = $goodsInfo['bp_type'];
        }elseif($ref==0){
            $reslut["goods_id"] = $goodsInfo['id'];
            $reslut["img"] = $goodsInfo['img'];
            $reslut["gname"] = $goodsInfo['name'];
            $reslut["gremark"] = $goodsInfo['remark'];
            $reslut["gtype"] = $goodsInfo['type'];
            $reslut["gcoin"] = $goodsInfo['number'];
            $reslut["gat_coin"] = $goodsInfo['give_number'];
            $reslut["gvalid_time_type"] = $goodsInfo['valid_time_type'];
            $reslut["gvalid_time"] = $goodsInfo['valid_time'];
            $reslut["gis_vip"] = $goodsInfo['is_vip'];
            $reslut["bp_isshow"] = $goodsInfo['bp_isshow'];
            $reslut["bp_type"] = $goodsInfo['bp_type'];
        }else{
            $reslut["goods_id"] = $goodsInfo['goods_id'];
            $reslut["img"] = $goodsInfo['goods_img'];
            $reslut["gname"] = $goodsInfo['gname'];
            $reslut["gremark"] = $goodsInfo['gremark'];
            $reslut["gtype"] = $goodsInfo['gtype'];
            $reslut["gcoin"] = $goodsInfo['gcoin'];
            $reslut["gat_coin"] = $goodsInfo['gat_coin'];
            $reslut["gvalid_time_type"] = $goodsInfo['gvalid_time_type'];
            $reslut["gvalid_time"] = $goodsInfo['gvalid_time'];
            $reslut["gis_vip"] = $goodsInfo['gis_vip'];
            $reslut["bp_isshow"] = $goodsInfo['bp_isshow'];
            $reslut["bp_type"] = $goodsInfo['bp_type'];
        }
        $reslut["email_content"] = $goodsInfo['email_content'];
        $reslut["game"] = $goodsInfo['game'];
        return $reslut;
    }
    
    public function getInsert($goodsInfo,$ref=1){
        $goodsInfo = $this->convertGoodsInfo($goodsInfo,$ref);
    //先到背包订单道具表里面去插入
        $intOrderGoods['goods_img']   =$goodsInfo['img'];
        $intOrderGoods['gname']   =$goodsInfo['gname'];
        $intOrderGoods['gremark'] =$goodsInfo['gremark'];
        $intOrderGoods['email_content'] =$goodsInfo['email_content'];

        $ifOrderGoods['goods_id']=$goodsInfo['goods_id'];
        $ifOrderGoods['gtype'] =  $goodsInfo['gtype'];
        $ifOrderGoods['gcoin']    =$goodsInfo['gcoin'];
        $ifOrderGoods['gat_coin'] =$goodsInfo['gat_coin'];
        $ifOrderGoods['gvalid_time_type'] =$goodsInfo['gvalid_time_type'];
        $ifOrderGoods['gvalid_time'] =$goodsInfo['gvalid_time'];
        $ifOrderGoods['gis_vip']     =$goodsInfo['gis_vip'];     
        $ifOrderGoods['bp_isshow']  = $goodsInfo['bp_isshow'];
        $ifOrderGoods['bp_type']     =$goodsInfo['bp_type'];    
        $ifOrderGoods['game']     =$goodsInfo['game'];    
        
        $obGoods = $this->firstOrCreate($ifOrderGoods,$intOrderGoods); 
        
        return $obGoods;
    }    
    
    
   /*
    * 根据道具详情 获取obGoodsId
    * $goodsInfo道具详情
    */
    public function GetObId($goodsInfo){
        $where['goods_id'] = $goodsInfo['id'];
        $where['gtype'] =  $goodsInfo['type'];
        $where['gcoin']    =$goodsInfo['number'];
        $where['gat_coin'] =$goodsInfo['give_number'];
        $where['gvalid_time_type'] =$goodsInfo['valid_time_type'];
        $where['gvalid_time'] =$goodsInfo['valid_time'];
        $where['gis_vip']     =$goodsInfo['is_vip'];     
        $where['bp_isshow']  = $goodsInfo['bp_isshow'];
        $where['bp_type']     =$goodsInfo['bp_type'];    
        $obGoods = $this->where($where)->first();
        if($obGoods){
            $obGoods= $obGoods->toArray();
        }
        return $obGoods;
    }
    
    /*
     * 获取道具信息
     */
    public function getObGoodsInfo($obgoods_id){
        $obgoods = $this->where("id",$obgoods_id)->first();
        return $obgoods;
    }
}
