@section('title', "公告管理")
@section('content_title', '游戏公告列表')
@section('content_title_small',  $pager->total())
@section('content')
@section('css')
    <link rel="stylesheet" href="{{ $assets_url}}/plugins/select2/select2.min.css">
@endsection
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
                            <form action="{{ url('/game/notice/index') }}" method="get">
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
                                
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">用户列表</h3>
                            <span class="pull-right"><button type="button" onclick="location='{{ $base_url . "/game/notice/show/0" }}';" class="btn btn-success pull-right">添加</button></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>游戏</th>
                                <th>左侧公告</th>
                                <th>公告标题</th>
                                <th>公告内容</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
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
                                
                                <td>{{ $item['left_title'] }}</td>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ $item['content'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['updated_at'] }}</td>
                                <td>
                                    <a onclick="show({{$item['id']}})">编辑</a>
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
                title:"<h3 style='line-height:40px'>游戏公告修改</h3>",
                type: 2, 
                content: '{{$base_url}}/game/notice/show/'+id,
                area: ['1000px', '500px'],
            });
      }
      
      

    </script>
@endsection



@extends('layouts.admin')

                            