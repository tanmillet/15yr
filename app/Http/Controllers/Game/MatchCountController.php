<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;

use App\Models\Count\base\MacthCount;
use App\Models\Count\base\MatchLog;
use App\Models\Game\base\MacthRoomCfg;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

/**
 * Description of ActiveController
 *
 * @author 七彩P1
 */
class MatchCountController extends BaseController
{
    public function index(Request $request)
    {
        $baseCount = new MacthCount;
        if ($request->has("game") != TRUE) {
            $request->offsetSet("game", 2);
        }
        $baseComCount = $baseCount->where("game", $request->get("game"));
        $time = time();
        if ($request->has("sdate") != TRUE) {
            $stime = $time - 8 * 3600 * 24;
            $sdate = date("Y-m-d", $stime);
            $request->offsetSet("sdate", $sdate);
        }
        if ($request->has("fdate") != TRUE) {
            $request->offsetSet("fdate", date("Y-m-d", $time));
        }
        if ($request->has("match_count_type") != TRUE) {
            $request->offsetSet("match_count_type", 13);
        }
        $baseComCount = $baseComCount->where("match_count_type", $request->get("match_count_type"));
        $baseComCount = $baseComCount->where("date", ">=", $request->get("sdate"));
        $baseComCount = $baseComCount->where("date", "<=", $request->get("fdate"));
        $dataObj = $baseComCount->orderBy("date", "desc")->get();
        $data = $dataObj->toArray();
        $ret_data = array();
        foreach ($data as $values) {
            $ret_data[$values["date"]][$values['type']]["num"] = $values['num'];
            $ret_data[$values["date"]][$values['type']]["pop_num"] = $values['pop_num'];
        }
        $active_type_arr = $baseCount->match_count_type_arr;
        $type_arr_all = $baseCount->type_arr;
        $type_arr = $type_arr_all[$request->get("match_count_type")];
        return view('admin.game.matchcount.list', compact('ret_data', 'active_type_arr', 'type_arr'));
    }

    public function match(Request $request)
    {
        $game = 2;
        $name = [1];
        $guide = config('match.guide');
        $types = config('match.types');
        $stime = strtotime(date('Y-m-d')); //默认只获取当天的数据
        $ftime = strtotime(date('Y-m-d'));
        $type = (new MacthRoomCfg())->select('id', 'type', 'match_name')->get()->toArray();
        if ($request->has('type') && !$request->has('name')) {
            $data = (new MacthRoomCfg())->select('id')->where('type', $request->get('type'))->get()->toArray();
            $name = array_pluck($data, 'id');
        }
        if ($request->has('name')) {
            $name = [$request->get('name')];
        }
        if ($request->has('game')) {
            $game = $request->get('game');
        }
        if ($request->has('sdate')) {
            $stime = strtotime($request->get('sdate'));
        }
        if ($request->has('fdate')) {
            $ftime = strtotime($request->get('fdate'));
        }
        $nrr = [];
        $rr = [];
        foreach ($type as $v) {
            if (!isset($rr[$v['type']])) {
                $rr[$v['type']] = [$v['id']];
            } else {
                array_push($rr[$v['type']], $v['id']);
            }
            $nrr[$v['id']] = array('type' => $v['type'], 'name' => $v['match_name']);
        }
        $arr = [];
        $matchlog = new MatchLog();
        for ($i = 0; $i * 86400 + $stime <= $ftime; $i++) { //按天数遍历
            $arr[date('Y-m-d', $i * 86400 + $stime)] = $matchlog
                ->where('game',$game)
                ->where('name',$name)
                ->where('date',date('Y-m-d',$i * 86400 + $stime))
                ->get()->toArray();
        }
        $ar = [];
        foreach ($arr as $k=>$v) {
            $ar[$k][1] = [];
            foreach ($v as $ke=>$va) {
                $ar[$k][1][$va['value']] = $va['nums'];
            }
        }
        foreach ($guide as $k => $v) {
            data_set($ar, '*.1.' . $k, 0, false);
        }
        $arr = $ar;
        return view('admin.game.matchcount.match', compact('types', 'guide', 'nrr', 'rr', 'arr'));
    }

    public function expold(Request $request)
    {
        $game = $request->get('game'); //游戏ID
        $name = $request->get('name'); //比赛名称
        $stime = strtotime($request->get('stime')); //开始时间
        $ftime = strtotime($request->get('ftime')); //结束时间
        $value = $request->get('value'); //排名段位
        $matchlog = new MatchLog();
        $arr = [];
        for ($i = 0; $i * 86400 + $stime <= $ftime; $i++) { //按天数遍历
            $date = date('Y-m-d',$i * 86400 + $stime);
            $arr[$date] = $matchlog
                ->select('ids')
                ->where('game',$game)
                ->where('name',$name)
                ->where('value',$value)
                ->where('date',$date)
                ->get()
                ->toArray();
        }
        $title = array_keys($arr);
        $datas = [];
        foreach ($arr as $k=>$v) {
            if (array_has($v,0)) {
                $datas[$k] = json_decode($v[0]['ids']);
            }else {
                $datas[$k] = '';
            }
        }
        $this->exported('比赛统计ID' . date('Ymdhis') . '.xls', $title, $datas);
    }

    public function getname($type)
    {
        $room = new MacthRoomCfg();
        $res = $room->select('id', 'match_name as name')->where('type', $type)->get()->toArray();
        return json_encode($res);
    }

    public function exported($filename, $title, $datas)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $filename);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, $title);
        $max = 0;
        foreach ($datas as $d) {
            if(is_array($d))
                $max = count($d) > $max ? count($d) : $max;
        }
        data_set($datas, '*.' . 0, '', false);
        for ($i = 0;$i<$max;$i++) {
            data_set($datas, '*.' . $i, '', false);
        }
        $datt = [];
        foreach ($datas as $k => $v) {
            foreach ($v as $ks => $vs) {
                $datt[$ks][$k] = $vs;
            }
        }
        foreach ($datt as $v) {
            fputcsv($fp, $v);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}
