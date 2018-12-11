@section('title', "金币场统计管理")
@section('content_title', '金币场统计列表')
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
                        <form action="{{ url('game/playcoincount/index') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <select name="game" class="form-control" id="moneyType">
                                    <option value="">请选择游戏</option>
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
                                <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd"
                                       value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control"
                                       placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd"
                                       value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control"
                                       placeholder="结束时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="play_type" class="form-control" id="play_type">
                                    <option
                                            value="">请选择玩法
                                    </option>
                                    @foreach($showplaygamearr as $key=>$value)
                                        <option
                                                @if(Input::get('play_type') ==$key )
                                                {{ 'selected' }}
                                                @endif
                                                value="{{$key}}">{{$value['value'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{$base_url}}/game/playcoincount/index';">重置
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box" style="overflow:scroll;width:100%;">
                <!-- /.box-header -->
                <!--<div class="box-body">
                    <div id="container" style="min-width:400px;height:400px"></div>
                </div>-->

                <div class="box-header with-border">
                    <h3 class="box-title">金币数据列表</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr class="active">
                            <th style="min-width:100px;text-align:center">场次</th>
                            <th>统计项</th>
                            @foreach($dateData as $date=>$value)
                            <th>{{$date}}</th>
                            @endforeach
                        </tr>
                         @foreach($room_type_arr as $room_type=>$va)
                            <tr>
                                <th  style="min-width:100px;vertical-align:middle;text-align:center" rowspan="{{count($count_type_arr)>1?count($count_type_arr)+1:1}}">{{$va}}</th>
                            </tr>        
                            @foreach($count_type_arr as $k=>$v)
                                <tr>
                                    <th>{{$v}}</th>
                                    @foreach($dateData as $date=>$value)
                                    <th>{{isset($data[$date][$room_type][$k])?$data[$date][$room_type][$k]:0}}</th>
                                    @endforeach
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

    $(function () {
        $('#datetimepicker').datetimepicker({
            language: 'zh-CN',
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn: 1,
            autoclose: 1,
             
        });
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN',
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn: 1,
            autoclose: 1,
    });


</script>
@endsection
@extends('layouts.admin')