@section('title', "游戏用户管理")
@section('content_title', '反馈详细列表')
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
                            <form action="{{ url('game/feedback/index') }}" method="get">
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

                <div class="box" style="overflow:auto">
                    <div class="box-header with-border">
                        <h3 class="box-title">反馈详细列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>用户id</th>
                                <th>用户名字</th>
                                <th>游戏</th>
                                <th>平台ID</th>
                                <th>平台子ID</th>
                                <th>反馈内容</th>
                                <th>是否回复</th>
                                <th>反馈时间</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($pager as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['uid'] }}</td>
                                <td>{{ $item['uname'] }}</td>
                                
                                <td>   
                                    @foreach(config("game.game") as $game)
                                        @if($item['game'] == $game['value'] )
                                           {{$game['name']}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $item['pfid'] }}</td>
                                
                                <td>{{ $item['usid'] }}</td>
                                <td>{{ $item['contents'] }}</td>
                                <td>
                                    {{$item['is_reply']?"已回复":"未回复"}}
                                </td>
                                <td>{{$item['created_at'] }}</td>

                                <td><a href="#"  onclick="show({{$item['id']}})">回复</a></td>
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
                title:"<h3 style='line-height:40px'>回复详细</h3>",
                type: 2, 
                content: '{{$base_url}}game/feedback/show/'+id,
                area: ['80%', '100%'],
				//scrollbar:true,
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