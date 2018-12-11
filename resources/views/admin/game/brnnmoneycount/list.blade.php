@section('title', "金币统计管理")
@section('content_title', '百人牛牛金币统计列表')
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
                <div class="box-body" style="overflow:scroll;width:100%;">
                    <div class="row">
                        <form action="{{ url('game/brnnMonenyCount/index') }}" method="get" id="search">
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
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/brnnMonenyCount/index';">重置</button>
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
                    <h3 class="box-title">百人牛牛统计</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>日期</th>
                                <th>游戏</th>
                                @foreach($type_arr as $k=>$v)
                                <th>{{$v}}
                                </th>
                                @endforeach 
                            </tr>
                                @foreach($data as $date=>$value)
                                    <tr  >
                                        <th>{{$date}}</th>
                                        <th>
                                             @foreach(config("game.game") as $game)
                                                @if(Input::get('game') == $game['value'] )
                                                   {{$game['name']}}
                                                @endif
                                             @endforeach
                                        </th>
                                        @foreach($type_arr as $k=>$v)
                                        <th>{{isset($value[$k])?$value[$k]:0}}</th>
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