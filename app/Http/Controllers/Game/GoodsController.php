<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use App\Models\Game\base\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\GoodsModel;
/**
 * Description of GoodsController
 *
 * @author 七彩P1
 */
class GoodsController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $goods = new Goods();
        if($request->has("game") ==TRUE){
            $goods->where("game",$request->get("game"));
        }
        if($request->has("name") ==TRUE){
            $goods->where("name",$request->get("name"));
        }
        if($request->has("type") ==TRUE){
            $goods->where("type",$request->get("type"));
        }
        if($request->has("is_buy") ==TRUE){
            $goods->where("is_buy",$request->get("is_buy"));
        }
        if($request->has("show_type") ==TRUE){
            $goods->where("show_type",$request->get("show_type"));
        }
        if($request->has("is_show") ==TRUE){
            $goods->where("is_show",$request->get("is_show"));
        }
        if($request->has("bp_type") ==TRUE){
            $goods->where("bp_type",$request->get("bp_type"));
        }
        if($request->has("ticket_show_type") ==TRUE){
            $goods->where("ticket_show_type",$request->get("ticket_show_type"));
        }
        if($request->has("valid_time_type") ==TRUE){
            $goods->where("valid_time_type",$request->get("valid_time_type"));
        }
        if($request->has("is_vip") ==TRUE){
            $goods->where("is_vip",$request->get("is_vip"));
        }
        
        $pager = $goods->paginate();
        $goodsAtrr = (new GoodsModel()) ->showSection();
        return view('admin.game.goods.list', compact('pager','goodsAtrr'));
    }
    
    
    public function getDelete($id){
        $goodsArr = (new Goods())->where("id",$id)->delete(array("is_delete"=>1));
        return redirect("goods/info");
    }
    
    public function getEdit($id){
       return  $this->getAddGoods($id);
    }
    
    
    
    /*
     *显示单条
     */
    public function show($id){
        $goodsInfo = array();
        if($id){
            $goodsInfo = (new Goods())->where("id",$id)->first()->toArray();
        }
        $goodsAtrr = (new GoodsModel()) ->showSection($goodsInfo);
        return view("admin.game.goods.show", compact('goodsInfo'))->with("goods",$goodsAtrr);
        
    }
    
    
    public function opeary($id,Request $request){
        extract($request->all());
        $validateRule = [
                            'game'                      => 'required',
                            'left_title'                  => 'required',
                            'title'                         => 'required',
                            'content'=>'required'
                        ];
        $errorMsg =     [
                            'game.required'                      => '游戏必填',
                            'left_title.required'                  => '左边标题必填',
                            'title.required'                         => '标题必填',
                            'content.integer'                         => '内容必填',
                        ];
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);

        
        
        $insert['game'] = $all['game'];
        $insert['left_title'] = $all['left_title'];
        $insert['title'] = $all['title'];
        $insert['content'] = $all['content'];
        
        
        $add = array(
            "name" =>$name,
            "img" =>$imgUrl,
            'remark'=>$remark,
            'type'=>$type,
            'use_number'=>$use_number,
            'number'=>$number,
            'give_number'=>$give_number,
            'is_buy'=>$is_buy,
            'show_type'=>$show_type,
            'is_show'=>$is_show,
            'bp_isshow'=>$bp_isshow,
            'bp_type'=>$bp_type,
            'valid_time_type'=>$valid_time_type,
            'valid_time'=>$valid_time,  
            'give_goods'=>$give_goods,
            'is_vip'=>$is_vip,
            'email_content'=>$email_content,
            'game'=>$game,
            'ticket_show_type'=>$ticket_show_type,
        );
        $goodsPrice = new \App\Models\base\GoodsPrice;
        
        if($id){
            $op ="修改";
            if(!$imgUrl){
                unset($add['img']);
                $add['updated_at']=date("Y-m-d H:i:s");
            }
            $id = (new Goods)->insertGetId($add);
            $op ="修改";
            $add['updated_at'] = date("Y-m-d H:i:s");
            (new Goods)->where("id",$id)->update($add);
            
            foreach($mgoodPrice->buy_type as $key=>$val){
               $goodsPrice ->where(array("goods_id"=>$id,"buy_type"=>$key))->delete();
                $gdpInsert['buy_type'] =$key;
                $gdpInsert['price']     =$request->get("price_".$key);
                $gdpInsert['sham_price'] =$request->get("sham_price_".$key);
                $gdpInsert['goods_id'] =$id;
                $gdpInsert['three_gid'] =$request->get("three_gid_".$key);
                //$flag = $goodsPrice ->where(array("goods_id"=>$id,"buy_type"=>$key))->update($gdpInsert);
                $goodsPrice ->insert($gdpInsert);
            }
        }else{
            $add['created_at']=date("Y-m-d H:i:s");
            $goodsid = $goods->insertGetId($add);
            foreach($mgoodPrice->buy_type as $key=>$val){
                $gdpInsert['goods_id'] =$goodsid;
                $gdpInsert['buy_type'] =$key;
                $gdpInsert['price']     =$request->get("price_".$key);
                $gdpInsert['sham_price'] =$request->get("sham_price_".$key);
                $gdpInsert['three_gid'] =$request->get("three_gid_".$key);
                $goodsPrice ->insert($gdpInsert);
            }
            
        }
        $this->Log($op ."道具ID".$id, $all);
        if($id){
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
}
