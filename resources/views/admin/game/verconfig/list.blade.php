@section('title', "游戏管理")
@section('content_title', '版本信息列表')
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
                            <form action="{{ url('game/verconfig/index') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <select name="game" class="form-control"  id="moneyType">
                                            <option
                                                value="">请选择游戏
                                            </option>
                                            @foreach(config("game.game") as $game)
                                                <option
                                                @if(Input::get('game') == $game['value'] )
                                                    {{ 'selected=selected' }}
                                                @endif
                                                
                                                value="{{ $game['value'] }}">{{$game['name'] }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>   
                                
                                <div class="form-group col-lg-2">
                                <select name="pfid" class="form-control"  id="pfid">
                                            <option
                                                value="">请选择平台
                                            </option>
                                            @foreach(config("game.pfid") as $pfid=>$name)
                                                <option
                                                @if(Input::get('pfid') == $pfid )
                                                    {{ 'selected=selected' }}
                                                @endif
                                                
                                                value="{{$pfid }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <input name="usid" value="{{ Input::get('usid', '') }}" type="text"
                                           class="form-control" placeholder="平台的子ID"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <select name="mobile_type" class="form-control"  id="mobile_type">
                                            <option
                                                value="">请选择手机类型
                                            </option>
                                            @foreach(config("game.mobile_type") as $mobile_type=>$name)
                                                <option
                                                @if(Input::get('mobile_type') == $mobile_type )
                                                   {{ 'selected=selected' }}
                                                @endif
                                                
                                                value="{{$mobile_type }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                    <input name="app_ver" value="{{ Input::get('app_ver', '') }}" type="text"
                                           class="form-control" placeholder="更新大版本号"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <input name="zip_ver" value="{{ Input::get('zip_ver', '') }}" type="text"
                                           class="form-control" placeholder="热更新版本"/>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text"
                                           class="form-control" placeholder="游戏ID"/>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <input name="ip" value="{{ Input::get('ip', '') }}" 
                                           type="text" class="form-control" placeholder="限制IP">
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <input name="device_id" value="{{ Input::get('device_id', '') }}" type="text"
                                           class="form-control" placeholder="设备id"/>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                            onclick="location.href='{{$base_url}}game/verconfig/index';">重置
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
                        <h3 class="box-title">版本信息列表</h3>
                        <span class="pull-right"><button type="button" onclick="location ='{{ $base_url . "/game/verconfig/show/0" }}';" class="btn btn-success pull-right">添加</button></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>游戏</th>
                                <th>平台</th>
                                <th>平台的子ID</th>
                                <th>手机型号(2是苹果1安卓)</th>
                                <th>更新包的大小</th>

                                <th>更新包的名字</th>
                                <th>大版本号</th>
                                <th>热更新版本</th>
                                <th>是否最新整包</th>
                                <th>更新类型</th>
                                <th>限制用户id</th>

                                <th>限制IP</th>
                                <th>设备id</th>
                                <th>创建时间</th>
                                <th>其他操作</th>
                            </tr>
                            @foreach ($pager as $item)
                                <tr>
                                    <td>{{ $item['id'] }}</td>
                                    <td>
                                         @foreach(config("game.game") as $game)
                                            @if($item['game'] == $game['value'] )
                                               {{$game['name']}}
                                            @endif
                                         @endforeach
                                    </td>
                                    <td>{{ config("game.pfid.".$item['pfid'])?config("game.pfid.".$item['pfid']):$item['pfid'] }}</td>
                                    <td>{{ $item['usid'] }}</td>
                                    <td>{{ config("game.mobile_type.".$item['mobile_type'])?config("game.mobile_type.".$item['mobile_type']):$item['mobile_type'] }}</td>
                                    <td>{{ $item['size'] }}</td>

                                    <td>{{ $item['filename'] }}</td>
                                    <td>{{ $item['app_ver'] }}</td>
                                    <td>{{ $item['zip_ver'] }}</td>

                                    <td>{{ isset($uptTypeArr[$item['upt_type']])?$uptTypeArr[$item['upt_type']]:$item['upt_type']}}</td>
                                    <td>{{isset($typeArr[$item['type']])?$typeArr[$item['type']]:$item['type']}}</td>
                                    <td>{{ $item['uid']}}</td>
                                    <td>{{ $item['ip'] }}</td>
                                    <td>{{ $item['device_id'] }}</td>
                                    <td>{{ $item['created_at'] }}</td>
                                    <td>
                                        <a href="#" onclick="showMoneyLog({{$item['id']}})">修改版本</a>
                                        <a href="#" class="_delete_"
                                           data-url="{{ url('/game/verconfig/'.$item['id']) }}">删除</a>
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

        function showMoneyLog(id) {
            layer.open({
                title: "<h3 style='line-height:40px'>修改版本信息</h3>",
                type: 2,
                content: '{{$base_url}}/game/verconfig/show/' + id,
                area: ['60%', '900px'],
            });
        }


    </script>
@endsection
@extends('layouts.admin')