<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;

use App\Models\Model;
/**
 * Description of Good
 *
 * @author 七彩P1
 */
class Goods extends Model {
    public  $connection="mysql_two";
    public $timestamps = true;
    public $table="goods";
    
    public $goods_type = ["1" => "游戏币道具", "2" => "VIP道具", "3" => "赠送道具", "4" => "首冲道具", "5" => "彩券道具",
        "6" => "电子兑换券", "7" => "实物道具", "8" => "抢庄道具", "9" => "救助道具", "10" => "保险箱道具", "11" => "记牌器", "12" => "日赛门票", "13" => "月赛门票",
        "14" => "惊喜礼包", "15" => "粽子道具", "16" => "大师积分",
        "19" => "钻石道具", "20" => "奖赛邀请函", "21" => "改名卡", "22" => "易容卡", "23" => "分数加成卡（排位赛）", "24" => "连胜卡（排位赛）"
    ];
    public $is_buy = ["1" => "可以购买", "2" => "不能购买"];
    public $show_type = ["1" => "金币商城", "2" => "钻石商城", "3" => "彩卷商城", "4" => "道具商城", "5" => "首冲道具", "6" => "任务奖励道具", "7" => "活动奖励道具", "8" => "一元抢购道具", "9" => "VIP道具"];
    public $is_show = ["1" => "显示", "2" => "不显示"];
    public $valid_time_type = ["1" => "购买后立即生效", "2" => "购买后不立即生效", "3" => "购买后隔天生效", "4" => "购买后在多久之内有效", "5" => "购买后每隔多久生效一次"];
    public $bp_isshow = ["1" => "显示", "0" => "不显示"];
    public $is_vip = ["0" => "无", "1" => "VIP等级一级", "2" => "VIP等级二级", "3" => "VIP等级三级"];
    public $game = ["1" => "斗牛", "2" => "斗地主"];
    public $ticket_show_type_arr = ["1" => "游戏道具", "2" => "电子卡券", "3" => "实物道具"];
    /*
     * 通知客户端有道具消耗 里面是道具类型
     * 2 VIP道具 11 记牌器 7 实物道具
     */
    public $notice_goods_type = [2, 11, 7];

    /* 那种类型可以获得游戏货币 请查看MoneyModel
      "uchip"=>1,//金钱
      "udiamond"=>2,//砖石
      "utombola"=>3 //彩券
     */
    public $goods_type_getCoin = ["1" => "uchip", "19" => "udiamond"];
    /*
     * 以下配置都是根据 $goods_type  设置的
     */
    /* 道具放入背包之后是否直接使用根据valid_time_type这个去进行过滤判断
     * 下面这个属性是不直接使用的
     * 
     */
    public $noDirectType = [
        "2", //购买后不立即生效
    ];
    //使用道具回调地址
    public $useGoodsCallBack = [
        "8" => [//"8"=>"救助道具"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addZhuangGoods",
        ],
        "9" => [//"9"=>"救助道具"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addMuCountGoods",
        ],
        "2" => [//"9"=>"VIP"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addVipGoods",
        ],
        "10" => [//"10"=>"保险箱道具"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addStrongBoxGoods",
        ],
        "12" => [//日赛门票
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addDayTicket",
        ],
        "13" => [//月赛门票
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "addMonthTicket",
        ],
        "14" => [//惊喜礼包
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "drawGoods",
        ],
        "7" => [//实物道具
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "realGoods",
        ],
        "16" => [//大师积分
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "masterIntegrate",
        ],
    ];
    //清除道具回调地址
    public $deleteGoodsCallBack = [
        "2" => [//"2"=>"VIP道具"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "deleteVipGoods",
        ],
        "10" => [//"10"=>"保险箱道具"使用时候回调方法跟类
            "class" => "\App\Server\Goods\CallBackGoods",
            "method" => "deleteStongBoxGoods",
        ],
    ];

    /*
     *  把道具类型goods_type 相同的道具跟购买时效性 整合成一个背包里面的道具 例如5天的保险箱跟30天的保险箱 进行叠加
     *  注意是根据
     * 
     * merge 1是只合并 有效时间 2合并 道具数量 
     */
    public $integGoodsType = [
        "10" => [
                [
                "gvalid_time_type" => [4], //例如保险箱道具下的 购买后在多久之内有效 道具能叠加
                "merge" => 1, //必须参数
            ],
        ],
        "11" => [
                [
                "gvalid_time_type" => [4], //
                "merge" => 1, //必须参数
            ],
                [
                "gvalid_time_type" => [2], //
                "merge" => 2, //必须参数
            ]
        ],
    ];

    public function __construct() {
        $this->game = config("config.game");
    }

    public function dealIntegGoodsType($ObGoods, $tableName) {
        if (isset($ObGoods['gtype']) && in_array($ObGoods['gtype'], array_keys($this->integGoodsType))) {
            foreach ($this->integGoodsType[$ObGoods['gtype']] as $value) {
                $ret = array();
                foreach ($value as $key => $v) {
                    if ($key == "merge") {
                        $ret['merge'] = $v;
                        unset($value[$key]);
                        continue;
                    }
                    if (isset($ObGoods[$key]) && in_array($ObGoods[$key], $v)) {
                        $ret["where"][$tableName . "." . $key] = $v;
                    }
                }
                if (isset($ret["where"]) && (count($ret["where"]) == count($value))) {
                    return $ret;
                }
            }
        }
        return FALSE;
    }
}
