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
                <div class="box-body" >
                    <div class="row">
                        <form action="{{ url('game/cardlog/index') }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                            </div>

                            <div class="form-group col-lg-2">
                                <select name="gameid" class="form-control"  id="gameid">
                                    <option value="">请选择玩法</option>
                                    @foreach(config("game.gameid") as $gameid=>$value)
                                    <option
                                        value="{{ $gameid}}"
                                        @if(Input::get('gameid') ==$gameid )
                                        {{ 'selected' }}
                                        @endif 
                                        >{{$value }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group col-lg-2">
                            <button type="submit" class="btn btn-default col-md-5">搜索</button>
                            <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/cardlog/index';">重置</button>
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
                <?php $commonModel = new \App\Models\Game\CommonModel()?>
                <div class="box-header with-border">
                    <h3 class="box-title">牌局详情</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            @if(Input::get('gameid') ==3 )
                            <tr class="active">
                                <th>牌局id</th>
                                <th>用户UID</th>
                                <th>庄家ID</th>
                                <th>金币变动</th>
                                <th>押注区域</th>
                                <th>牌型</th>
                                <th>时间</th>
                            </tr>
                            @foreach ($pager as $item)
                                <tr>
                                    <?php $card = $commonModel->getCardInfo($item['uid'], $item['gameid'], $item['tllog'])?>
                                    <td>{{ $item["tlid"] }}</td>
                                    <td>{{ $item['uid'] }}</td>
                                    <td>{{ $card['zhuangPlayerId']?$card['zhuangPlayerId']:"系统庄" }}</td>
                                    <td>{{ $item['wlwin'] }}</td>
                                    
                                    <td>
                                        @if(isset($card['playerBet']) )
                                            @foreach($card['playerBet'] as $k=>$v)
                                                {{$k}}：{{$v}}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($card['retCard']) )
                                        @foreach($card['retCard'] as $k=>$v)
                                            {{$k}}：{{$v}}<br>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{ date("Y-m-d H:i:s",$item['wltime']) }}</td>
                                </tr>
                            @endforeach
                            @endif 
                        </table>
                    </div>
                <!-- /.box-body -->
                 @include('admin.common.pager')
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
});
function subt() {
    location.href = '{{$base_url}}/game/moneycount/index/?count_type=' + $("#count_type").val();
}
</script>
@endsection
@extends('layouts.admin')