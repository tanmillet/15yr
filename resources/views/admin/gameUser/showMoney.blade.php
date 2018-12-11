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
                        <form method="POST" id="form" action="{{ url('/game/opearymoneny') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">用户名</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="username" value="{{ $user->uname }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">金钱</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="uchip" value="{{ $user->uchip }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">钻石</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="udiamond" value="{{ $user->udiamond }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">彩券</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="utombola" value="{{ $user->utombola }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="IP" class="col-sm-2 control-label">注册IP</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="ip" value="{{ $ip }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">修改货币类型</label>
                                    <div class="col-sm-10">
                                        <select name="moneyType" class="form-control"  id="moneyType">
                                            @foreach($moneyType as $moneyName=>$value)
                                                <option
                                                
                                                value="{{ $moneyName }}">{{$value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">修改类型</label>
                                    <div class="col-sm-10">
                                        <select name="moneyFlagType" class="form-control"  id="moneyFlagType">
                                            @foreach($moneyFlagType as $k=>$val)
                                                <option
                                                value="{{ $k }}">{{$val }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">数量</label>
                                    <div class="col-sm-10">
                                            <input type="input" class="form-control" name="money" >
                                    </div>
                                </div>
 
                                <input type="hidden"  name="uid" value="{{ $user->uid }}" >

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-cancel pull-left" onclick="colse()">返回</button>
                                <button type="submit" class="btn btn-info pull-right _submitajax_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/gameuserinfo") }}">提交</button>
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