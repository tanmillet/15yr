<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/9/26
 * Time: 10:05
 */

namespace App\Models\Game;

use App\Models\Game\base\DdzCfg;
use App\Models\Game\base\DdzRankData;
use Illuminate\Support\Facades\DB;

class SeasonModel extends DdzRankData
{
    /**
     * 统计当前赛季段位人数
     * @return mixed
     */
    public function getData()
    {
        //统计当前时间的数据
        $ddzcfg = new DdzCfg();
        $rtable = $ddzcfg->getTable();
        $ltable = $this->getTable();
        $res = $this->leftJoin($rtable, $ltable . '.season_id', $rtable . '.season')
            ->select(DB::raw('curdate() as date,count(*) as members'), $ltable . '.group', $ltable . '.order', $rtable . '.season', $ltable . '.game')
            ->where($rtable . '.status', '1')
            ->groupBy($ltable . '.group', $ltable . '.order', $ltable . '.season_id', $ltable . '.game')
            ->orderBy($ltable . '.season_id', $ltable . '.group', $ltable . '.game', $ltable.'.order')
            ->get();
        return $res;
    }

    public function getData2()
    {
        $ddzcfg = new DdzCfg();
        $rtable = $ddzcfg->getTable();
        $ltable = $this->getTable();
        $res = $this->leftJoin($rtable, $ltable . '.season_id', $rtable . '.season')
            ->where($rtable . '.status', '1')
            ->orderBy($ltable . '.season_id', $ltable . '.group', $ltable . '.game', $ltable.'.order')
            ->get();
        return $res;
    }
}