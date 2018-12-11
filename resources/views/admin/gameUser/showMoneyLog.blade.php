@section('css')
    <link rel="stylesheet" href="{{ $assets_url }}/plugins/select2/select2.min.css">
    <style>
        .select2-container .select2-selection--single{
            height: 32px;
        }
    </style>
@endsection
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
                        <form action="{{ url('game/showmonenyLog/'.$uid) }}" method="get" id="search">
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                            </div>
                            <div class="form-group col-lg-2">
                                <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <select name="moneytype" class="form-control"  id="moneytype">
                                    @foreach($moneyType as $moneyName=>$value)
                                        <option

                                        value="{{ $moneyName}}"
                                       @if(Input::get('moneytype') ==$moneyName )
                                              {{ 'selected' }}
                                           @endif 
                                        >{{$value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <div class="form-group col-lg-2">
                                <select name="code" class="form-control"  id="code">
                                    <option  value="0">全部来源</option>
                                    @foreach( config("socket.code") as $code=>$name)
                                        <option

                                        value="{{ $code}}"
                                       @if(Input::get('code') ==$code )
                                              {{ 'selected' }}
                                           @endif 
                                        >{{$name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <div class="box">
                <!-- /.box-header -->
                <!--<div class="box-body">
                    <div id="container" style="min-width:400px;height:400px"></div>
                </div>-->
                
                <div class="box-header with-border">
                    <h3 class="box-title">金币变动详情</h3>
                </div>
                <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>用户ID</th>
                                <th>游戏币类型</th>
                                <th>来源</th>
                                <th>数量</th>
                                <th>时间</th>
                            </tr>
                            @foreach ($pager as $item)
                                <tr>
                                    <td>{{ $item['uid'] }}</td>
                                    <td>{{ $moneyType[Input::get('moneytype')] }}</td>
                                    <td>{{config("game.gametype.". $item['gameid'].".".$item['gametype'])}}{{ config("socket.code.".$item['code'])?config("socket.code.".$item['code']):$item['code'] }}</td>
                                    <td>{{ $item['changecoin'] }}</td>
                                    <td>{{ date("Y-m-d H:i:s",$item['time']) }}</td>

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


@section('js')
<script src="{{ $assets_url }}/plugins/select2/select2.full.min.js"></script>
<script src="{{ $assets_url }}plugins/select2/i18n/zh-CN.js"></script>
<script data-exec-on-popstate>
    /*$(function () {
        $("#type").select2({allowClear: true});
    });*/
        function colse(){
            var index=parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
        
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
</script>
@endsection
@extends('layouts.layer')