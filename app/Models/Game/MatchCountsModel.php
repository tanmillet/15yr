<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/10
 * Time: 15:00
 */

namespace App\Models\Game;

use App\Models\Game\base\MacthRoomCfg;
use App\Models\Game\base\UserMatchLog;
use Illuminate\Support\Facades\DB;

class MatchCountsModel
{


    public function getDate($game = 2,$name = [1],$stime,$ftime)
    {
        $where[0] = ['game',$game];
        $where[1] = ['created_at','>=',$stime];
        $where[2] = ['created_at','<',$ftime];
        $datas = (new UserMatchLog())
            ->where($where)
            ->whereIn('mrf_id', $name)
            ->select('game', 'mrf_id', 'uid', //game一般是固定的
                DB::raw('count(uid) as times,
                sum(case when rank = 0 or rank is null then 1 else 0 end) as cancel,
                sum(case when rank = 1 then 1 else 0 end) as one,
                sum(case when rank = 2 then 1 else 0 end) as two,
                sum(case when rank = 3 then 1 else 0 end) as three,
                sum(case when rank > 3 and rank <= 6 then 1 else 0 end) as four,
                sum(case when rank > 6 and rank <= 10 then 1 else 0 end) as seven,
                sum(case when rank > 11 and rank <= 20 then 1 else 0 end) as eleven,
                sum(case when rank > 21 and rank <= 50 then 1 else 0 end) as twenty'))
            ->groupBy('mrf_id')//按照比赛名称进行分组
            ->groupBy('uid')
            ->get()->toArray();
        $res = array_unique(array_column($datas, 'mrf_id')); //去除重复的
        $rr = (new MacthRoomCfg())->select('type', 'id', 'match_name as name')->get()->toArray();
        $arr = array();
        foreach ($res as $k => $v) {
            foreach ($datas as $key => $val) {
                if ($v == $val['mrf_id']) {
                    $arr[$v]['game'] = $val['game'];
                    $arr[$v]['type'] = array_column($rr, 'type', 'id')[$val['mrf_id']];
                    $arr[$v]['mrf_id'] = $val['mrf_id'];
                    if (empty($arr[$v]['members']))
                        $arr[$v]['members'] = 0;
                    $arr[$v]['members'] += 1;
                    if (empty($arr[$v]['times']))
                        $arr[$v]['times'] = 0;
                    $arr[$v]['times'] += $val['times'];
                    if (empty($arr[$v]['cancel']))
                        $arr[$v]['cancel'] = 0;
                    $arr[$v]['cancel'] += $val['cancel'];
                    if (empty($arr[$v]['one']))
                        $arr[$v]['one'] = 0;
                    $arr[$v]['one'] += $val['one'];
                    if (empty($arr[$v]['two']))
                        $arr[$v]['two'] = 0;
                    $arr[$v]['two'] += $val['two'];
                    if (empty($arr[$v]['three']))
                        $arr[$v]['three'] = 0;
                    $arr[$v]['three'] += $val['three'];
                    if (empty($arr[$v]['four']))
                        $arr[$v]['four'] = 0;
                    $arr[$v]['four'] += $val['four'];
                    if (empty($arr[$v]['seven']))
                        $arr[$v]['seven'] = 0;
                    $arr[$v]['seven'] += $val['seven'];
                    if (empty($arr[$v]['twenty']))
                        $arr[$v]['twenty'] = 0;
                    $arr[$v]['twenty'] += $val['twenty'];
                }
            }
        }
        return $arr;
    }
}