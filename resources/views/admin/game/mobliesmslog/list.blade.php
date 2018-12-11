@section('title', "短信记录")
@section('content_title', '短信记录列表')
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
                            <form action="{{ url('game/mobilesmslog/index') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text" class="form-control" placeholder="游戏ID" />
                                </div>
                               
                                 <div class="form-group col-lg-2">
                                    <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"  value="{{Input::get('sdate')}}" title="sdate" type="text" class="form-control" placeholder="开始时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii" value="{{Input::get('fdate')}}" title="fdate" type="text" class="form-control" placeholder="结束时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}game/feedback/index';">重置</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">反馈详细列表</h3>
                        <span class="pull-right"><button type="button" onclick="location='{{ $base_url . "game/mobilesmslog/show" }}';" class="btn btn-success pull-right">发送短信</button></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>类型</th>
                                <th>手机号</th>
                                <th>内容</th>
                                <th>发送人的ID</th>
                                <th>发送时间</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['send_type'] }}</td>
                                <td>{{ $item['centents'] }}</td>
                                <td>{{ $item['send_uid'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
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