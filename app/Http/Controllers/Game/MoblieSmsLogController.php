<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Count\MobileSmsLogModel;
use App\Api\Sms\SmsApi;
use Admin;
/**
 * Description of MoblieSmsLogController
 *
 * @author 七彩P1
 */
class MoblieSmsLogController extends BaseController {
    //put your code here
    public function index(Request $request){
        $mobileSmsLog = new MobileSmsLogModel;
        if($request->has("type") ==TRUE){
            $mobileSmsLog->where("type",$request->get("type"));
        }
        $pager = $mobileSmsLog->orderBy("created_at", 'DESC')->paginate();
        
        return view('admin.game.mobliesmslog.list', compact('pager'));
    }
    
    
    public function show(Request $request){
        return view('admin.game.mobliesmslog.show', compact('pager'));
    }
    
    public function opearySend(Request $request){
        $all  = $request->all();
        $validateRule = [
                            'mobile'                      => 'required',
                            'goods_name'                  => 'required',
                            'user'                         => 'required',
                            'password'                         => 'required',
                        ];
        $errorMsg =     [
                            'mobile.required'                      => '手机号码必填',
                            'goods_name.required'                  => '道具名字必填',
                            'user.required'                         => '卡号必填',
                            'password.integer'                         => '卡密必填',
                        ];
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);
        $str ="亲爱的玩家，恭喜您在游戏中获得%s,请根据账号密码自行充值。若有疑问请联系客服！感谢您对我们的支持！<br>卡号：%s卡密：%s";
        $centents = sprintf($str,$goods_name,$user,$password);
        $insert["mobile"] = $mobile;
        $insert["centents"] = $centents;
        $insert["send_type"] = 1;
        $insert["created_at"] = date("Y-m-d H:i:s");
        $insert['send_uid'] = Admin::userId();
        $id = (new MobileSmsLogModel)->insertGetId($insert);
        $this->Log("发送短信内容".$centents, $all);
        if($id){
            $flag =(new SmsApi())->realOrderSms($mobile,$goods_name,$user,$password,"SMS_142905047");
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
}
