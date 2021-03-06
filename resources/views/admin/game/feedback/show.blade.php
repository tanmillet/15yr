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
        <div class="row" style="overflow:auto">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body" style="display: block;">
                        <form method="POST" id="form" action="{{ url('/game/feedback/opeary') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">用户ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="uid" value="{{ $info['uid'] }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">用户名</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="username" value="{{ $info['uname'] }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">反馈内容</label>
                                    <div class="col-sm-10">
                                            <textarea  class="form-control" disabled>{{ $info['contents'] }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">反馈内容</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="email_contents" >
                                    </div>
                                </div>

 
                                <input type="hidden"  name="id" value="{{ $info['id'] }}" >
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right _submitajaxpost_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/feedback/index") }}">提交</button>
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