@section('title', "金币统计管理")
@section('content_title', '活动统计列表')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ $assets_url}}/plugins/select2/select2.min.css">
@endsection
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">搜索</h3>
                </div>
                <div class="box-body" >
                    <div class="row">
                        <form action="{{ url('game/active/index') }}" method="get" id="search">
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
                            <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                        </div>
                        <div class="form-group col-lg-2">
                            <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                        </div>
                        <div class="form-group col-lg-2">
                            <select name="active_type" class="form-control"  id="moneyType">
                                <option
                                    value="">请选择活动
                                </option>
                                @foreach($active_type_arr as $active_type=>$active_name)
                                <option
                                    @if(Input::get('active_type') == $active_type )
                                    {{ 'selected' }}
                                    @endif

                                    value="{{ $active_type}}">{{$active_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <button type="submit" class="btn btn-default col-md-5">搜索</button>
                        <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/active / index';">重置</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box">
        <!-- /.box-header -->
        <!--<div class="box-body">
            <div id="container" style="min-width:400px;height:400px"></div>
        </div>-->

        <div class="box-header with-border">
            <h3 class="box-title">{{ $active_type_arr[Input::get('active_type')] }}</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tr style="align:center">
                    <th  rowspan="2"  >日期</th>
                    @foreach($type_arr as $k=>$v)
                    <th colspan=2>
                        {{$v}}
                    </th>
                    @endforeach 
                </tr>
                <tr >

                    @foreach($type_arr as $k=>$v)
                    <th>
                        领取人数
                    </th>
                    <th>
                        @if(Input::get('active_type') ==20)
                        注册人数
                        @else
                        次数
                        @endif
                    </th>
                    @endforeach 
                </tr>
                @foreach($ret_data as $date=>$value)
                <tr  >
                    <th>{{$date}}</th>
                    @foreach($type_arr as $k=>$v)
                    <th>{{isset($value[$k])?$value[$k]["pop_num"]:0}}</th>
                    <th>{{isset($value[$k])?$value[$k]["num"]:0}}</th>
                    @endforeach 
                </tr>  
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
    language:  'zh-CN',
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
    });
    $('#datetimepicker1').datetimepicker({
    language:  'zh-CN',
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn:  1,
            autoclose: 1,
    });
    });
</script>
@endsection
@extends('layouts.admin')