@if (!isset($item))
    @section('title', "公告管理")
    @section('content_title', '游戏公告')
    @section('content_title_small', isset($item) ? "编辑ID: $item->id " : "增加")
@endif
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
                        <form method="POST" id="form" action="{{ url('/game/notice/opeary') }}{{!isset($item)?"/0":""}}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
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
                                                    @if(isset($item)&&$item->game == $game['value'] )
                                                       {{ 'selected' }}
                                                    @endif

                                                    value="{{ $game['value'] }}">{{$game['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scare_buy_name" class="col-sm-2 control-label">左侧菜单标题</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="left_title" value="{{ $item->left_title }}"  >
                                            @else
                                                <input type="text" class="form-control" name="left_title" value="" >
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scare_buy_contens" class="col-sm-2 control-label">公告标题</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="title" value="{{ $item->title }}"  >
                                            @else
                                                <input type="text" class="form-control" name="title" value="" >
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scare_buy_contens" class="col-sm-2 control-label">公告内容</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="content" value="{{ $item->content }}"  >
                                            @else
                                            <textarea  class="form-control" name="content" value="" style="height:128px" ></textarea>
                                            @endif
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
                                        data-refresh-url="{{ url("/game/notice/index") }}">提交</button>
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
<script>

    
            
   
</script>
@endsection

@extends('layouts.'.(isset($item)?"layer":"admin"))

