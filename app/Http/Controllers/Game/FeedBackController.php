<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;

use App\Models\Game\base\FeedBack;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\userInfo;
use App\Models\Game\EmailModel;
/**
 * Description of FeedController
 *
 * @author 七彩P1
 */
class FeedBackController extends BaseController {

    //put your code here
    public function index(Request $request) {
        $uTable = (new userInfo)->getTable();
        $baseFeedBack = new FeedBack();
        $bbTable = $baseFeedBack->getTable();
        $db =(new userInfo)->getConnectionName();
        $udb = config("database.connections.".$db);
        $feedBack = $baseFeedBack->leftjoin($udb["database"].".".$uTable, $uTable . ".uid", "=", $bbTable . ".uid");

        
        if($request->has("game") !=TRUE){
           $request->offsetSet("game",2); 
        }
        
        
        if ($request->has("uid") == TRUE) {
            $feedBack = $feedBack->where($uTable . ".uid", $request->get("uid"));
        }
        if ($request->has("game") == TRUE) {
            $feedBack = $feedBack->where($uTable . ".game", $request->get("game"));
        }
        if ($request->has("uname") == TRUE) {
            $feedBack = $feedBack->where($uTable . ".uname", 'LIKE', '%' . $request->get("uid") . '%');
        }
        
        if ($request->has("uname") == TRUE) {
            $feedBack = $feedBack->where($uTable . ".uname", 'LIKE', '%' . $request->get("uid") . '%');
        }
        if($request->has("sdate") !=TRUE){
           $sdate= date("Y-m-d 00:00:00", time()-7*3600*24);
           $request->offsetSet("sdate",$sdate); 
        }
        if($request->has("sdate") !=TRUE){
           $fdate= date("Y-m-d H:i:s");
           $request->offsetSet("fdate",$fdate); 
        }
        
        $feedBack = $feedBack->where("created_at",">=",$request->get("sdate"));
        if($request->has("fdate") !=TRUE){
           $request->offsetSet("fdate",date("Y-m-d H:i:s")); 
        }
        $feedBack = $feedBack->where("created_at","<=",$request->get("fdate"));

        $pager = $feedBack->orderBy($bbTable . '.id', 'desc')->select($bbTable . ".*", $uTable . ".game", $uTable . ".uname")->paginate();
        return view('admin.game.feedback.list', compact('pager'));
    }

    
    /*
     * 显示页面
     */
    public function show($id){
        $uTable = (new userInfo)->getTable();
        $baseFeedBack = new FeedBack();
        $bbTable = $baseFeedBack->getTable();
        $db =(new userInfo)->getConnectionName();
        $udb = config("database.connections.".$db);
        $feedBack = $baseFeedBack->leftjoin($udb["database"].".".$uTable, $uTable . ".uid", "=", $bbTable . ".uid");
        $feedBack = $feedBack->where($bbTable . ".id",$id)->first();
        $info = $feedBack->toArray();
        return view('admin.game.feedback.show', compact('info'));
    }
    
        /*
     * 显示页面
     */
    public function opeary(Request $request){
        
        $all  = $request->all();
        $validateRule = [
                            'email_contents'                      => 'required',
                            'uid'                      => 'required',
                        ];
        $errorMsg =     [
                            'email_contents.required'                      => '发送邮件内容必填',
                            'uid.required'                  => '用户uid缺少',
                        ];
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);
        
        $flag =(new EmailModel())->sendEmail($uid, $email_contents);
        $this->Log("给用户".$uid."发送邮件内容".$email_contents, $all);
        if($flag){
            (new FeedBack)->where("id",$id)->update(array("is_reply"=>"1","updated_at"=>date("Y-m-d H:i:s"),"email_id"=>$flag));
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
}
