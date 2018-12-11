<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Game\VerConfigModel;

/**
 * Description of VerConfigController
 *
 * @author 七彩P1
 */
class VerConfigController extends BaseController {

    public function index(Request $request) {
        $baseWhereModel = $baseModel = new VerConfigModel();
        if ($request->has("uid") == TRUE) {
            $baseWhereModel = $baseModel->where("uid", $request->get("uid"));
        }
        if ($request->has("ip") == TRUE) {
            $baseWhereModel = $baseModel->where("ip", $request->get("ip"));
        }
        if ($request->has("device_id") == TRUE) {
            $baseWhereModel = $baseModel->where("device_id", $request->get("device_id"));
        }
        if ($request->has("pfid") == TRUE) {
            $baseWhereModel = $baseModel->where("pfid", $request->get("pfid"));
        }

        if ($request->has("game") != TRUE) {
            $request->offsetSet("game", 2);
        }
        $baseWhereModel = $baseModel->where("game", $request->get("game"));
        if ($request->has("mobile_type") == TRUE) {
            $baseWhereModel = $baseModel->where("mobile_type", $request->get("mobile_type"));
        }

        if ($request->has("mobile_type") == TRUE) {
            $baseWhereModel = $baseModel->where("mobile_type", $request->get("mobile_type"));
        }

        if ($request->has("app_ver") == TRUE) {
            $baseWhereModel = $baseModel->where("app_ver", $request->get("app_ver"));
        }
        if ($request->has("zip_ver") == TRUE) {
            $baseWhereModel = $baseModel->where("zip_ver", $request->get("zip_ver"));
        }

        $pager = $baseWhereModel->orderBy("created_at", 'DESC')->paginate(60);

        $typeArr = $baseModel->typeArr;
        $uptTypeArr = $baseModel->uptTypeArr;
        return view('admin.game.verconfig.list', compact('pager', 'typeArr', 'uptTypeArr'));
    }

    /*
     * 显示单条
     */

    public function show($id) {
        $info = array();
        if ($id) {
            $info = (new VerConfigModel())->where("id", $id)->first()->toArray();
        }
        $typeArr = (new VerConfigModel())->typeArr;
        $uptTypeArr = (new VerConfigModel())->uptTypeArr;
        return view("admin.game.verconfig.show", compact('info', 'typeArr', 'uptTypeArr'));
    }

    public function opeary($id, Request $request) {
        $all= $request->all();
        $validateRule = [
            'pfid' => 'required',
            'usid' => 'required',
            'game' => 'required',
            'mobile_type' => 'required',
            'url' => 'required',
            'app_ver' => 'required',
            'zip_ver' => 'required',
            'upt_type' => 'required',
            'type' => 'required',
            'filename' => 'required',
            'size' => 'required',
        ];
        $errorMsg = [
            'pfid.required' => '平台ID必填',
            'usid.required' => '平台的子ID必填',
            'game.required' => '游戏必填',
            'mobile_type.required' => '手机型号必填',
            'url.required' => '请上传包必填',
            'app_ver.required' => '更新大版本号必填',
            'zip_ver.required' => '热更新版本必填',
            'upt_type.required' => '是否最新整包必填',
            'type.required' => '是否最新整包必填',
            'filename.required' => '包命必填',
            'size.required' => '包大小必填',
        ];
        if($id){//如果修改
            unset($validateRule['url']);
            unset($errorMsg['url']);
        }
        
    
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);

        $add = array(
            'pfid' => $pfid,
            'usid' => $usid,
            'game' => $game,
            'mobile_type' => $mobile_type,
            'url' => $url,
            'app_ver' => $app_ver,
            'zip_ver' => $zip_ver,
            'upt_type' => $upt_type,
            'type' => $type,
            'uid' =>  isset($uid)?$uid:"",
            'ip' =>  isset($ip)?$ip:"",
            'device_id' => isset($device_id)?$device_id:"",
            'filename' => isset($filename)?$filename:"",
            'size' => $size,
        );

        $baseModel = new VerConfigModel();
        if($url){
            $newUrl =$baseModel->moveFile($url, $game, $pfid, $usid, $mobile_type, $filename);
            $add['url'] = $newUrl;
        }
        if ($id) {
            $op = "修改";
            if (!$url) {
                unset($add['url']);
            }
            //f($upt_type == 1 && !$uid  && !$ip && !$device_id){//如果是最新整包的话需要把之前的给修改掉
            if($upt_type == 1 ){//如果是最新整包的话需要把之前的给修改掉
                $baseModel->where("game",$game)->where("pfid",$pfid)->where("usid",$usid)->where("mobile_type",$mobile_type)->update(array("upt_type"=>"0"));
            }
            $id = $baseModel->where("id",$id)->update($add);
        } else {
            $op = "添加";
            $add['created_at'] = date("Y-m-d H:i:s");
            //if($upt_type == 1 && !$uid  && !$ip && !$device_id){//如果是最新整包的话需要把之前的给修改掉
            if($upt_type == 1 ){//如果是最新整包的话需要把之前的给修改掉
                $baseModel->where("game",$game)->where("pfid",$pfid)->where("usid",$usid)->where("mobile_type",$mobile_type)->update(array("upt_type"=>"0"));
            }
            $id =$baseModel->insertGetId($add);
        }
        $this->Log($op . "版本配置ID" . $id, $all);
        if ($id) {
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }

}
