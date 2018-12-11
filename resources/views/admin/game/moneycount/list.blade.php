@section('title', "金币统计管理")
@section('content_title', '金币统计列表')
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
                        <form action="{{ url('game/moneycount/index') }}" method="get" id="search">
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
                                <input id="datetimepicker" name="sdate" data-date-format="{{$date_type}}"
                                       value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control"
                                       placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="{{$date_type}}"
                                       value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control"
                                       placeholder="结束时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="money_type" class="form-control" id="money_type">
                                    <option
                                            value="">请选择金币类型
                                    </option>
                                    @foreach($data['moneyTypeName'] as $mtype=>$value)
                                        <option
                                                @if(Input::get('money_type') == $data['moneyType'][$mtype] )
                                                {{ 'selected' }}
                                                @endif

                                                value="{{ $data['moneyType'][$mtype] }}">{{$value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <select name="count_type" class="form-control" id="count_type" onchange="subt()">
                                    <option
                                            value="">请选择统计类型

                                    </option>
                                    @foreach($count_type_arr as $ke=>$v)
                                        <option
                                                @if(Input::get('count_type') == $ke )
                                                {{ 'selected' }}
                                                @endif

                                                value="{{ $ke }}">{{$v }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{$base_url}}/game/moneycount/index';">重置
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
                    <?php $moneyTypeOne = Input::get('money_type')?>
                    <table class="table table-bordered">
                        <tr class="active">
                            <th style="min-width:100px">分类</th>
                            <th>类型</th>
                            @foreach($keyData as $date=>$value)
                            <th>{{$date}}</th>
                            @endforeach
                        </tr>
                         @foreach($moneyInfoType as $k=>$v)
                                <tr>
                                    <th style="min-width:100px">{{$v}}</th>
                                    <th>统计</th>
                                    @foreach($keyData as $date=>$value)
                                    <th>{{isset($value[$k]["-1"][$moneyTypeOne])?$value[$k]["-1"][$moneyTypeOne]:0}}</th>
                                    @endforeach
                                </tr>
                        @endforeach
                        @foreach($countCode as $code=>$v)
                            @foreach($v as $sendtype)
                            <tr>
                                <th  style="width:5%">{{$moneyCode[$code]}}</th>
                                <th>{{$sendtype ==1?"送出":"回收"}}</th>
                                @foreach($keyData as $date=>$value)
                                      <th>
                                          @if($sendtype ==1)
                                          {{isset($value[4][$code][$moneyTypeOne])?$value[4][$code][$moneyTypeOne]:0}}
                                          @else
                                          {{isset($value[5][$code][$moneyTypeOne])?$value[5][$code][$moneyTypeOne]:0}}
                                          @endif
                                      </th>
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
    function show(id) {
        layer.open({
            title: "<h3 style='line-height:40px'>实物发货</h3>",
            type: 2,
            content: '{{$base_url}}game/realGood/show/' + id,
            area: ['1000px', '700px'],
        });
    }

    $(function () {
        $('#datetimepicker').datetimepicker({
            language: 'zh-CN',
            @if(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn: 1,
            autoclose: 1,
            @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView: 'year',
            maxView: 'decade'
            @elseif(Input::get('count_type') ==5)//年
            autoclose: true,
            todayBtn: true,
            startView: 4,
            minView: 4,
            autoclose: 1,
            @endif
        });
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN',
            @if(Input::get('count_type') ==3)//天
            minView: "month", //选择日期后，不会再跳转去选择时分秒 
            todayBtn: 1,
            autoclose: 1,
            @elseif(Input::get('count_type') ==4)//月
            autoclose: true,
            todayBtn: true,
            startView: 'year',
            minView: 'year',
            maxView: 'decade'
            @elseif(Input::get('count_type') ==5)//年
            autoclose: true,
            todayBtn: true,
            startView: 4,
            minView: 4,
            autoclose: 1,
            @endif
        });
    });

    function subt() {
        location.href = '{{$base_url}}/game/moneycount/index/?count_type=' + $("#count_type").val();
    }
</script>
@endsection
@extends('layouts.admin')