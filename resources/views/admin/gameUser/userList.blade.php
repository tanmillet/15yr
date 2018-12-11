@section('title', "游戏用户管理")
@section('content_title', '游戏用户用户列表')
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
                            <form action="{{ url('game/gameuserinfo') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text"
                                           class="form-control" placeholder="游戏ID"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <input name="uname" value="{{ Input::get('uname', '') }}" title="username"
                                           type="text" class="form-control" placeholder="游戏用户名">
                                </div>
                                <div class="form-group col-lg-2">
                                    <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"
                                           value="{{Input::get('sdate')}}" title="sdate" type="text"
                                           class="form-control" placeholder="注册开始时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii"
                                           value="{{Input::get('fdate')}}" title="fdate" type="text"
                                           class="form-control" placeholder="注册结束时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                            onclick="location.href='{{$base_url}}game/gameuserinfo';">重置
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>游戏</th>
                                <th>用户名</th>
                                <th>头像</th>
                                <th>性别</th>
                                <th>手机</th>

                                <th>金钱</th>
                                <th>钻石</th>
                                <th>彩券</th>
                                <th>注册时间</th>
                                <th>最后登录时间</th>
                                <th>连续登陆天数</th>

                                <th>VIP等级</th>
                                <th>注册IP</th>
                                <th>账号状态</th>
                                <th>其他操作</th>
                            </tr>
                            @foreach ($pager as $item)
                                <tr>
                                    <td>{{ $item['uid'] }}</td>
                                    <td>{{ $item['game'] }}</td>
                                    <td>{{ $item['uname'] }}</td>
                                    <td><img src="{{ $item['uface'] }}" width="40" height="40"></td>
                                    <td>{{ $item['usex'] }}</td>
                                    <td>{{ $item['mobile'] }}</td>

                                    <td>{{ $item['uchip'] }}</td>
                                    <td>{{ $item['udiamond'] }}</td>
                                    <td>{{ $item['utombola'] }}</td>

                                    <td>{{ date("Y-m-d H:i:s",$item['urtime']) }}</td>
                                    <td>{{ date("Y-m-d H:i:s",$item['lasttime'])}}</td>
                                    <td>{{ $item['uldays']}}</td>
                                    <td>{{ $item['is_vip'] }}</td>
                                    <td>{{ $item['ip'] }}</td>
                                    <td>{{ $item['ustatus'] }}</td>
                                    <td>
                                        <a href="#" class="_delete_"
                                           data-url="{{ url('/game/gameuserinfo/'.$item['uid']) }}">删除</a>
                                        <a href="#" onclick="test({{$item['uid']}})">操作游戏币</a>
                                        <a href="#" onclick="showMoneyLog({{$item['uid']}})">金币日志</a>
                                        <a href="#" onclick="userlog({{$item['uid']}})">登陆日志</a>
                                    </td>
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
        function test(uid) {
            layer.open({
                title: "<h3 style='line-height:40px'>用户金币修改</h3>",
                type: 2,
                content: '{{$base_url}}/game/showmoneny/' + uid,
                area: ['1000px', '500px'],
            });
        }

        function showMoneyLog(uid) {
            layer.open({
                title: "<h3 style='line-height:40px'>金币日志</h3>",
                type: 2,
                content: '{{$base_url}}/game/showmonenyLog/' + uid,
                area: ['1500px', '900px'],
            });
        }

        function userlog(uid) {
            layer.open({
                title: "<h3 style='line-height:40px'>登录日志</h3>",
                type: 2,
                content: '{{$base_url}}/game/userlog/' + uid,
                area: ['1700px', '900px'],
            });
        }

        $('#datetimepicker').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1

        });
    </script>
@endsection
@extends('layouts.admin')