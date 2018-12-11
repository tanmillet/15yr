@section('title', "夺宝统计")
@section('content_title', '夺宝统计')
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
                        <form action="{{ url('game/scare/list') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <select name="game" class="form-control" id="game">
                                    @foreach(config("game.game") as $game)
                                        <option
                                                @if( Input::get('game') == $game['value'] )
                                                {{ 'selected' }}
                                                @elseif($game['value'] == 2)
                                                {{ 'selected' }}
                                                @endif
                                                value="{{ $game['value'] }}">{{ $game['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="date" data-date-format="{{ $date_type }}"
                                       value="{{Input::get('date')}}" title="date" type="text" class="form-control"
                                       placeholder="{{ date('Y-m-d',strtotime('-1 day')) }}">
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{ $base_url }}/game/scare/list'">重置
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box" style="overflow:scroll;width:100%;">
                <div class="box-header with-border">
                    <h3 class="box-title">夺宝统计</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr class="active">
                            <th>商品</th>
                            <th>每日开奖次数</th>
                            <th>玩家夺宝次数</th>
                            <th>玩家中奖次数</th>
                            <th>中奖玩家ID</th>
                        </tr>
                        @if(!empty($datas))
                            @foreach($datas as $key=>$val)
                                <tr>
                                    <td>{{ $val['goodname'] }}</td>
                                    <td>{{ $val['dayopens'] }}</td>
                                    <td>{{ $val['playnums'] }}</td>
                                    <td>{{ $val['dayshuts'] }}</td>
                                    <td><a href="{{ $base_url }}/game/scare/explord?ga={{ $val['game'] }}&da={{ $val['date'] }}&go={{ $val['goodid'] }}&na={{ $val['goodname'] }}">导出ID</a></td>
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
</script>
@endsection
@extends('layouts.admin')