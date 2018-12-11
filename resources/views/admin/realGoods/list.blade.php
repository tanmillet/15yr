@section('title', "实物发放")
@section('content_title', '实物发放列表')
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
                            <form action="{{ url('game/realGood/index') }}" method="get">
                                <div class="form-group col-lg-2">
                                    <input name="uid" value="{{ Input::get('uid', '') }}" type="text" class="form-control" placeholder="游戏ID" />
                                </div>
                                <div class="form-group col-lg-2">
                                     <input name="uname" value="{{ Input::get('uname', '') }}" title="uname" type="text" class="form-control" placeholder="游戏用户名">
                                </div>
                                <div class="form-group col-lg-2">
                                     <input name="real_order" value="{{ Input::get('real_order', '') }}" title="real_order" type="text" class="form-control" placeholder="订单号">
                                </div>
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
                                <div class="form-group col-lg-2">
                                     <select name="status" class="form-control"  id="moneyType">
                                            <option
                                                value="">请选择订单状态
                                            </option>
                                            @foreach($status_arr as $k=>$v)
                                                <option
                                                 @if(Input::has('status') && Input::get('status') == $k )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $k }}">{{$v }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-2">
                                    <button type="submit" class="btn btn-default col-md-5">搜索</button>
                                    <button type="button" style="margin-left:5px;" class="btn btn-default col-md-5" onclick="location.href='{{$base_url}}/realGood/index';">重置</button>
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
                                <th>实物订单号</th>
                                <th>用户ID</th>
                                <th>用户名字</th>
                                <th>实际道具表ID</th>
                                <th>道具名字</th>
                                <th>道具数量</th>
                                <th>手机号码</th>
                                <th>详细地址</th>
                                
                                <th>用户真实名字</th>
                                <th>邮编</th>
                                <th>订单来源</th>
                                
                                <th>订单状态</th>
                                <th>快递类型</th>
                                <th>快递单号</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            <?php $commonModel = new \App\Models\Game\CommonModel;?>
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
                                <td>{{ $item['real_order'] }}</td>
                                <td>{{ $item['uid'] }}</td>
                                <td>{{ $item['uname'] }}</td>
                                <td>{{ $item['ob_goods_id'] }}</td>
                                <td>{{ $item['gname'] }}</td>
                                
                                <td>{{ $item['num'] }}</td>
                                <td>{{ $item['mobile'] }}</td>
                                <td>{{ $item['address'] }}</td>
                                
                                <td>{{ $item['real_name'] }}</td>
                                <td>{{ $item['zip_code'] }}</td>
                                
                                <td>{{  $commonModel->getGoodsRefName($item['ref'],$item['search_ref'],0,$item['game']) }}</td>
                                <td>{{ $item['status_arr'][$item['status']] }}</td>
                                <td>{{ $item['fast_type'] }}</td>
                                <td>{{ $item['fast'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>
                                    <a href="#"  onclick="show({{$item['id']}})">发货</a>
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
                title:"<h3 style='line-height:40px'>实物发货</h3>",
                type: 2, 
                content: '{{$base_url}}game/realGood/show/'+id,
                area: ['1000px', '700px'],
            });
      }
    </script>
@endsection
@extends('layouts.admin')