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
                        <form method="POST" id="form" action="{{ url('/game/dws/opeary') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="uid" class="col-sm-2 control-label">用户ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="uid" value="{{ $info['uid'] }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">用户名</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="username" value="{{ $info['uname'] }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">赛季</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="username" value="{{ $info['ddz_name'] }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="group" class="col-sm-2 control-label">段位</label>
                                    <div class="col-sm-10">
                                        <select name="group" class="form-control"  id="group">
                                            @foreach($groupArr as $key=>$name)
                                            <option
                                                @if(isset($info["group"])&&$info["group"] ==$key )
                                                {{ 'selected' }}
                                                @endif

                                                value="{{ $key }}" >{{$name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">阶</label>
                                    <div class="col-sm-10">
                                            <select name="order" class="form-control"  id="order">
                                            @foreach($orderArr as $key=>$name)
                                            <option
                                                @if(isset($info["order"])&&$info["order"] ==$key )
                                                {{ 'selected' }}
                                                @endif

                                                value="{{ $key }}" >{{$name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">星星</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="star"  value="{{$info["star"]}}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label">分数</label>
                                    <div class="col-sm-10">
                                            <input type="text" class="form-control" name="score"  value="{{$info["score"]}}">
                                    </div>
                                </div>
                                <input type="hidden"  name="id" value="{{ $info['id'] }}" >
                            </div>
                            
                            
                            
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right _submitajaxpost_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/dws/index") }}">提交</button>
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