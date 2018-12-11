<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\RealOrder;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\ObGoods;
use Admin;
/**
 * Description of SendRealGoodController
 *
 * @author 七彩P1
 */
class SendRealGoodController extends BaseController{
    //put your code here
    
    public function index(Request $request){
        $uTable = (new userInfo)->getTable();
        $roTable = (new RealOrder)->getTable();
        $ogTable = (new ObGoods)->getTable();
        $baseRealOrder = new RealOrder(); 
        $realOrder = $baseRealOrder->leftjoin($ogTable ,$roTable .".ob_goods_id","=",$ogTable.".id");
        $realOrder = $realOrder->leftjoin($uTable ,$roTable .".uid","=",$uTable.".uid");
        
        if($request->has("uid") ==TRUE){
            $realOrder->where($uTable .".uid",$request->get("uid"));
        }
        if($request->has("game") ==TRUE){
            $realOrder->where($roTable .".game",$request->get("game"));
        }
        if($request->has("uname") ==TRUE){
            $realOrder->where($uTable .".uname",'LIKE', '%' . $request->get("uid") . '%');
        }
        if($request->has("status") ==TRUE){
            $realOrder->where($roTable .".status",$request->get("status"));
        }else{
            $realOrder->where($roTable .".status",1);
        }
        if($request->has("real_order") ==TRUE){
            $realOrder->where($roTable .".real_order",$request->get("real_order"));
        }
        
        $pager = $realOrder->orderBy($roTable .'.id', 'desc')->select($roTable.".*",$ogTable.".gname",$uTable.".uname")->paginate();
        $status_arr = $baseRealOrder->status_arr;
        return view('admin.realGoods.list', compact('pager','status_arr'));
    }
    
    
    public function show($id){
        $uTable = (new userInfo)->getTable();
        $roTable = (new RealOrder)->getTable();
        $ogTable = (new ObGoods)->getTable();
        
        $baseRealOrder = new RealOrder(); 
        $realOrder = $baseRealOrder->leftjoin($ogTable ,$roTable .".ob_goods_id","=",$ogTable.".id");
        $realOrder = $realOrder->leftjoin($uTable ,$roTable .".uid","=",$uTable.".uid");
        $realOrder->where($roTable .".id",$id);
        $info = $realOrder->orderBy($roTable .'.id', 'asc')->select($roTable.".*",$ogTable.".gname",$uTable.".uname")->first();
        $status_arr = $baseRealOrder->status_arr;
        $fast_type_arr  = $baseRealOrder->fast_type_arr;
        return view('admin.realGoods.item', compact('info','status_arr','fast_type_arr'));
    }
    
    
    public function update($id,Request $request){
        
        $all  = $request->all();
        $validateRule = [
                            'status'                      => 'required',
                            'fast_type'                  => 'required',
                            'fast'                         => 'required',
                            'remark'                         => 'sometimes',
                            'id'                         => 'required',
                        ];
        $errorMsg =     [
                            'status.required'                      => '订单状态必填',
                            'fast_type.required'                  => '快递类型必填',
                            'fast.required'                         => '快递单号不能为空',
                            'id.integer'                         => '修改ID不能为空',
                        ];
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);
        $baseRealOrder = new RealOrder(); 
        $update['status'] = $status;
        $update['fast_type'] = $fast_type;
        $update['fast'] = $fast;
        $update['remark'] = $remark;
        if($status == 2){
            $update['send_admin_id'] = Admin::userId();
        }
        $flag = $baseRealOrder ->where("id",$id)->update($update);
        
        $this->Log("修改实物发放列表ID".$id, $all);
        if($flag){
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
    
}
