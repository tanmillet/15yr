@section('title', "用户管理")
@section('content_title', '排位赛详情')
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
                            <form action="{{ url('game/dws/index') }}" method="get">
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
                                    <select name="ddz_id" class="form-control"  id="order_status">
                                            <option
                                                value="">选择赛季
                                            </option>
                                            @foreach($ddzCfgArr as $k=>$value)
                                                <option
                                                @if(Input::get('ddz_id') == $value["id"])
                                                   {{ 'selected' }}
                                                @endif
                                                value="{{ $value["id"]}}">{{$value["ddz_name"]}}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
 
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}game/dws/index';">重置</button>
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
                                <th>赛季</th>
                                <th>用户ID</th>
                                <th>用户姓名</th>
                                <th>用户头像</th>
                                <th>段位</th>
                                <th>阶</th>
                                <th>星星</th>
                                <th>分数</th>
                                <th>勇士积分</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>
                                     @foreach($ddzCfgArr as $k=>$value)
                                        @if($item['season_id'] == $value["id"])
                                           {{$value["ddz_name"]}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $item['uid'] }}</td>
                                <td>{{ $item['uname'] }}</td>
                                <td><image src="{{ env('CDNURL') .$item['uface'] }}" with="40px" height="40px"></td>
                                <td>{{ $groupArr[$item['group']] }}</td>
                                
                                <td>{{ $orderArr[$item['order']] }}</td>
                                <td>{{ $item['star'] }}</td>
                                <td>{{ $item['score'] }}</td>
                                
                                <td>{{ $item['warrior'] }}</td>
                                <td><a href="#"  onclick="show({{$item['id']}})">修改段位</a></td>
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
                title:"<h3 style='line-height:40px'>修改段位</h3>",
                type: 2, 
                content: '{{$base_url}}game/dws/show/'+id,
                area: ['1000px', '500px'],
            });
      }
    </script>
@endsection
@extends('layouts.admin')