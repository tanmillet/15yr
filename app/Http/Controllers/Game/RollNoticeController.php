<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\RollNotice;
use App\Server\RedisKey;
use Illuminate\Support\Facades\Redis;
use Admin;

/**
 * Description of RollNoticeController
 *
 * @author 七彩P1
 */
class RollNoticeController extends BaseController{
    //put your code here
    public function index(Request $request) {
        $baseWhereModel = $baseModel = new RollNotice();
        if ($request->has("play_type") == TRUE) {
            $baseWhereModel = $baseModel->where("play_type", $request->get("play_type"));
        }
        if ($request->has("pfid") == TRUE) {
            $baseWhereModel = $baseModel->where("pfid","like", "%,".$request->get("pfid").",");
        }

        if ($request->has("game") != TRUE) {
            $request->offsetSet("game", 2);
        }
        $baseWhereModel = $baseModel->where("game", $request->get("game"));
        
        if ($request->has("status") != TRUE) {
            $request->offsetSet("status", 1);
        }
        $baseWhereModel = $baseModel->where("status", $request->get("status"));

        if ($request->has("mobile_type") == TRUE) {
            $baseWhereModel = $baseModel->where("mobile_type","like", "%".$request->get("mobile_type").",");
        }
        
        $pager = $baseWhereModel->orderBy("sort", 'asc')->paginate(60);

        $playTypeArr = $baseModel->playTypeArr;
        $statusArr = $baseModel->statusArr;
        return view('admin.game.rollnotice.list', compact('pager', 'playTypeArr', 'statusArr'));
    }
    
    public function show($id) {
        $baseModel = new RollNotice();
        $playTypeArr = $baseModel->playTypeArr;
        $statusArr = $baseModel->statusArr;
        if ($id) {
            $item = $baseModel->where("id", $id)->first();
            return view('admin.game.rollnotice.show', compact('item', 'playTypeArr', 'statusArr'));
        }
        return view('admin.game.rollnotice.show', compact( 'playTypeArr', 'statusArr'));
    }
    
    
       public function opeary($id, Request $request) {

        $all = $request->all();
        $validateRule = [
            'game' => 'required',
            'pfid' => 'required',
            'contens' => 'required',
            'play_type' => 'required',
            'play_num' => 'required',
            'play_sdate' => 'required',
            'play_sfdate' => 'required',
            'sort' => 'required',
            'status' => 'required',
            'mobile_type' => 'required',
                //'price'                         => 'required', 
        ];
        $errorMsg = [
            'game.required' => '游戏不能为空',
            'pfid.required' => '渠道不能为空',
            'contens.required' => '内容不能为空',
            'play_type.required' => '播放类型不能为空',
            'play_num.required' => '播放次数不能为空',
            'play_sdate.required' => '播放开始时间不能为空',
            'play_sfdate.required' => '播放结束时间不能为空',
            'sort.required' => '排序不能为空',
            'status.required' => '状态不能为空',
            'mobile_type.required' => '手机类型不能为空',
                //'price.required'                         => '购买所需道具价格不能为空', 
        ];
        if($id){
            unset($validateRule["type"]); ////不让间隔类型
            unset($validateRule["space_time"]); //不让修改间隔时间
        }
        $this->validate($request, $validateRule, $errorMsg);
        
        extract($all);
        
        $insert['game'] = $game;
        $insert['pfid'] = ",".join(",", $pfid).",";
        $insert['contens'] = $contens;
        $insert['play_type'] = $play_type;
        $insert['play_num'] =$play_num;
        $insert['play_sdate'] = $play_sdate;
        //$insert['buy_type'] = $all['buy_type'];
        $insert['play_sfdate'] = $play_sfdate;
        $insert['sort'] = $sort;
        $insert['status'] = $status;
        $insert['limit_user'] = isset($limit_uid)?$limit_uid:"";
        $insert['mobile_type'] =  ",".join(",", $mobile_type).",";
        $insert['admin_id'] =  Admin::userId();
        if (!$id) {
            $op = "添加";
            $insert['created_at'] = date("Y-m-d H:i:s");
            $id = (new RollNotice)->insertGetId($insert);
            $this->Log($op . "添加ID" . $id."添加数据".json_encode($insert), $all);

        } else {
            $op = "修改";
            $insert['updated_at'] = date("Y-m-d H:i:s");
            (new RollNotice)->where("id", $id)->update($insert);
            $this->Log($op . "ID" . $id."修改数据".json_encode($insert), $all);
        }
        
        if ($id) {
            $redis = Redis::connection();//清楚缓存
            $redisKey = RedisKey::$Key;
            $key = $redisKey["rollNotice"];
            $redis->hdel($key,$game);
        
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }

    public function delete($id, Request $request) {
        $now_time = time();
        $all = $request->all();
        $info = (new RollNotice)->where("id", $id)->first();
        $flag  = (new RollNotice)->where("id", $id)->delete();
        $this->Log("ID" . $id."删除数据".json_encode($info->toArray()),$info->Array());
        if($info && $flag && $info->status==1){
            $redis = Redis::connection();//清楚缓存
            $redisKey = RedisKey::$Key;
            $key = $redisKey["rollNotice"];
            $redis->hdel($key,$game);
        }
        return $this->success();
    }
    
    //发布
    public function publish($id, Request $request){
        $info = (new RollNotice)->where("id", $id)->first();
        $flag = (new RollNotice)->where("id", $id)->where("status", 2)->update(array("status"=>1,"updated_at"=>date("Y-m-d H:i:s"),"admin_id"=>Admin::userId()));
        $this->Log("ID" . $id."发布数据".json_encode($info->toArray()),$info->Array());
        if($info && $flag){
            $redis = Redis::connection();//清楚缓存
            $redisKey = RedisKey::$Key;
            $key = $redisKey["rollNotice"];
            $redis->hdel($key,$info->game);
        }
        return $this->success();
    }
}
