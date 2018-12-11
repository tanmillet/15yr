@section('title', "赛季统计")
@section('content_title', '赛季段位统计列表')
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
                        <form action="{{ url('season/list') }}" method="get" id="search">
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
                                <select name="season" class="form-control" id="season">
                                    <option value="">请选择赛季</option>
                                    @foreach($seasons as $k=>$val)
                                        <option
                                                @if(Input::get('season') == $val['season'] )
                                                {{ 'selected' }}
                                                @endif
                                                value="{{ $val['season'] }}">{{ $val['ddz_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="bdate" data-date-format="{{ $date_type }}"
                                       value="{{Input::get('bdate')}}" title="bdate" type="text" class="form-control"
                                       placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="ldate" data-date-format="{{ $date_type }}"
                                       value="{{Input::get('ldate')}}" title="ldate" type="text" class="form-control"
                                       placeholder="结束时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{ $base_url }}/season/list'">重置
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box" style="overflow:scroll;width:100%;">
                <div class="box-header with-border">
                    <h3 class="box-title">段位人数统计</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr class="active">
                            <th>段位</th>
                            <th>阶数</th>
                            @foreach($times as $k=>$v)
                                <th>{{ substr($k,0,-9).' / 人数' }}</th>
                            @endforeach
                            <th>操作</th>
                        </tr>
                        @if(!empty($datas))
                            @foreach($level as $key=>$val)
                                @foreach($val as $k=>$v) {{-- 按照段位进行排序 --}}
                                <tr>
                                    <td>{{ $group[$key] }}</td> {{-- 输出段位 --}}
                                    <td>{{ $k }}</td> {{-- 输出阶数 --}}
                                    @foreach($times as $ke=>$ve) {{-- 按时间遍历 --}}
                                    <?php $aa = 0 ?>
                                    @foreach($datas as $ka=>$va) {{-- 按条数进行遍历 --}}
                                    @if($va['date'] == $ke && $va['group'] == $key && $va['order'] == $k)
                                        <td>{{ $va['members'] }}</td>
                                        <?php $aa = 1 ?>
                                    @endif
                                    @endforeach
                                    @if($aa==0)
                                        <td>0</td>
                                    @endif
                                    @endforeach
                                    <td>
                                        <a href="{{ $base_url }}/season/expord?se={{ Input::get('season') }}&ga={{ Input::get('game') }}&gr={{ $key }}&or={{ $k }}&ld={{ Input::get('bdate') }}&rd={{ Input::get('ldate') }}">导出ID</a>
                                    </td>
                                </tr>
                                @endforeach
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
            language: 'zh-CN',
            minView: "month",
            todayBtn: 1,
            autoclose: 1,
        });
    });
</script>
@endsection
@extends('layouts.admin')