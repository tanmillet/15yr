@section('title', "支付管理")
@section('content_title', '支付排名列表')
@section('content')
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
                        <form action="{{ url('game/paycount/payRank') }}" method="get" id="search">
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
                                <input id="datetimepicker" name="sdate"   value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate"  value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                            </div>

                       

                            
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href ='{{$base_url}}/game/paycount/payRank';">重置</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <h3 class="box-title">支付排名列表</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>游戏</th>
                                <th>用户ID</th>
                                <th>付费金额</th>
                                <th>购买次数</th>
                                
                                <th>用户金币</th>
                                <th>注册时间</th>
                                <th>最后登录时间</th>
                            </tr>
                                @if(!empty($payCount))
                                @foreach($payCount as $k=>$v)
                                <tr >
                                    <th>
                                         @foreach(config("game.game") as $game)
                                            @if($v['game'] == $game['value'] )
                                               {{$game['name']}}
                                            @endif
                                         @endforeach
                                    </th>
                                    <th>{{$v['uid']}}</th>
                                    <th>{{$v['pay_price']}}</th>
                                    
                                    <th>{{$v['pay_count']}}</th>
                                    <th>{{$v['uchip']}}</th>
                                    <th>{{date("Y-m-d H:i:s",$v['urtime'])}}</th>
                                    <th>{{date("Y-m-d H:i:s",$v['lasttime'])}}</th>

                                </tr>  
                                @endforeach 
                                @endif
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