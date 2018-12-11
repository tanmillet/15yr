@section('title', "短信记录")
@section('content_title', '短信记录')
@section('content_title_small', "发送")
@section('css')
    <link rel="stylesheet" href="{{ $assets_url}}/plugins/select2/select2.min.css">
@endsection
@section('content')
    <!-- Main content -->
    <section class="content" id="pjax-container">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body" style="display: block;">
                        <form method="POST" id="form" action="{{ url('/game/mobliesmslog/opeary') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="mobile" class="col-sm-2 control-label">手机号码</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="mobile" value="" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">发送内容</label>
                                    <div class="col-sm-10">
                                        亲爱的玩家，恭喜您在游戏中获得<input type="text" class="form-control" name="goods_name" value="" >,
                                        请根据账号密码自行充值。若有疑问请联系客服！感谢您对我们的支持！<br>
                                        卡号：<input type="text" class="form-control" name="user" value="" ><br>
                                        卡密：<input type="text" class="form-control" name="password" value="" >
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right _submit_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/mobilesmslog/index") }}">提交</button>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script src="{{ $assets_url }}/plugins/select2/select2.full.min.js"></script>
<script src="{{ $assets_url }}/plugins/select2/i18n/zh-CN.js"></script>

@endsection
@extends('layouts.admin')