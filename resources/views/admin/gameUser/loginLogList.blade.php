@section('title', "游戏用户管理")
@section('content_title', '用户登录日志列表')
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
                            <form action="{{ url('game/loginlog/list') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text"
                                           class="form-control" placeholder="游戏ID"/>
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
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text"
                                           class="form-control" placeholder="游戏ID"/>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <select name="login_type" class="form-control"  id="login_type">
                                            <option
                                                value="">请选择登录类型
                                            </option>
                                            @foreach($login_type as $ke=>$login_arr)
                                                <option
                                                @if(Input::get('login_type') == $ke)
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $ke }}">{{$login_arr['1'] }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <input name="login_ip" value="{{ Input::get('login_ip', '') }}" title="username"
                                           type="text" class="form-control" placeholder="登录IP">
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
                                            onclick="location.href='{{$base_url}}game/loginlog/list';">重置
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
                                <th>UID</th>
                                <th>游戏</th>
                                <th>登陆类型</th>
                                <th>设备id</th>
                                <th>设备系统</th>
                                <th>机型</th>

                                <th>网洛</th>
                                <th>系统版本号</th>
                                <th>屏幕分辨率</th>
                                <th>登录时间</th>
                                <th>游戏版本号</th>
                                <th>登录ip</th>

                                <th>平台id</th>
                                <th>平台子id</th>
                                <th>是否快速登录</th>
                                <th>其他操作</th>
                            </tr>
                            @foreach ($pager as $item)
                                <tr>
                                    <td>{{ $item['uid'] }}</td>
                                    <td>{{ $item['game'] }}</td>
                                    <td>{{ isset($login_type[$item['login_type']]['1'] )?$login_type[$item['login_type']]['1'] :$item['login_type'] }}</td>
                                    <td>{{ $item['deve_id'] }}</td>
                                    <td>{{ $item['os'] }}</td>
                                    <td>{{ $item['deviceName'] }}</td>

                                    <td>{{ $item['network'] }}</td>
                                    <td>{{ $item['sysVer'] }}</td>
                                    <td>{{ $item['screenPix'] }}</td>

                                    <td>{{   $item['login_time'] }}</td>
                                    <td>{{  $item['version'] }}</td>
                                    <td>{{ $item['login_ip']}}</td>
                                    <td>{{ isset($pfid[$item['pfid']])?$pfid[$item['pfid']]:$item['pfid'] }}</td>
                                    <td>{{ $item['usid'] }}</td>
                                    <td>{{ $item['is_fast_login']?"是":"不是" }}</td>
                                    <td>
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