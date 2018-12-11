@section('title', "用户在线管理")
@section('content_title', '用户在线列表')
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
                        <form action="{{ url('game/paycount/index') }}" method="get" id="search">
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
                                <select name="data_type" class="form-control"  id="data_type">
                                            <option
                                                value="">请选择显示数据
                                            </option>
                                            @foreach($data_type_arr as $ke=>$v)
                                                <option
                                                @if(Input::get('data_type') == $ke )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $ke }}">{{$v }}
                                                </option>
                                            @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="obuy_type" class="form-control"  id="obuy_type_arr">
                                            <option
                                                value="">请选择支付类型
                                            </option>
                                            @foreach($obuy_type_arr as $ke=>$v)
                                                <option
                                                @if(Input::get('obuy_type') == $ke )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $ke }}">{{$v }}
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
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/paycount/index';">重置</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">用户列表</h3>
                </div>
                <!-- /.box-header -->
                <!--<div class="box-body">
                    <div id="container" style="min-width:400px;height:400px"></div>
                </div>-->
                
                <div class="box-header with-border">
                    <h3 class="box-title">支付数据列表</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>游戏</th>
                                <th>平台</th>
                                <th>子平台</th>
                                <th>日期</th>
                                <th>时分</th>
                                <th>订单数量</th>
                                <th>支付数量</th>
                                <th>订单成功率</th>
                                <th>支付人数</th>
                                <th>新增用户人数</th>
                                <th>购买方式</th>
                                <th>获得的金额</th>
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
                                    <th>{{$v['pfid']}}</th>
                                    <th>{{$v['usid']}}</th>
                                    <th>{{$now_data['date']}}</th>
                                    <th>{{$now_data['hour_min']}}</th>
                                    <th>{{$v['order_num']}}</th>
                                    <th>{{$v['pay_num']}}</th>
                                    <th>{{round($v['pay_num']/$v['order_num'],4) * 100 ."%"}}</th>
                                    <th>{{$v['pay_pop_num']}}</th>
                                    <th>{{$v['new_pay_pop_num']}}</th>
                                    <th>{{$obuy_type_arr[$v['obuy_type']]}}</th>
                                    <th>{{$v['pay_price']}}</th>
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
                                    <td>{{$v['pfid']}}</td>
                                    <td>{{$v['usid']}}</td>
                                    <td>{{$v['date']}}</td>
                                    <td>{{$v['hour_min']}}</td>
                                    <td>{{$v['order_num']}}</td>
                                    <td>{{$v['pay_num']}}</td>
                                    <th>{{round($v['pay_num']/$v['order_num'],2)}}</th>
                                    <td>{{$v['new_pay_pop_num']}}</td>
                                    
                                    <td>{{$v['pay_pop_num']}}</td>
                                    <td>{{isset($obuy_type_arr[$v['obuy_type']])?$obuy_type_arr[$v['obuy_type']]:$v['obuy_type']}}</td>
                                    <td>{{$v['pay_price']}}</td>
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
        @if(Input::get('count_type') ==1)//分
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        @elseif( Input::get('count_type')==2)//小时
            autoclose: true,
            minView: 'hour',
            minuteStep:60,
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        @elseif(Input::get('count_type') ==5)//年
             autoclose: true,
            todayBtn: true,
            startView: 4, 
            minView: 4,
            autoclose: 1,
        @endif
    });
     $('#datetimepicker1').datetimepicker({
        language:  'zh-CN',
        @if(Input::get('count_type') ==1)//分
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        @elseif( Input::get('count_type')==2)//小时
            autoclose: true,
            minView: 'hour',
            minuteStep:60,
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
        @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        @elseif(Input::get('count_type') ==5)//年
             autoclose: true,
            todayBtn: true,
            startView: 4, 
            minView: 4,
            autoclose: 1,
        @endif
    });
}); 

function subt(){
    location.href ='{{$base_url}}/game/paycount/index/?count_type='+$("#count_type").val();
}
</script>
@endsection
@extends('layouts.admin')