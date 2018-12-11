<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\MoneyModel;
use App\Models\Game\base\ScareBuy;
use App\Models\Game\base\ScareBuyLog;

/**
 * Description of ScareBuyController
 *
 * @author 七彩P1
 */
class ScareBuyController extends BaseController {

    //put your code here
    public function index(Request $request) {
        $ScareBuy = ScareBuy::query();
        if ($request->has("game") == TRUE) {
            $users->where("game", $request->get("game"));
        }

        $pager = $ScareBuy->orderBy('created_at', 'DESC')->paginate(60);
        $money_type = (new MoneyModel)->typeName;
        $type_arr = (new ScareBuy)->type_arr;
        $robot_num_type_arr = (new ScareBuy)->robot_num_type_arr;
        return view('admin.game.scarebuy.list', compact('pager', 'money_type', 'type_arr', 'robot_num_type_arr'));
    }

    public function show($id) {
        $ScareBuy = ScareBuy::query();


        $money_type = (new MoneyModel)->typeName;
        $type_arr = (new ScareBuy)->type_arr;
        $robot_num_type_arr = (new ScareBuy)->robot_num_type_arr;
        $dealPrice = array();
        if ($id) {
            $item = (new ScareBuy)->where("id", $id)->first();
            $dealPrice = (new ScareBuy)->dealPrice($item->price);
            return view('admin.game.scarebuy.show', compact('item', 'money_type', 'type_arr', 'robot_num_type_arr', 'dealPrice'));
        }
        return view('admin.game.scarebuy.show', compact('money_type', 'type_arr', 'robot_num_type_arr', 'dealPrice'));
    }

    public function opeary($id, Request $request) {

        $all = $request->all();
        $validateRule = [
            'game' => 'required',
            'scare_buy_name' => 'required',
            'scare_buy_contens' => 'required',
            'scare_buy_goods_str' => 'required',
            'type' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'space_time' => 'required',
            'buy_type' => 'required',
                //'price'                         => 'required', 
        ];
        $errorMsg = [
            'game.required' => '游戏',
            'scare_buy_name.required' => '抢购名称不能为空',
            'scare_buy_contens.required' => '抢购内容不能为空',
            'scare_buy_goods_str.required' => '奖品内容不能为空',
            'type.required' => '抢购类型不能为空',
            'start_time.required' => '抢购生效时间不能为空',
            'end_time.required' => '抢购截止时间不能为空',
            'space_time.required' => '抢购开奖间隔时间不能为空',
            'buy_type.required' => '购买类型不能为空',
                //'price.required'                         => '购买所需道具价格不能为空', 
        ];
        if($id){
            unset($validateRule["type"]); ////不让间隔类型
            unset($validateRule["space_time"]); //不让修改间隔时间
        }
        $this->validate($request, $validateRule, $errorMsg);
        $price = "";
        foreach ($all["buy_type"] as $buy) {
            $price_num = "price_" . $buy;
            $price .= $buy . ":" . $all[$price_num] . ",";
        }
        $price = rtrim($price, ",");

        $insert['game'] = $all['game'];
        $insert['scare_buy_name'] = $all['scare_buy_name'];
        $insert['scare_buy_contens'] = $all['scare_buy_contens'];
        $insert['scare_buy_goods_str'] = $all['scare_buy_goods_str'];
        if(isset( $all['type'] )){
            $insert['type'] = $all['type'];
        }
        if(isset( $all['space_time'] )){
            $insert['space_time'] = $all['space_time'];
        }
        $insert['start_time'] = strtotime($all['start_time']);
        $insert['end_time'] = strtotime($all['end_time']);
        //$insert['buy_type'] = $all['buy_type'];
        $insert['price'] = $price;
        $insert['created_at'] = date("Y-m-d H:i:s");
        $insert['robot_num'] = $all['robot_num'];
        $insert['robot_num_type'] = $all['robot_num_type'];

        if (!$id) {
            $op = "添加";
            $id = (new ScareBuy)->insertGetId($insert);
            $url = env("GAME_URL") . "/makeconfig/uptScareLog/?game=" . $insert['game'] . "&id=" . $id;
            file_get_contents($url); //更新夺宝奇兵
        } else {
            $op = "修改";
            unset($insert["type"]); ////不让间隔类型
            unset($insert["space_time"]); //不让修改间隔时间
            (new ScareBuy)->where("id", $id)->update($insert);
            $sbl = (new ScareBuyLog);
            $now_time = time();
            $sbl->where("scare_buy_id", $id)->where("status", 0)->where("order_stime", ">", $now_time)->where("order_ftime", ">", $now_time)
                    ->update($insert);
        }
        $this->Log($op . "一元抢购ID" . $id, $all);
        if ($id) {
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }

    public function delete($id, Request $request) {
        $now_time = time();
        $all = $request->all();
        (new ScareBuy)->where("id", $id)->delete();
        (new ScareBuyLog)->where("scare_buy_id", $id)->where("status", 0)->where("order_stime", ">", $now_time)->where("order_ftime", ">", $now_time)->delete();
        return $this->success();
    }

}
