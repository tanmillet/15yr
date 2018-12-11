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
use App\Models\Game\base\DdzCfg;
use App\Models\Game\base\DdzRankData;
use App\Models\Game\base\DdzRankCfg;
use App\Models\Game\base\userInfo;

/**
 * Description of BwsController
 *
 * @author 七彩P1
 */
class DwsController extends BaseController {

    //put your code here
    public function index(Request $request) {
        $ddzRankData = new DdzRankData();
        $baseUserInfo = new UserInfo();
        $baseDdzCfg = new DdzCfg();

        $uiTable = $baseUserInfo->getTable();
        $ddzTable = $ddzRankData->getTable();


        if ($request->has("game") != TRUE) {
            $request->offsetSet("game", 2);
        }
        $ddzCfgObj = $baseDdzCfg->where("game", $request->get("game"))->select("id", "ddz_name", "status")->get();
        $ddzCfgArr = $ddzCfgObj->toArray();
        if ($request->has("game") != TRUE) {
            $request->offsetSet("game", 2);
        }
        if ($request->has("ddz_id") != TRUE) {
            foreach ($ddzCfgObj as $ddzCfg) {
                if ($ddzCfg['status'] == 1) {
                    $request->offsetSet("ddz_id", $ddzCfg["id"]);
                }
            }
        }
        $baseObj = $ddzRankData->leftjoin($uiTable, $uiTable . ".uid", "=", $ddzTable . ".uid")->where($uiTable . ".ustatus", 0)->where($uiTable . ".game", $request->get("game"))
                ->where($ddzTable . ".season_id", $request->get("ddz_id"))
                ->orderBy($ddzTable . ".group", "desc")->orderBy($ddzTable . ".order", "asc")->orderBy($ddzTable . ".star", "desc")->orderBy($ddzTable . ".score", "desc")
                ->select($ddzTable . ".uid", "uface", "uname", "group", "order", "star", "score", "season_id", "id");

        if ($request->has("uid")) {
            $baseObj = $baseObj->where($uiTable . ".uid", $request->get("uid"));
        }
        $pager = $baseObj->paginate(50);
        $groupArr = (new DdzRankCfg)->groupArr;
        $orderArr = (new DdzRankCfg)->orderArr;
        return view('admin.game.dws.list', compact('pager', "ddzCfgArr", "orderArr", "groupArr"));
    }

    public function show($id,Request $request) {
        $ddzRankData = new DdzRankData();
        $baseUserInfo = new UserInfo();
        $baseDdzCfg = new DdzCfg();
        
        $uiTable = $baseUserInfo->getTable();
        $ddzTable = $ddzRankData->getTable();
        $ddzCfgTable = $baseDdzCfg->getTable();
        
        $ddzRankData = new DdzRankData();
        $info = $ddzRankData->leftjoin($uiTable, $uiTable . ".uid", "=", $ddzTable . ".uid")
                            ->leftjoin($ddzCfgTable, $ddzCfgTable . ".id", "=", $ddzTable . ".season_id")
                ->where($ddzTable . ".id", $id)
                ->select($ddzTable . ".uid", "uface", "uname", "group", "order", "star", "score", "season_id", $ddzTable.".id","ddz_name")->first()->toArray();
        
        $groupArr = (new DdzRankCfg)->groupArr;
        $orderArr = (new DdzRankCfg)->orderArr;
        return view('admin.game.dws.show', compact('info', "orderArr", "groupArr"));
    }

    
    public function opeary($id, Request $request) {

        $all = $request->all();
        $validateRule = [
            'group' => 'required',
            'order' => 'required',
            'star' => 'required',
            'score' => 'required',
        ];
        $errorMsg = [
            'group.required' => '段位必填',
            'order.required' => '阶必填',
            'star.required' => '星星必填',
            'score.required' => '分数必填',
        ];
        $all = $request->all();
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);
        $update["group"] = $all["group"];
        $update["order"] = $all["order"];
        $update["star"] = $all["star"];
        $update["score"] = $all["score"];
        $ddzRankData = new DdzRankData();
        $flag = $ddzRankData->where("id", $id)->update($update);
        $this->Log("修改了ddz_rank_data表的id" . $id . "修改了" . json_encode($update), $all);
        if ($flag) {
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }
}
