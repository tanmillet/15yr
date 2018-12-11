@section('title', "玩家金币总览")
@section('content_title', '玩家金币总览列表')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ $assets_url}}/plugins/select2/select2.min.css">
@endsection
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
                        <form action="{{ url('game/coin') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <select name="game" class="form-control" id="game">
                                    <option value="">请选择游戏</option>
                                    @foreach(config("game.game") as $game)
                                    <option
                                        @if(Input::get('game') == $game['value'] )
                                        {{ 'selected' }}
                                        @endif
                                        value="{{ $game['value'] }}">{{ $game['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <select name="data_type" class="form-control" id="data_type">
                                <option value="">请选择类型</option>
                                @foreach($data_type_arr as $ke=>$value)
                                <option
                                    @if(Input::get('data_type') == $ke )
                                    {{ 'selected' }}
                                    @endif
                                    value="{{$ke}}">{{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <input id="datetimepicker" name="ldate" data-date-format="{{ $date_type }}"
                               value="{{Input::get('ldate')}}" title="ldate" type="text" class="form-control"
                               placeholder="开始时间">
                    </div>
                    <div class="form-group col-lg-2">
                        <input id="datetimepicker1" name="rdate" data-date-format="{{ $date_type }}"
                               value="{{Input::get('rdate')}}" title="rdate" type="text" class="form-control"
                               placeholder="结束时间">
                    </div>
                    <div class="form-group col-lg-2">
                        <button type="submit" class="btn btn-default col-md-5">搜索</button>
                        <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                onclick="location.href ='{{ $base_url }}/game/coin'">重置
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="container" style="overflow:scroll;min-width:400px;height:400px;margin-bottom: 15px"></div>
    <div class="box" style="overflow:scroll;width:100%;">
        <div class="box-header with-border">
            <h3 class="box-title">玩家金币总览</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tr class="active">
                    <th>日期</th>
                    @foreach($types as $k=>$v)
                    <th>{{ $v }}</th>
                    @endforeach
                </tr>
                @if(!empty($datas))
                @foreach($datas as $key=>$val)
                <tr>
                    <td>{{ substr($val['date'],0,-9) }}</td>
                    @foreach($types as $k=>$v)
                    <th>
                        @if($val[Input::get('data_type')] =="")
                        0
                        @else
                        <?php $dataArr = json_decode($val[Input::get('data_type')]) ?>
                        {{$dataArr[$k]}}
                        @endif
                    </th>
                    @endforeach

                </tr>
                @endforeach
                @endif
            </table>
        </div>
    </div>
</div>
</div>
</section>

<script>
    $(function () {
    $('#datetimepicker,#datetimepicker1').datetimepicker({
    language:  'zh-CN',
            minView: "month",
            todayBtn:  1,
            autoclose: 1,
    });
    });
    var chart = Highcharts.chart('container', {
    chart: {
    type: 'column'
    },
            title: {
            text: '玩家金币总览'
            },
            xAxis: {
            categories: {!! json_encode($types) !!}
            },
            yAxis: {
            title: {
            text: '玩家总量',
                    align: 'high'
            }
            },
            tooltip: {
            pointFormat: '玩家人数: <b>{point.y} 人</b>'
            },
            series: {!! $da !!}
    });
</script>
@endsection
@extends('layouts.admin')