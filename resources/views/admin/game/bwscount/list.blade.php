@section('title', "宾王赛统计管理")
@section('content_title', '详细列表')
@section('content')

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
                            <form action="{{ url('game/bwscount/index') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <select name="game" class="form-control"  id="game">
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
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}game/bwscount/index';">重置</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">详情列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered" style="text-align: center;">
                            <tr class="active">
                                <td rowspan="2" >日期</td>
                                <td colspan="7">门票送出</td>
                                <td colspan="3">消耗</td>
                            </tr>
                            <tr class="active">
                                
                                @foreach ($ref_arr as $title) 
                                 <td>{{ $title }}</td>
                                @endforeach
                            </tr>
                            @foreach ($ret_data as $date=>$item)
                            <tr>
                                <td>{{ $date }}</td>
                                 @foreach ($ref_arr as $ref=>$title) 
                                 <td>{{ isset($item[$ref])?$item[$ref]:0 }}</td>
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