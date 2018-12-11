@section('title', "活动管理")
@section('content_title', '一元抢购列表')
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
                            <form action="{{ url('/game/scarebuy/index') }}" method="get">
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
                            <span class="pull-right"><button type="button" onclick="location='{{ $base_url . "/game/scarebuy/show/0" }}';" class="btn btn-success pull-right">添加</button></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr class="active">
                                <th>ID</th>
                                <th>游戏</th>
                                <th>抢购名称</th>
                                <th>抢购内容</th>
                                <th>抢购奖励</th>
                                <th>抢购类型</th>
                                <th>抢购生效时间</th>
                                <th>抢购截止时间</th>
                                <th>开奖间隔时间</th>
                                <!--<th>购买类型</th>-->
                                <th>购买所需道具价格</th>
                                <th>机器人数量类型</th>
                                <th>机器人数量</th>
                                
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
                                
                                <td>{{ $item['scare_buy_name'] }}</td>
                                <td>{{ $item['scare_buy_contens'] }}</td>
                                <td>{{ $item['scare_buy_goods_str'] }}</td>
                                <td>{{ $type_arr[$item['type']] }}</td>
                                <td>{{ date("Y-m-d H:i:s",$item['start_time']) }}</td>
                                <td>{{ date("Y-m-d H:i:s",$item['end_time']) }}</td>
                                <td>{{ $item['space_time'] }}分钟</td>
                                <!--<td></td>-->
                                <td>
                                    <?php $priceArr = (new \App\Models\Game\base\ScareBuy)->dealShowPrice($item['price']) ?>
                                    
                                    @foreach ($priceArr as $moneyType=>$price)
                                       {{ $moneyType }}：{{ $price }}<br>
                                    @endforeach
                                </td>
                                <td>{{ $robot_num_type_arr[$item['robot_num_type']] }}</td>
                                <td>{{ $item['robot_num'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['updated_at'] }}</td>
                                <td>
                                    <a onclick="show({{$item['id']}})">编辑</a>
                                    <a onclick="delete1('{{ $base_url . "/game/scarebuy/delete/".$item['id'] }}')">删除</a>
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
                content: '{{$base_url}}/game/scarebuy/show/'+id,
                area: ['1000px', '500px'],
            });
        }
        
        function delete1(url){
            $.post(url,{}, function (response) {
                alert("操作成功！");
                location.reload();
            })
        }
    </script>
@endsection
@extends('layouts.admin')