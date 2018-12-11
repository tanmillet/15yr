@section('title', "统计管理")
@section('content_title', '宾王赛详情列表')
@section('content_title_small',  $pager->total())
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
                            <form action="{{ url('game/bwscount/ranklist') }}" method="get">
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
                                    <input id="datetimepicker" name="date" data-date-format="yyyy-mm-dd"  value="{{Input::get('date')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                                </div>

                                <div class="form-group col-lg-2">
                                    <select name="mrf_id" class="form-control"  id="game">
                                            <option
                                                value="">请选择场次
                                            </option>
                                            @foreach($mrf_id_arr as $mrf_id=>$name)
                                                <option
                                                @if(Input::get('mrf_id') == $mrf_id )
                                                   {{ 'selected' }}
                                                @endif
                                                value="{{ $mrf_id }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}game/bwscount/ranklist';">重置</button>
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
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>排名</th>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>参赛场次</th>
                                <th>获奖次数</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>{{ $item['rank'] }}</td>
                                <td>{{ $item['uid'] }}</td>
                                <td>{{ $item['uname'] }}</td>

                                <td>{{ $item['join_num'] }}</td>
                                <td>{{ $item['reard_num'] }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @include('admin.common.pager')
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
<script>

     $('#datetimepicker').datetimepicker({
        language:  'zh-CN',
        minView: "month", //选择日期后，不会再跳转去选择时分秒 
        todayBtn:  1,
        autoclose: 1,

    });


</script>

@endsection
@extends('layouts.admin')