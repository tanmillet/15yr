@section('css')
    <link rel="stylesheet" href="{{ $assets_url }}/plugins/select2/select2.min.css">
    <style>
        .select2-container .select2-selection--single{
            height: 32px;
        }
    </style>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content" id="pjax-container">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body" style="display: block;">
                        <form method="POST" id="form" action="{{ url('/game/realGood/update') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="game" class="col-sm-2 control-label">游戏</label>
                                    <div class="col-sm-10">
                                           <select name="game" class="form-control"  id="game" disabled>
                                            <option
                                                value="">请选择游戏
                                            </option>
                                            @foreach(config("game.game") as $game)
                                                <option
                                                @if($info->game == $game['value'] )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $game['value'] }}">{{$game['name'] }}
                                                </option>
                                            @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="real_order" class="col-sm-2 control-label">实物订单号</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="real_order" value="{{ $info->real_order }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="uid" class="col-sm-2 control-label">用户ID</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="uid" value="{{ $info->uid }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="uname" class="col-sm-2 control-label">用户姓名</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="uname" value="{{ $info->uname }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="ob_goods_id" class="col-sm-2 control-label">实际道具表ID</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="ob_goods_id" value="{{ $info->ob_goods_id }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gname" class="col-sm-2 control-label">实际道具名</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="gname" value="{{ $info->gname }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="num" class="col-sm-2 control-label">道具数量</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="num" value="{{ $info->num }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mobile" class="col-sm-2 control-label">手机号</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="mobile" value="{{ $info->mobile }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">详细地址</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="address" value="{{ $info->address }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="real_name" class="col-sm-2 control-label">用户真实名字</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="real_name" value="{{ $info->real_name }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="zip_code" class="col-sm-2 control-label">邮编</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="zip_code" value="{{ $info->zip_code }}" disabled>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">订单状态</label>
                                    <div class="col-sm-10">
                                        <select name="status" class="form-control"  id="status">
                                            <option
                                                value="">请选择订单状态
                                            </option>
                                            @foreach($status_arr as $k=>$v)
                                                <option
                                                    
                                                @if($info->status == $k )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $k }}">{{$v }}
                                                </option>
                                            @endforeach
                                    </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="fast_type" class="col-sm-2 control-label">快递类型</label>
                                    <div class="col-sm-10">
                                            <select name="fast_type" class="form-control"  id="fast_type">
                                            <option
                                                value="">请选择快递类型
                                            </option>
                                            @foreach($fast_type_arr as $k=>$v)
                                                <option
                                                @if($info->fast_type == $k )
                                                   {{ 'selected' }}
                                                @endif
                                                
                                                value="{{ $k }}">{{$v }}
                                                </option>
                                            @endforeach
                                            </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="fast" class="col-sm-2 control-label">快递单号</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="fast" value="{{ $info->fast }}" >
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="remark" class="col-sm-2 control-label">备注</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="remark" value="{{ $info->remark }}" >
                                    </div>
                                </div>
      
 
                                <input type="hidden"  name="id" value="{{ $info->id }}" >

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-cancel pull-left" onclick="colse()">返回</button>
                                <button type="submit" class="btn btn-info pull-right _submitajax_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/realGood/index") }}">提交</button>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
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
@section('js')
<script src="{{ $assets_url }}/plugins/select2/select2.full.min.js"></script>
<script src="{{ $assets_url }}plugins/select2/i18n/zh-CN.js"></script>
<script data-exec-on-popstate>
    /*$(function () {
        $("#type").select2({allowClear: true});
    });*/
</script>
@endsection
@extends('layouts.layer')