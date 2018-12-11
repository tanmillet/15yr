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
                        <form action="{{ url('game/online/index') }}" method="get">
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
                                <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="date_group" class="form-control"  id="date_group">
                                            <option
                                                value="">请选择游戏
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
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/online/index';">重置</button>
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
                <div class="box-body">
                    <div id="container" style="min-width:400px;height:400px"></div>
                </div>
                
                <div class="box-header with-border">
                    <h3 class="box-title">实时在线人数</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>游戏</th>
                                <th>平台</th>
                                <th>子平台</th>
                                <th>人数</th>
                                <th>在线总时长(分)</th>
                            </tr>
                            @foreach ($now_data as $pfid=>$item)
                                @foreach ($item as $suid=>$it)
                            <tr>
                                <td>
                                    @foreach(config("game.game") as $game)
                                        @if(Input::get('game') == $game['value'] )
                                           {{$game['name']}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$pfid}}</td>
                                <td>{{ $suid}}</td>
                                <td>{{ $it['user_num'] }}</td>
                                <td>{{$it['time_num'] }}</td>
                            </tr>
                                @endforeach
                            @endforeach
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


    var chart = Highcharts.chart('container', {
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
                    @foreach($x as $kl=>$xname)
                        "{{$xname}}",
                    @endforeach 
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
            series:[
                   @foreach($retdata as $name=>$data)
                        {
                        name: {{$data['name']}},
                        data: {{json_encode($data['data'])}}
                        },
                    @endforeach 
            ],
            
            });
            
            
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
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
        $('#datetimepicker1').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
}); 
</script>
@endsection
@extends('layouts.admin')