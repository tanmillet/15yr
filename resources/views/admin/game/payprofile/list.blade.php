@section('title', "支付管理")
@section('content_title', '付费概况')
@section('content')
<script src="{{$assets_url}}/highcharts/highcharts.js"></script>
<script src="{{$assets_url}}/highcharts/modules/exporting.js"></script>
<script src="{{$assets_url}}/highcharts/modules/oldie.js"></script>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">搜索</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <form action="{{ url('game/payprofile/index') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <select name="game" class="form-control"  id="moneyType">
                                            <option
                                                value="">请选择游戏
                                            </option>
                                            @foreach(config("game.game") as $game)
                                                <option
                                                @if(Input::get('game') == $game['value'] )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $game['value'] }}">{{$game['name'] }}
                                                </option>
                                            @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-lg-2">
                                    <select name="pfid" class="form-control"  id="pfid">
                                            <option
                                                value="">请选择渠道
                                            </option>
                                            @foreach(config("game.pfid") as $pfid=>$name)
                                                <option
                                                @if(Input::get('pfid') == $pfid )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{$pfid}}">{{$name}}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                             <div class="form-group col-lg-2">
                                    <select name="obuy_ref" class="form-control"  id="pfid">
                                            <option
                                                value="">请选择购买渠道
                                            </option>
                                            @foreach($obuy_ref_arr as $key=>$value)
                                                <option
                                                @if(Input::get('obuy_ref') == $key )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{$key}}">{{$value}}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="sdate" data-date-format="{{$date_type}}"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="{{$date_type}}" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="date_group" class="form-control"  id="date_group">
                                            <option
                                                value="">请选择数据显示
                                            </option>
                                            @foreach($date_group as $ke=>$v)
                                                <option
                                                @if(Input::get('date_group') == $ke )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $ke}}">{{$v }}
                                                </option>
                                            @endforeach
                                </select>
                            </div>
                             
 
                            <div class="form-group col-lg-2">
                                <select name="count_type" class="form-control"  id="count_type" onchange="subt()">
                                            <option
                                                value="">请选择统计类型
                                                
                                            </option>
                                            @foreach($count_type_arr as $ke=>$v)
                                                <option
                                                @if(Input::get('count_type') == $ke )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $ke }}">{{$v }}
                                                </option>
                                            @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/payprofile/index';">重置</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">支付数据列表</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>游戏</th>
                                <th>日期</th>
                                <th>渠道</th>
                                <th>购买渠道</th>
                                <th>订单数量</th>
                                <th>支付数量</th>
                                <th>订单成功率</th>
                                <th>支付人数</th>
                                <th>新增用户人数</th>
                                <th>获得的金额</th>
                                <th>DAU</th>
                                <th>付费渗透率</th>
                                <th>付费arpu</th>
                            </tr>
                                @if(!empty($now_data))
                                @foreach($now_data['payCount'] as $k=>$v)
                                <tr class="active">
                                    <th>
                                         @foreach(config("game.game") as $game)
                                            @if($v['game'] == $game['value'] )
                                               {{$game['name']}}
                                            @endif
                                         @endforeach
                                    </th>
                                    <th>{{$now_data['date']}}</th>
                                     <th>
                                         {{config("game.pfid.".$v['pfid'])}}
                                    </th>
                                    <th>
                                         {{$obuy_ref_arr[$v['obuy_ref']]}}
                                    </th>
                                    <th>{{$v['order_num']}}</th>
                                    <th>{{$v['pay_num']}}</th>
                                    <th>{{$v['order_num']?(round($v['pay_num']/$v['order_num'],4) * 100) ."%":0}}</th>
                                    <th>{{$v['pay_pop_num']}}</th>
                                    <th>{{$v['new_pay_pop_num']}}</th>
                                    <th>{{$v['pay_price']}}</th>
                                    
                                    <th>{{$v['active_use_cout']}}</th>
                                    <th>{{$v['active_use_cout']?(round($v['pay_pop_num']/$v['active_use_cout']) * 100) ."%":0}}</th>
                                    <th>{{$v['pay_pop_num']?(round($v['pay_price']/$v['pay_pop_num']) * 100) ."%":0}}</th>
                                </tr>  
                                @endforeach 
                                @endif
                        
                                @if(!empty($count))
                                @foreach($count as $k=>$v)
                                <tr >
                                    <td>
                                        @foreach(config("game.game") as $game)
                                        @if($v['game'] == $game['value'] )
                                           {{$game['name']}}
                                        @endif
                                     @endforeach
                                    </td>
                                    <td>{{$v['date']}}</td>
									<th>
                                         {{config("game.pfid.".$v['pfid'])}}
                                    </th>
                                    <th>
                                         {{isset($obuy_ref_arr[$v['obuy_ref']])?$obuy_ref_arr[$v['obuy_ref']]:$v['obuy_ref']}}
                                    </th>
                                    <th>{{$v['order_num']}}</th>
									
                                    <th>{{$v['pay_num']}}</th>
                                    <th>{{$v['order_num']?(round($v['pay_num']/$v['order_num'],4) * 100) ."%":0}}</th>
                                    <th>{{$v['pay_pop_num']}}</th>
                                    <th>{{$v['new_pay_pop_num']}}</th>
                                    <th>{{$v['pay_price']}}</th>
                                    
                                    <th>{{$v['active_use_cout']}}</th>
                                    <th>{{$v['active_use_cout']?(round($v['pay_pop_num']/$v['active_use_cout']) * 100) ."%":0}}</th>
                                    <th>{{$v['pay_pop_num']?(round($v['pay_price']/$v['pay_pop_num']) ):0}}</th>
                                </tr>  
                                @endforeach 
                                @endif
                        </table>
                    </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<script>
            function show(id){
            layer.open({
            title:"<h3 style='line-height:40px'>实物发货</h3>",
                    type: 2,
                    content: '{{$base_url}}game/realGood/show/' + id,
                    area: ['1000px', '700px'],
            });
            }


   /* var chart = Highcharts.chart('container', {
    chart: {
    type: 'line'
    },
            title: {
            text: '用户在线统计'
            },
            subtitle: {
            text: ''
            },
            xAxis: {
            categories: [
                    @if(!empty($x))
                    @foreach($x as $kl=>$xname)
                        "{{$xname}}",
                    @endforeach 
                    @endif
            ]
            },
            yAxis: {
            title: {
            text: '人数（个）'
            }
            },

            plotOptions: {
                line: {
                        dataLabels: {
                        // 开启数据标签
                        enabled: true
                        },
                        // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                        enableMouseTracking: true
                },
            },
            /*series: [{
            name: "东京",
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
            name: "伦敦",
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]*/
          /*  series:[
                    @if(!empty($x))
                    @foreach($retdata as $name=>$data)
                        {
                        name: {{$data['name']}},
                        data: {{json_encode($data['data'])}}
                        },
                    @endforeach 
                    @endif
            ],
            
            });*/
            
            
            /*$('#datetimepicker').datetimepicker({
		Date:'yyyy-mm-dd',
		format: 'yyyy-mm-dd',
		language:'zh-CN',
		autoclose:true,
		minView:'month',
		maxView:1,
		todayBtn:'linked',
		showMeridian:false,
	});*/
        
        
    $(function () {  
      $('#datetimepicker').datetimepicker({
        language:  'zh-CN',
        @if(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        @elseif(Input::get('count_type') ==6)//周
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
            daysOfWeekDisabled: [0,2,3,4,5,6]
        @endif
    });
     $('#datetimepicker1').datetimepicker({
        language:  'zh-CN',
        @if(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        @elseif(Input::get('count_type') ==6)//周
             minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
            daysOfWeekDisabled: [0,2,3,4,5,6]
        @endif
    });
}); 

function subt(){
    location.href ='{{$base_url}}/game/payprofile/index/?count_type='+$("#count_type").val();
}
</script>
@endsection
@extends('layouts.admin')