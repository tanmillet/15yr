<?php

//auth
Route::group(['prefix' => '/auth', 'namespace' => "Auth", 'middleware' => ['csrf']], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::post('logout', 'LoginController@logout')->name('logout');
});


//
Route::group(['prefix' => '/', 'middleware' => ['auth', 'permission']], function () {
    Route::resource('/menu/tree', 'MenuController@tree');
    Route::resource('/menu', 'MenuController');
    Route::get('/role/{id}/permission', 'RoleController@permissionEdit');
    Route::post('/role/{id}/permission', 'RoleController@permissionStore');
    Route::resource('/role', 'RoleController');
    Route::resource('/user', 'UserController');
    Route::resource('/permission', 'PermissionController');
    Route::resource('/info', 'InfoController');
    Route::resource('/log', 'LogController');
    Route::resource('/loginLog', 'LoginLogController');

    Route::resource('/', 'HomeController');
});

Route::group(['prefix' => '/game', 'namespace' => "Game", 'middleware' => ['csrf', 'auth', 'permission']], function () {
    Route::get('/gameuserinfo', 'UserController@index');
    Route::get('/showmoneny/{uid}', 'UserController@showMoney');
    Route::get('/showmonenyLog/{uid}', 'UserController@showMoneyLog');
    Route::get('/userlog/{uid}', 'UserController@userlog');

    Route::post('/opearymoneny', 'UserController@operateMoney');

    Route::get('/realGood/index', 'SendRealGoodController@index');
    Route::get('/realGood/show/{uid}', 'SendRealGoodController@show');
    Route::put('/realGood/update/{id}', 'SendRealGoodController@update');

    Route::get('/online/index', 'OnlineController@index');
    Route::get('/online/show/{uid}', 'OnlineController@show');

    Route::get('/paycount/index', 'PayCountController@index');
    Route::get('/order/index', 'OrderController@index');
    Route::get('/order/show/{id}', 'OrderController@show');

    Route::get('/paycount/now_data', 'PayCountController@nowData');
    Route::get('/paycount/payRank', 'PayCountController@payRank');//支付排行榜

    Route::get('/payprofile/index', 'PayProfileCountController@index');//支付概况

    Route::get('/payprofile/index', 'PayProfileCountController@index');//支付概况

    Route::get('/paygoods/index', 'PayGoodsCountController@index');//商品分析
    Route::get('/payscene/index', 'PaySceneCountController@index');//付费场景

    Route::get('/coin', 'CoinCountController@index');
    Route::get('/coin/rank', 'CoinRankController@index');
    Route::get('/coin/change', 'CoinRankController@change');
    Route::get('/scare/list', 'LuckDrawController@index');
    Route::get('/scare/explord', 'LuckDrawController@explord');

    Route::get('/loginlog/list', 'UserController@loginLog');
});


Route::group(['prefix' => '/game', 'namespace' => "Game", 'middleware' => ['csrf', 'auth', 'permission']], function () {
    Route::get('/scarebuy/index', 'ScareBuyController@index');
    Route::get('/scarebuy/show/{id}', 'ScareBuyController@show');
    Route::post('/scarebuy/opeary/{id}', 'ScareBuyController@opeary');
    Route::put('/scarebuy/opeary/{id}', 'ScareBuyController@opeary');
    Route::post('/scarebuy/delete/{id}', 'ScareBuyController@delete');


    Route::get('/notice/index', 'NoticeController@index');
    Route::get('/notice/show/{id}', 'NoticeController@show');
    Route::post('/notice/opeary/{id}', 'NoticeController@opeary');
    Route::put('/notice/opeary/{id}', 'NoticeController@opeary');


    Route::get('/goods/index', 'GoodsController@index');
    Route::get('/goods/show/{id}', 'GoodsController@show');
    Route::post('/goods/opeary/{id}', 'GoodsController@opeary');
    Route::put('/goods/opeary/{id}', 'GoodsController@opeary');

    Route::get('/moneycount/index', 'MoneyCountController@index');//金币统计

    Route::get('/test/test', 'TestController@test');//


    Route::get('/bwscount/index', 'BwsCountController@index');//宾王赛统计
    Route::get('/bwscount/ranklist', 'BwsCountController@rankList');//宾王赛统计


    Route::get('/feedback/index', 'FeedBackController@index');//反馈
    Route::get('/feedback/show/{id}', 'FeedBackController@show');//显示回复反馈
    Route::post('/feedback/opeary/{id}', 'FeedBackController@opeary');//回复反馈


    Route::get('/mobilesmslog/index', 'MoblieSmsLogController@index');//手机发送信息
    Route::get('/mobilesmslog/show', 'MoblieSmsLogController@show');//发送短信
    Route::post('/mobliesmslog/opeary', 'MoblieSmsLogController@opearySend');//发送短信

    Route::get('/brnnMonenyCount/index', 'BrnnMoneyController@index');//百人牛牛统计


    Route::get('/active/index', 'ActiveController@index');//活动统计
    Route::get('/matchcount/index', 'MatchCountController@index');//比赛统计统计
    Route::get('/matchcount/match', 'MatchCountController@match');//比赛统计统计
    Route::get('/matchcount/getname/{type}', 'MatchCountController@getname');//比赛统计统计
    Route::get('/matchcount/expold', 'MatchCountController@expold');//比赛统计统计

    Route::get('/dws/index', 'DwsController@index');//排位赛排行榜详情
    Route::get('/dws/show/{id}', 'DwsController@show');//排位赛排行榜详情
    Route::post('/dws/opeary/{id}', 'DwsController@opeary');//排位赛排行榜详情

    Route::get('/zfbmoneycount/index', 'ZfbMoneyCountController@index');//中发白统计
    Route::get('/cardlog/index', 'CardLogController@index');//中发白统计

    Route::get('/playcoincount/index', 'PlayCoinCountController@index');//金币场统计

    Route::get('/verconfig/index', 'VerConfigController@index');//游戏版本列表
    Route::get('/verconfig/show/{id}', 'VerConfigController@show');//添加或者修改列表
    Route::post('/verconfig/opeary/{id}', 'VerConfigController@opeary');//添加或者修改

    Route::post('/common/UploadFile/{uploadType}', 'CommonController@UploadFile');//上传文件

    Route::get('/rollnotice/index/', 'RollNoticeController@index');//跑马灯列表
    Route::get('/rollnotice/show/{id}', 'RollNoticeController@show');//跑马灯添加或者修改列表
    Route::post('/rollnotice/opeary/{id}', 'RollNoticeController@opeary');//添加或者修改
    Route::post('/rollnotice/delete/{id}', 'RollNoticeController@delete');
    Route::post('/rollnotice/publish/{id}', 'RollNoticeController@publish');//发布
});

Route::group(['prefix' => '/season', 'namespace' => "Game", 'middleware' => ['csrf', 'auth', 'permission']], function () {
    Route::get('/list', 'SeasonController@index');
    Route::get('/expord', 'SeasonController@expord');
});