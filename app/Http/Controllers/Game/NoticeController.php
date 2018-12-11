<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author 七彩P1
 */
namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\Notice;

class NoticeController extends BaseController{
    
    public function index(Request $request){
        $notice = new Notice();
        if($request->has("game") ==TRUE){
            $notice->where("game",$request->get("game"));
        }
        $pager = $notice->paginate();
        
        return view('admin.game.notice.list', compact('pager'));
    }
    
    
    /*
     *显示单条公告 
     */
    public function show($id){
        $item = (new Notice)->where("id",$id)->first(); 
        return view('admin.game.notice.show', compact('item'));
    }
    
    
    public function opeary($id,Request $request){
        
        $all  = $request->all();
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
        if(!$id){
            $op ="添加";
            $insert['created_at'] = date("Y-m-d H:i:s");
            $id = (new Notice)->insertGetId($insert);
        }else{
            $op ="修改";
            $insert['updated_at'] = date("Y-m-d H:i:s");
            (new Notice)->where("id",$id)->update($insert);
        }
        $this->Log($op ."一元抢购ID".$id, $all);
        if($id){
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
    

}
