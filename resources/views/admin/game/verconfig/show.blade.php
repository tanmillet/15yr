@if (empty($info))
@section('title', "游戏版本管理")
@section('content_title', '游戏版本信息')
@section('content_title_small', !empty($info)? "编辑ID:  ".$info['id'] : "增加")
@endif

@section('content')
<!-- Main content -->
<section class="content" id="pjax-container">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body" style="display: block;">
                    <form method="POST" id="form" action="{{ url('/game/verconfig/opeary') }}{{empty($info)?"/0":"/".$info["id"]}}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="game" class="col-sm-2 control-label">游戏</label>
                                <div class="col-sm-10">
                                    <select name="game" class="form-control"  id="game">
                                        <option
                                            value="">请选择游戏
                                        </option>
                                        @foreach(config("game.game") as $game)
                                        <option
                                            @if(!empty($info)&&$info["game"] == $game['value'] )
                                            {{ 'selected=selected' }}
                                            @endif

                                            value="{{ $game['value'] }}" >{{$game['name'] }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pfid" class="col-sm-2 control-label">平台</label>
                                <div class="col-sm-10">
                                    <select name="pfid" class="form-control"  id="pfid">
                                        <option
                                            value="">请选择平台
                                        </option>
                                        @foreach(config("game.pfid") as $pfid=>$name)
                                        <option
                                            @if(!empty($info)&&$info["pfid"] == $pfid )
                                            {{ 'selected=selected' }}
                                            @endif
                                            value="{{ $pfid }}" >{{$name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="usid" class="col-sm-2 control-label">平台的子ID</label>
                                <div class="col-sm-10">
                                     @if (!empty($info))
                                    <input type="text" class="form-control"  id="usid" name="usid"  value= @if(isset($info['usid'])){{$info['usid']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="usid" value="1" >
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mobile_type" class="col-sm-2 control-label">手机型号</label>
                                <div class="col-sm-10">
                                    <select name="mobile_type" class="form-control"  id="mobile_type">
                                            <option
                                                value="">请选择平台
                                            </option>
                                            @foreach(config("game.mobile_type") as $mobile_type=>$name)
                                            <option
                                                @if(!empty($info)&&$info["mobile_type"] == $mobile_type )
                                                {{ 'selected=selected' }}
                                                @endif
                                                value="{{ $mobile_type }}" >{{$name }}
                                            </option>
                                        @endforeach
                                        </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                 
                                <label for="app_ver" class="col-sm-2 control-label">上传版本文件</label>
                                <div class="col-sm-10">
                                {!!(new \App\Server\UploadeFile)->showFile("url","/game/common/UploadFile/verconfig","1","*","uploadSucess");!!}
                                </div>
                                @if(!empty($info))
                                 已经上传文件{{$info["url"]}}
                                 @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="filename" class="col-sm-2 control-label">包名</label>
                                <div class="col-sm-10">
                                    @if (!empty($info))
                                    <input type="text" class="form-control"  id="filename" name="filename"  value= @if(isset($info['filename'])){{$info['filename']}}@endif >
                                    @else
                                    <input type="text" class="form-control" id="filename" name="filename" value="1" >
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="size" class="col-sm-2 control-label">包大小</label>
                                <div class="col-sm-10">
                                    @if (!empty($info))
                                    <input type="text" class="form-control"  id="filename" name="size"  value= @if(isset($info['size'])){{$info['size']}}@endif >
                                    @else
                                    <input type="text" class="form-control" id="size" name="size" value="1" >
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="app_ver" class="col-sm-2 control-label">更新大版本号</label>
                                <div class="col-sm-10">
                                    @if (!empty($info))
                                    <input type="text" class="form-control"  id="app_ver" name="app_ver"  value= @if(isset($info['app_ver'])){{$info['app_ver']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="app_ver" value="1" >
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zip_ver" class="col-sm-2 control-label">热更新版本</label>
                                <div class="col-sm-10">
                                     @if (!empty($info))
                                    <input type="text" class="form-control"  id="zip_ver" name="zip_ver"  value= @if(isset($info['zip_ver'])){{$info['zip_ver']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="zip_ver" value="" >
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="upt_type" class="col-sm-2 control-label">是否最新整包</label>
                                <div class="col-sm-10">
                                    <select name="upt_type" class="form-control"  id="upt_type">
                                        <option
                                            value="">请选择
                                        </option>
                                        @foreach($uptTypeArr as $upt_type=>$name)
                                        <option
                                            @if(!empty($info)&&$info["upt_type"] == $upt_type )
                                            {{ 'selected=selected' }}
                                            @endif
                                            value="{{ $upt_type }}" >{{$name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label">更新类型</label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-control"  id="type">
                                        <option
                                            value="">请选择
                                        </option>
                                        @foreach($typeArr as $type=>$name)
                                        <option
                                            @if(!empty($info)&&$info["type"] == $type )
                                            {{ 'selected=selected' }}
                                            @endif
                                            value="{{ $type }}" >{{$name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="uid" class="col-sm-2 control-label">限制用户id</label>
                                <div class="col-sm-10">
                                     @if (!empty($info))
                                    <input type="text" class="form-control"  id="uid" name="uid"  value= @if(isset($info['uid'])){{$info['uid']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="uid" value="" >
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="ip" class="col-sm-2 control-label">限制IP</label>
                                <div class="col-sm-10">
                                    @if (!empty($info))
                                    <input type="text" class="form-control"  id="zip_ver" name="ip"  value= @if(isset($info['ip'])){{$info['ip']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="ip" value="" >
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="device_id" class="col-sm-2 control-label">限制设备id</label>
                                <div class="col-sm-10">
                                    @if (!empty($info))
                                    <input type="text" class="form-control"  id="device_id" name="device_id"  value= @if(isset($info['device_id'])){{$info['device_id']}}@endif >
                                    @else
                                    <input type="text" class="form-control" name="device_id" value="" >
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                            
                        @if (isset($item))
                        <input type="hidden"  name="id" value="{{ $item->id }}" >
                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                        @endif

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-cancel pull-left" onclick="colse()">返回</button>
                        <button type="submit" class="btn btn-info pull-right _submitajax_" data-form-id="form"
                                data-refresh-url="{{ url("/game/verconfig/index") }}">提交</button>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>
</section>
@endsection
<script>
    function uploadSucess(data,file){
        $("#filename").val(file.name)
        $("#size").val(file.size)
    }
 </script>
@extends('layouts.'.(!empty($info)?"layer":"admin"))

