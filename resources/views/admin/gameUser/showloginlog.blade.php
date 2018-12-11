@section('css')
    <link rel="stylesheet" href="{{ $assets_url }}/plugins/select2/select2.min.css">
    <style>
        .select2-container .select2-selection--single {
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
                            <form action="{{ url('game/userlog/'.$uid) }}" method="get" id="search">
                                <div class="form-group col-lg-2">
                                    <input id="datetimepicker" name="sdate" data-date-format="yyyy-mm-dd hh:ii"
                                           value="{{Input::get('sdate')}}" title="sdate" type="text"
                                           class="form-control" placeholder="开始时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <input id="datetimepicker1" name="fdate" data-date-format="yyyy-mm-dd hh:ii"
                                           value="{{Input::get('fdate')}}" title="fdate" type="text"
                                           class="form-control" placeholder="结束时间">
                                </div>
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">金币变动详情</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>游戏ID</th>
                                <th>登录方式</th>
                                <th>设备ID</th>
                                <th>系统</th>
                                <th>平台ID</th>
                                <th>机型</th>
                                <th>网络</th>
                                <th>系统版本号</th>
                                <th>屏幕分辨率</th>
                                <th>登录时间</th>
                                <th>游戏版本号</th>
                                <th>登录IP</th>
                                <th>平台ID</th>
                                <th>平台子ID</th>
                                <th>快速登录</th>
                            </tr>
                            @foreach ($pager as $data)
                                <tr>
                                    <td>{{ $game[$data['game']] }}</td>
                                    <td>{{ $login_type[$data['login_type']]['1'] }}</td>
                                    <td>{{ $data['deve_id'] }}</td>
                                    <td>{{ $data['os'] }}</td>
                                    <td>{{ $data['puid'] }}</td>
                                    <td>{{ $data['deviceName'] }}</td>
                                    <td>{{ $data['network'] }}</td>
                                    <td>{{ $data['sysVer'] }}</td>
                                    <td>{{ $data['screenPix'] }}</td>
                                    <td>{{ $data['login_time'] }}</td>
                                    <td>{{ $data['version'] }}</td>
                                    <td>{{ $data['login_ip'] }}</td>
                                    <td>{{ $pfid[$data['pfid']] }}</td>
                                    <td>{{ $data['usid'] }}</td>
                                    <td>{{ $data['is_fast_login'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @include('admin.common.pager')
                </div>
            </div>
        </div>
    </section>


@section('js')
    <script src="{{ $assets_url }}/plugins/select2/select2.full.min.js"></script>
    <script src="{{ $assets_url }}plugins/select2/i18n/zh-CN.js"></script>
    <script data-exec-on-popstate>
        function colse() {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }

        $('#datetimepicker,#datetimepicker1').datetimepicker({
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
@extends('layouts.layer')