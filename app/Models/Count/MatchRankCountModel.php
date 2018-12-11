<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/12
 * Time: 14:58
 */

namespace App\Models\Count;

use App\Models\Count\base\MatchLog;
use App\Models\Game\base\MacthRoomCfg;
use App\Models\Game\base\UserMatchLog;

class MatchRankCountModel
{
    public function arstianCount()
    {
        //获取当日时间区间
        $stime = strtotime(date('Y-m-d'));
        $ftime = strtotime(date('Y-m-d', strtotime('+1 day')));
        //判断当日的记录是否已经保存
        $matchlog = new MatchLog();
        $res = $matchlog->where('date', date('Y-m-d'))->first();
        //如果当日已经有日志了，本次操作停止
        if($res)
            return;
        //获取类型与名称对应
        $tton = (new MacthRoomCfg())->select('type', 'id', 'match_name as name')->get()->toArray();
        $ttom = array_column($tton, 'type', 'id'); //index=>mrf_id;value=>type;
        //获取当日有人参加的比赛名
        $userlog = new UserMatchLog();
        $datas = $userlog
            ->where('created_at','>=',$stime)
            ->where('created_at','<',$ftime)
            ->select('game', 'mrf_id', 'uid')->get()->toArray();
        $mids = array_unique(array_column($datas, 'mrf_id')); //去除重复的比赛名
        //统计项
        $t = [
            'match_times' => [['created_at','>',1]], //比赛场次
            'members' => [['created_at', '>', 1]], //参赛人数
            'times' => [['created_at', '>', 1]], //参赛次数
            'cancel' => [['rank', 0]], //取消次数
            'one' => [['rank', 1]],
            'two' => [['rank', 2]],
            'three' => [['rank', 3]],
            'four' => [['rank', '>=', 4], ['rank', '<=', 6]],
            'seven' => [['rank', '>=', 7], ['rank', '<=', 10]],
            'eleven' => [['rank', '>=', 11], ['rank', '<=', 20]],
            'twenty' => [['rank', '>=', 21], ['rank', '<=', 50]]
        ];
        //查询数据
        $datas = [];
        foreach ($t as $ke => $va) { //ke=>统计名,$va=>统计条件
            $datas[$ke] = $userlog
                ->select('match_id','uid', 'game', 'mrf_id as name')
                ->where('created_at','>=',$stime)
                ->where('created_at','<',$ftime)
                ->whereIn('mrf_id', $mids)
                ->where($va)
                ->get()
                ->groupBy('name')
                ->toArray();
        }
        //数据整理
        $arr = array();
        foreach ($datas as $key => $value) { //$key=>排名;$value=>比赛
            foreach ($value as $k => $v) { //$k=>比赛名;$v=>该比赛获得该排名的游戏玩家信息
                $arr[$k . $key]['game'] = $v[0]['game'];
                $arr[$k . $key]['type'] = $ttom[$v[0]['name']];
                $arr[$k . $key]['name'] = $v[0]['name'];
                $arr[$k . $key]['date'] = date('Y-m-d');
                $arr[$k . $key]['value'] = $key;
                if($key == 'members') {
                    $arr[$k . $key]['nums'] = count(array_unique(array_pluck($v, 'uid')));
                } elseif($key == 'match_times') {
                    $arr[$k . $key]['nums'] = count(array_unique(array_pluck($v, 'match_id'))); //取出所有match_id去重，求数量
                } else {
                    $arr[$k . $key]['nums'] = count($v);
                }
                if ($key == 'times' || $key == 'members' || $key == 'match_times') {
                    $arr[$k . $key]['ids'] = '';
                } else {
                    $arr[$k . $key]['ids'] = json_encode(array_pluck($v, 'uid'));
                }
            }
        }
        $matchlog->insert($arr);
    }
}