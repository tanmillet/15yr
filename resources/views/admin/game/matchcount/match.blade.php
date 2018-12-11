@section('title', "比赛统计")
@section('content_title', '比赛统计')
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
                <div class="box-body">
                    <div class="row">
                        <form action="{{ url('game/matchcount/match') }}" method="get" id="search">
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
                                <select name="type" class="form-control" id="type">
                                    <option value="">比赛类型</option>
                                    @foreach($rr as $k=>$t)
                                        <option @if(Input::get('type') == $k )
                                                {{ 'selected' }}
                                                @endif
                                                value="{{ $k }}">{{ $types[$k] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="name" class="form-control" id="name">
                                    <option value="">比赛名称</option>
                                    @foreach($nrr as $k=>$t)
                                        <option @if(Input::get('name') == $k )
                                                {{ 'selected' }}
                                                @endif
                                                value="{{ $k }}">{{ $t['name'] }}</option>
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
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{$base_url}}/game/matchcount/match'">重置
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="active">
                            <th>统计项</th>
                            @foreach($arr as $k=>$v)
                                <th>{{ $k }}</th>
                            @endforeach
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($guide as $k=>$v)
                            <tr>
                                <td>{{ $v }}</td>
                                @foreach($arr as $ke=>$va)
                                <td>{{ $va[1][$k] }}</td>
                                @endforeach
                                @if(!in_array($k,['match_times','members','times','cancel_times']))
                                <td><a href="{{ $base_url }}/game/matchcount/expold?game={{Input::get('game')}}&name={{Input::get('name')}}&stime={{Input::get('sdate')}}&ftime={{Input::get('fdate')}}&value={{ $k }}">导出ID</a></td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('#datetimepicker,#datetimepicker1').datetimepicker({
        language: 'zh-CN',
        minView: "month",
        todayBtn: 1,
        autoclose: 1,
    });
    $("#type").change(function(){
        var type=$(this).val();
        if(type.length > 0){
            $("#name").html('<option value="">比赛名称</option>');
            $.get('{{ $base_url }}/game/matchcount/getname/'+type, function(result){
                result = JSON.parse(result);
                result.forEach(function (v,i,arr) {
                    var htm = '<option value="'+v['id']+'">'+v['name']+'</option>';
                    $("#name").append(htm);
                });
            });
        }
    });
</script>
@endsection
@extends('layouts.admin')