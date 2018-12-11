@section('title', "游戏管理")
@section('content_title', '跑马灯列表')
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
                            <form action="{{ url('/game/rollnotice/index') }}" method="get">
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
                                                
                                                value="{{ $pfid }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <select name="play_type" class="form-control"  id="play_type">
                                            <option
                                                value="">请选择播放类型
                                            </option>
                                            @foreach($playTypeArr as $key=>$name)
                                                <option
                                                @if(Input::get('play_type') == $key )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $key }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <select name="status" class="form-control"  id="status">
                                            <option
                                                value="">请选择状态
                                            </option>
                                            @foreach($statusArr as $key=>$name)
                                                <option
                                                @if(Input::get('status') == $key )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $key }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <select name="mobile_type" class="form-control"  id="pfid">
                                            <option
                                                value="">请选择手机类型
                                            </option>
                                            @foreach(config("game.mobile_type") as $mobile_type=>$name)
                                                <option
                                                @if(Input::get('mobile_type') == $mobile_type )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $mobile_type }}">{{$name }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5"
                                        onclick="location.href ='{{$base_url}}/game/rollnotice/index';">重置
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
                        <h3 class="box-title">跑马灯列表</h3>
                            <span class="pull-right"><button type="button" onclick="location='{{ $base_url . "/game/rollnotice/show/0" }}';" class="btn btn-success pull-right">添加</button></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>游戏</th>
                                <th>渠道</th>
                                <th>手机类型</th>
                                <th>内容</th>
                                <th>播放类型</th>
                                <th>播放次数</th>
                                <th>播放开始时间</th>
                                <th>播放结束时间</th>
                                <th>状态</th>
                                <th>排序</th>
                                <!--<th>购买类型</th>-->
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th>修改人</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>{{  $item['id'] }}</td>
                                <td>
                                    @foreach(config("game.game") as $game)
                                        @if($item['game'] == $game['value'] )
                                           {{$game['name']}}
                                        @endif
                                    @endforeach
                                </td>
                                
                                
                                <td>
                                    @foreach(config("game.pfid") as $pfid=>$name)
                                    @if(in_array($pfid,explode(",",$item['pfid'])))
                                    {{$name}};
                                    @endif
                                    @endforeach
                                </td>
                                
                                <td>
                                    @foreach(config("game.mobile_type") as $mobile_type=>$name)
                                    @if(in_array($mobile_type,explode(",",$item['mobile_type'])))
                                    {{$name}};
                                    @endif
                                    @endforeach
                                </td>
                                <td>{{ $item['contens'] }}</td>
                                <td>{{ $playTypeArr[$item['play_type']] }}</td>
                                <td>{{ $item['play_num'] }}</td>
                                <td>{{ $item['play_sdate'] }}</td>
                                <td>{{ $item['play_sfdate'] }}</td>
                                <td>{{ $statusArr[$item['status']]}}</td>
                                <td>{{ $item['sort'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['updated_at'] }}</td>
                                <td>
                                    @if($item['admin_id'])
                                    {{ (\App\Models\User::query())->where("id",$item['admin_id'])->pluck("username")->first() }}
                                    @endif
                                </td>
                                <td>
                                    @if($item['status'] ==2)
                                    <a onclick="publish('{{ $base_url . "/game/rollnotice/publish/".$item['id'] }}')">发布</a>
                                    @endif
                                    <a onclick="show({{$item['id']}})">编辑</a>
                                    <a onclick="delete1('{{ $base_url . "/game/rollnotice/delete/".$item['id'] }}')">删除</a>
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
        function show(id){
            layer.open({
                title:"<h3 style='line-height:40px'>一元抢购修改</h3>",
                type: 2, 
                content: '{{$base_url}}/game/rollnotice/show/'+id,
                area: ['1000px', '500px'],
            });
        }
        
        function delete1(url){
            $.post(url,{}, function (response) {
                alert("操作成功！");
                location.reload();
            })
        }
        function publish(url){
            $.post(url,{}, function (response) {
                alert("发布成功！");
                location.reload();
            })
        }
    </script>
@endsection
@extends('layouts.admin')