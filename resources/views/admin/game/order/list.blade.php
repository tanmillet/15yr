@section('title', "支付管理")
@section('content_title', '订单详细列表')
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
                            <form action="{{ url('game/order/index') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text" class="form-control" placeholder="游戏ID" />
                                </div>
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
                                    <select name="pfid" class="form-control"  id="pfid">
                                            <option
                                                value="">请选择渠道
                                            </option>
                                            @foreach(config("game.pfid") as $pfid=>$name)
                                                <option
                                                @if(Input::get('pfid') == $pfid )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{$pfid}}">{{$name}}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <input name="order" value="{{ Input::get('order', '') }}" type="text" class="form-control" placeholder="订单号" />
                                </div>
                                <div class="form-group col-lg-2">
                                    <select name="order_status" class="form-control"  id="order_status">
                                            <option
                                                value="">请选择订单状态
                                            </option>
                                            @foreach($order_status_arr as $k=>$value)
                                                <option
                                                @if(Input::get('order_status') == $value)
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $value}}">{{$order_status_name_arr[$k] }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <select name="obuy_ref" class="form-control"  id="obuy_ref">
                                            <option
                                                value="">请选择购买渠道
                                            </option>
                                            @foreach($obuy_ref_arr as $k=>$value)
                                                <option
                                                @if(Input::get('obuy_ref') == $k)
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $k}}">{{$value }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <select name="obuy_type" class="form-control"  id="obuy_type">
                                            <option
                                                value="">请选择购买方式
                                            </option>
                                            @foreach($obuy_type_arr as $k=>$value)
                                                <option
                                                @if(Input::get('obuy_type') == $k)
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $k}}">{{$value }}
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
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}game/order/index';">重置</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">订单列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>订单号</th>
                                <th>第三方订单号</th>
                                <th>用户id</th>
                                <th>游戏</th>
                                <th>购买数量</th>
                                <th>订单单价</th>
                                <th>购买方式</th>
                                <th>购买渠道</th>
                                <th>订单状态</th>
                                <th>渠道</th>
                                <th>平台id</th>
                                <th>创建时间</th>
                                <th>支付时间</th>
                                <th>发货时间</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['order'] }}</td>
                                <td>{{ $item['appOrder'] }}</td>
                                <td>{{ $item['uid'] }}</td>
                                <td>{{ $item['game'] }}</td>
                                <td>{{ $item['order_num'] }}</td>
                                
                                <td>{{ $item['price'] }}</td>
                                <td>{{ $obuy_type_arr[$item['obuy_type']] }}</td>
                                <td>{{ isset($obuy_ref_arr[$item['obuy_ref']])?$obuy_ref_arr[$item['obuy_ref']]:$item['obuy_ref'] }}</td>
                                <td>{{ $order_status_name_arr[array_search($item['order_status'],$order_status_arr)] }}</td>
                                
                                <td>{{ config("game.pfid.".$item['pfid']) }}</td>
                                <td>{{ $item['usid'] }}</td>
                                
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['pay_at'] }}</td>
                                <td>{{ $item['send_at'] }}</td>
                                <td><a href="#"  onclick="show({{$item['id']}})">详情</a></td>
                                 
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
        function show(id){
            layer.open({
                title:"<h3 style='line-height:40px'>订单详情</h3>",
                type: 2, 
                content: '{{$base_url}}game/order/show/'+id,
                area: ['1000px', '500px'],
            });
      }
      
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