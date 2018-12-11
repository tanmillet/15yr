@section('title', "玩家金币变化排行榜")
@section('content_title', '玩家金币变化排行榜')
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
                        <form action="{{ url('game/coin/change') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="date" data-date-format="{{ $date_type }}"
                                       value="{{ Input::get('date') }}" title="date" type="text" class="form-control"
                                       placeholder="{{ Input::get('jishi')?'即使数据':date('Y-m-d',strtotime('-1 day')) }}">
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{ $base_url }}/game/coin/change?jishi=1'">即时数据
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box" style="overflow:scroll;width:100%;">
                <div class="box-header with-border">
                    <h3 class="box-title">玩家金币变化排行榜</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr class="active">
                            <th>排名</th>
                            <th>玩家ID</th>
                            <th>昵称</th>
                            <th>初始金币</th>
                            <th>截至金币</th>
                            <th>变化金币</th>
                            <td>日常</td>
                            <td>活动</td>
                            <td>经典场</td>
                            <td>癞子场</td>
                            <td>排位赛</td>
                            <td>比赛</td>
                            <td>娱乐场</td>
                            <td>夺宝</td>
                            <td>充值</td>
                            <td>其他</td>
                        </tr>
                        @if(!empty($datas))
                            @foreach($datas as $key=>$val)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val['uid'] }}</td>
                                    @if(empty($val['nickname']))
                                        <td>NULL</td>
                                    @else
                                    <td>{{ $val['nickname'] }}</td>
                                    @endif
                                    <td>{{ $val['beforecoin'] }}</td>
                                    <td>{{ $val['aftercoin'] }}</td>
                                    <td>@if($val['win'] == 1)
                                            {{ $val['changecoin'] }}
                                        @else
                                            -{{ $val['changecoin'] }}
                                        @endif
                                    </td>
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