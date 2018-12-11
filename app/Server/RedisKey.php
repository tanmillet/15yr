<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;

/**
 * Description of RedisKey
 *
 * @author 七彩P1
 */
class RedisKey {
    static public  $Key=[
            "rankList"=>"RANKLISTNEW",//排行榜
            "userRankList"=>"USERRANKLIST",//每个用户的排行榜
            "task"=>"TASK",//用户任务
            "orderLock"=>"ORDERLOCK",//用户订单
            "feedReward"=>"FEEDREWARD",//用户分享奖励
            "scareBuy"=>"SCAREBUY",//一元夺宝
            "robotNum"=>"ROBOTNUM",//机器人数量
            "scareBuyLog"=>"SCAREBUYLOG",//夺宝详情
            "scareBuyLogQiShuNum"=>"SCAREBUYLOGQISHUNUM",//夺宝详情期数
            "pastPeriodSbl"=>"PASTPERIODSBL",//夺宝详情往期
            "useMuMoney"=>"USERMUMONEY",//救济金
        
        
            "warningStrongBoxSet"=>"WARNINGSTRONGBOXSET",//预警保险箱存
            "warningStrongBoxGet"=>"WARNINGSTRONGBOXGET",//预警保险箱取
            "active_notice"=>"WARNINGACTIVENOTICE",//预警保险箱取
            "task_notice"=>"WARNINGTASKNOTICE",//预警保险箱取
            "order_notice"=>"WARNINGORDERNOTICE",//预警保险箱取
            "ordernumber_notice"=>"WARNINGORDERNUNBERNOTICE",//订单数量预警
        
        
            "gzh_access_token"=>"GZHACCESSTOKEN",//公众号的access_token
        
            "use_inventory"=>"USEINVENTORY",//抢购库存
            "dwsRankList"=>"DWSRANKLIST",//段位赛排行榜
            "dwsUserRankList"=>"DWSUSERRANKLIST",//用户段位赛排行榜
            "rollNotice"=>"ROLLNOTICE",//跑马灯
            "repeatSumbitOrder"=>"REPEATSUMBITORDER",//付费重复下单
            "NOTICESERVERCHANGMONEY"=>"NOTICESERVERCHANGMONEY",//通知子游戏server 消息列队
    ];
}
