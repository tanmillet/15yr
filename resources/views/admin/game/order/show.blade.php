@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- /.box -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">订单列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr  >
                                <th>ID</th>
                                <th>{{ $item['id'] }}</th>
                            </tr>
                            <tr  >
                                <th>订单号</th>
                                <th>{{ $item['order'] }}</th>
                            </tr>
                            <tr  >
                                <th>第三方订单号</th>
                                <th>{{ $item['appOrder'] }}</th>
                            </tr>
                            <tr  >
                                <th>用户id</th>
                                <th>{{ $item['uid'] }}</th>
                            </tr>
                            <tr  >    
                                <th>游戏</th>
                                <th>{{ $item['id'] }}</th>
                            </tr>
                            <tr  >    
                                <th>购买数量</th>
                                <th>{{ $item['order_num'] }}</th>
                            </tr>
                            <tr  >    
                                <th>订单单价</th>
                                <th>{{ $item['price'] }}</th>
                            </tr>
                            <tr  >    
                                <th>购买方式</th>
                                <th>{{ $obuy_type_arr[$item['obuy_type']] }}</th>
                            </tr>
                            <tr  >    
                                <th>购买渠道</th>
                                <th>{{ $obuy_ref_arr[$item['obuy_ref']] }}</th>
                            </tr>
                            <tr  >    
                                <th>订单状态</th>
                                <th>{{ $order_status_name_arr[array_search($item['order_status'],$order_status_arr)] }}</th>
                            </tr>
                            <tr  >    
                                <th>渠道id</th>
                                <th>{{ $item['pfid'] }}</th>
                            </tr>
                            <tr  >    
                                <th>平台id</th>
                                <th>{{ $item['usid'] }}</th>
                            </tr>
                            <tr  >    
                                <th>创建时间</th>
                                <th>{{ $item['created_at'] }}</th>
                            </tr>
                            <tr  >    
                                <th>支付时间</th>
                                <th>{{ $item['pay_at'] }}</th>
                            </tr>
                            <tr  > 
                                <th>发货时间</th>
                                <th>{{ $item['send_at'] }}</th>
                            </tr>
                        </table>
                        <div class="box-header with-border">
                            <h3 class="box-title">订单购买列表</h3>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th>道具名字</th>
                                <th>购买份数</th>
                                <th>是否赠送</th>
                                <th>发送状态</th>
                                <th>发送成功时间</th>
                                <th>创建时间</th>
                            </tr>
                            @foreach ($orderGoodsInfo as $it)
                            <tr>
                                <th>{{(new App\Models\Game\base\ObGoods())->where("id",$it['obgoods_id'])->pluck("gname")->first()}}</th>
                                <th>{{$it['ognumber']}}</th>
                                <th>{{$it['is_give']?"赠送":"未赠送"}}</th>
                                <th>{{$send_status_arr[$it['send_status']]}}</th>
                                <th>{{$it['send_at']}}</th>
                                <th>{{$it['created_at']}}</th>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="box-footer">
                <button type="button" class="btn btn-cancel pull-left" onclick="colse()">返回</button>
                </div>
            </div>
        </div>
    </section>
    <script>
        function colse(){
            var index=parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
    </script>
@endsection
@extends('layouts.layer')