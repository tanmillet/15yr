<?php

namespace App\Http\Controllers\Game;

use App\Models\Count\base\SeasonCount;
use App\Models\Game\base\DdzCfg;
use App\Models\Game\SeasonModel;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\DdzRankCfg;

class SeasonController extends BaseController
{
    protected $season = ['1' => 'S1', '2' => 'S2'];
    protected $game = ['1' => '斗牛', '2' => '斗地主'];
    /**
     * 展示统计数据
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $date_type = "yyyy-mm-dd"; // 设置日期格式
        $seasons = (new DdzCfg())->select('season', 'ddz_name')->groupBy('season')->get()->toArray(); // 获取赛季
        $season = new SeasonCount();
        $where[0] = ['date', '>=', date('Y-m-d', strtotime('-1 day'))];
        $where[1] = ['date', '<=', date('Y-m-d', strtotime('-1 day'))];
        $where[2] = ['season', max(array_pluck($seasons, 'season'))];
        $where[3] = ['game', 2];
        if ($request->get('bdate')) {
            $where[0] = ['date', '>=', $request->get('bdate')];
        }
        if ($request->get('ldate')) {
            $where[1] = ['date', '<=', $request->get('ldate')];
        }
        if ($request->get('season')) {
            $where[2] = ['season', $request->get('season')];
        }
        if ($request->get('game')) {
            $where[3] = ['game', $request->get('game')];
        }
        if (empty($where))
            $res = $season
                ->orderBy('season', 'desc')
                ->orderBy('game', 'desc')
                ->orderBy('group', 'desc')
                ->orderBy('order', 'asc')
                ->get();
        else
            $res = $season
                ->where($where)
                ->orderBy('season', 'desc')
                ->orderBy('game', 'desc')
                ->orderBy('group', 'desc')
                ->orderBy('order', 'asc')
                ->get();
        $level = array();
        foreach ($res->toArray() as $k => $v) {
            $level[$v['group']][$v['order']] = $v['group'] . '-' . $v['order'];
        }
        $datas = $res->toArray();
        $times = $res->groupBy('date')->toArray();
        $group = $season->group;
        return view('admin.game.season.index', compact('level','group','times','date_type','seasons','datas'));
    }

    /**
     * 导出单行表格
     * @param Request $request
     */
    public function expord(Request $request)
    {
        if ($request->get('gr')) {
            $where['group'] = $request->get('gr');
        }
        if ($request->get('or')) {
            $where['order'] = $request->get('or');
        }
        if ($request->get('se')) {
            $where['season'] = $request->get('se');
        }
        if ($request->get('ga')) {
            $where['game'] = $request->get('ga');
        }
        $ldate = $request->get('ld') ? $request->get('ld') : date('Y-m-d');
        $rdate = $request->get('rd') ? $request->get('rd') : date('Y-m-d');
        $ids = new SeasonCount();
        $res = $ids->where($where)->where('date', '>=', $ldate)->where('date', '<=', $rdate)->get()->toArray();
        $order = (new DdzRankCfg())->orderArr;
        foreach ($res as $k => $v) {
            $datarr[$k]['game'] = $this->game[$v['game']];
            $datarr[$k]['season'] = $this->season[$v['season']];
            $datarr[$k]['group'] = $ids->group[$v['group']];
            $datarr[$k]['order'] = $order[$v['order']];
            $datarr[$k]['date'] = $v['date'];
            $datarr[$k]['members'] = $v['members'];
            $datarr[$k]['ids'] = $v['ids'];
        }
        $titarr = ['游戏', '赛季', '段位', '阶数', '时间', '人数', 'IDS'];
        $this->exportToExcel2('赛季段位统计' . date('YmdHis') . '.xls', $titarr, $datarr);
    }

    /**
     * 导出数据方法垂直格式
     * @param $filename
     * @param array $tileArray
     * @param array $dataArray
     */
    public function exportToExcel2($filename, $tileArray = [], $dataArray = [])
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $filename);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        $index = 0;
        $data = array();
        foreach ($dataArray as $key => $item) {
            if ($index == 1000) {
                $index = 0;
                ob_flush();
                flush();
            }
            foreach ($item as $k => $v) {
                if ($k == 'ids') {
                    $b = (array)json_decode($v);
                    foreach ($b as $kk => $vv) {
                        $item['id' . $kk] = $vv;
                    }
                    unset($item['ids']);
                }
            }
            $index++;
            $data[$index] = $item;
        }
        $y = 0;
        foreach ($data as $k => $v) {
            $data[$k] = array_flatten($v);
            $y = count($data[$k]) > $y ? count($data[$k]) : $y;
        }
        foreach ($data as $k => $v) {
            for ($i = 0; $i < $y; $i++) {
                $dat[$i][$k] = isset($data[$k][$i]) ? $data[$k][$i] : '';
            }
        }
        foreach ($dat as $k => $v) {
            $v[0] = isset($tileArray[$k]) ? $tileArray[$k] : "";
            ksort($v);
            fputcsv($fp, $v);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}
