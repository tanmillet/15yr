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
                <h3 class="box-title">道具列表</h3>
                <span class="pull-right"><button type="button" onclick="location ='{{ $base_url . "/game/goods/show/0" }}';" class="btn btn-success pull-right">添加</button></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tr class="active">
                        <th>ID</th>
                        <th>游戏</th>
                        <th>道具名称</th>
                        <th>道具图片</th>
                        <th>道具描述</th>
                        <th>显示场景</th>

                        <th>道具类型</th>
                        <th>道具数量</th>
                        <th>赠送数量</th>
                        <th>购买类型</th>
                        <th>是否显示</th>
                        <th>道具有效时间类型</th>
                        <th>道具有效时间（小时）</th>
                        <th>道具价格</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    <?php $goodsModel = (new App\Models\Game\GoodsModel()); ?>
                    @foreach ($pager as $itemObj)
                    <tr>
                        <?php $item=$itemObj['attributes']; ?>
                        <td>{{  $item['id'] }}</td>
                        <td>
                            @foreach(config("game.game") as $game)
                            @if($item['game'] == $game['value'] )
                            {{$game['name']}}
                            @endif
                            @endforeach
                        </td>

                        <td>{{$item['name']}}</td>
                        <td><img src="{{env("CDNURL").$item['img']}}" width="40px" height="40px"></td>
                        <td>{{$item['remark']}}</td>
                        <td>{{$goodsAtrr['show']['show_type'][$item['show_type']]}}</td>
                        <td>{{$goodsAtrr['show']['goods_type'][$item['type']]}}</td>
                        <td>{{$item['number']}}</td>
                        <td>{{$item['give_number']}}</td>
                        <td>{{$goodsAtrr['show']['is_buy'][$item['is_buy']]}}</td>
                        <td>{{$goodsAtrr['show']['is_show'][$item['is_show']]}}</td>
                        <td>{{$goodsAtrr['show']['valid_time_type'][$item['valid_time_type']]}}</td>
                        <td>{{$item['valid_time']}}</td>
                        <td>
                            @foreach($goodsAtrr['show']['buy_type'] as $key=>$val)
                            <?php $goodsPrice = $goodsModel->getGoodPrice($item['id']); ?>
                            {{$val}}实价&nbsp;&nbsp;&nbsp;{{$goodsPrice[$key]['price']}}
                            虚假&nbsp;&nbsp;&nbsp;{{$goodsPrice[$key]['sham_price']}}
                            <br>
                            @endforeach
                        </td>

                        <td>{{$item['created_at']}}</td>
                        <td>
                            <a onclick="show({{$item['id']}})">编辑</a>
                            <a onclick="delete({{$item['id']}})">删除</a>
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
    title:"<h3 style='line-height:40px'>道具详情修改</h3>",
            type: 2,
            content: '{{$base_url}}/game/goods/show/' + id,
            area: ['1000px', '500px'],
    });
    }



</script>
@endsection



@extends('layouts.admin')

