<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;
use App\Models\Game\base\Goods;
use App\Models\Game\base\ObGoods;
use Illuminate\Support\Facades\DB;
/**
 * Description of GoodsSelected
 *
 * @author 七彩P1
 */
class GoodsSelected {
    
    /*
     * goods_term_type显示类型是取goods表里面的数据还是obgoods表里面的数据
     * goods_id_str已经选择到的字符串 道具id:道具数量,道具id:道具数量''''''
     * $return_name  goods_id_str
     */
    public function show($goods_term= array(),$goods_id_str="",$goods_term_type="goods",$return_name="goods_id_str"){
        $select = "";
        if($goods_term_type =="goods"){
            $baseModel = new Goods();
            $select =DB::raw("id,name,img,type ");
        }elseif ($goods_term_type =="obgoods") {
            $baseModel = new ObGoods();
            $select =DB::raw("id,gname as name,goods_img as img,gtype as type"); 
        }else{
            $baseModel = new Goods();
            $select =DB::raw("id,name,img,type ");
        }

        $check_goods_selected =  $goods_img_info = $goods_selected_info = $goods_selected_num = array();
        if($goods_id_str){
            $selected_goods_data = explode(",", $goods_id_str);//选择的道具字符串
            foreach($selected_goods_data as $selected_goods){
                $selected_arr  = explode(":", $selected_goods);
                $goods_selected_num[$selected_arr['0']] = isset($selected_arr[1])?$selected_arr[1]:1;
            }
        }
        $check_goods_selected =  $goods_selected_num;
        $goods_info_data = $baseModel ->where($goods_term)->select($select)->orderby("type")->get();
        
        $g = new Goods();
        foreach($goods_info_data as $goods_info){
            $goods_img_info[$goods_info['id']] = $goods_info["img"];//道具图片
            $goods_selected_info[$goods_info['type']]['data'][$goods_info['id']] = $goods_info["name"];////道具名字
            !isset($goods_selected_num[$goods_info['id']]) &&  $goods_selected_num[$goods_info['id']] =1;//道具数量
            
            $goods_selected_info[$goods_info['type']]['type_name'] = isset($g->goods_type[$goods_info['type']])?$g->goods_type[$goods_info['type']]:$goods_info['type'];
        }
        $goods_selected_num= json_encode($goods_selected_num);
        return view("admin.common.goodsSelected",compact('goods_selected_num','goods_selected_info',"goods_img_info","check_goods_selected","return_name","goods_id_str"));
    }
}
