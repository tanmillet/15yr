
@if (!isset($item))
    @section('title', "游戏管理")
    @section('content_title', '跑马灯')
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
                        <form method="POST" id="form" action="{{ url('/game/rollnotice/opeary') }}{{!isset($item)?"/0":""}}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
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
                                    <label for="pfid" class="col-sm-2 control-label">渠道</label>
                                    <div class="col-sm-10">
                                                
                                                @foreach(config("game.pfid") as $pfid=>$nane)
                                                <label for="pfid" class="col-sm-2 control-label">
                                                    <input type="checkbox" name="pfid[]" style="float:left"
                                                    @if(isset($item)&&in_array($pfid,explode(",",$item->pfid)) )
                                                       {{ 'checked' }}
                                                    @endif

                                                    value="{{ $pfid }}"><font style="float:left">{{$nane }}</font>
                                                </label>
                                                @endforeach
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="mobile_type" class="col-sm-2 control-label">渠道</label>
                                    <div class="col-sm-10">
                                                
                                                @foreach(config("game.mobile_type") as $mobile_type=>$nane)
                                                <label for="mobile_type" class="col-sm-2 control-label">
                                                    <input type="checkbox" name="mobile_type[]" style="float:left"
                                                    @if(isset($item)&&in_array($mobile_type,explode(",",$item->mobile_type)) )
                                                       {{ 'checked' }}
                                                    @endif

                                                    value="{{ $mobile_type }}"><font style="float:left">{{$nane }}</font>
                                                </label>
                                                @endforeach
                                    </div>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label for="scare_buy_name" class="col-sm-2 control-label">内容</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                            <textarea type="text" class="form-control" name="contens" value="{{ $item->contens }}"  >{{ $item->contens }}</textarea>
                                            @else
                                                <textarea type="text" class="form-control" name="contens" value="" ></textarea>
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="play_type" class="col-sm-2 control-label">播放类型</label>
                                    <div class="col-sm-10">
                                            <select name="play_type" class="form-control"  id="play_type">
                                                <option
                                                    value="">播放类型
                                                </option>
                                                @foreach($playTypeArr as $play_type=>$nane)
                                                    <option
                                                    @if(isset($item)&&$item->play_type == $play_type )
                                                       {{ 'selected' }}
                                                    @endif

                                                    value="{{ $play_type }}">{{$nane }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="play_type" class="col-sm-2 control-label">状态</label>
                                    <div class="col-sm-10">
                                            <select name="status" class="form-control"  id="status">
                                                <option
                                                    value="">状态
                                                </option>
                                                @foreach($statusArr as $status=>$nane)
                                                    <option
                                                    @if(isset($item)&&$item->status == $status )
                                                       {{ 'selected' }}
                                                    @endif

                                                    value="{{ $status }}">{{$nane }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="play_num" class="col-sm-2 control-label">播放次数</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="play_num" value="{{ $item->play_num }}"  >
                                            @else
                                                <input type="text" class="form-control" name="play_num" value="" >
                                            @endif
                                    </div>
                                </div>
                                
   
                                <div class="form-group ">
                                     <label for="role" class="col-sm-2 control-label">播放开始时间</label>
                                     <div class="col-sm-10">
                                    <input id="start_time" name="play_sdate" data-date-format="yyyy-mm-dd hh:ii"  
                                           value="{{isset($item)?$item->play_sdate:date("Y-m-d H:i")}} " 
                                           title="play_sdate" type="text" class="form-control" placeholder="播放开始时间">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="role" class="col-sm-2 control-label">播放结束时间</label>
                                    <div class="col-sm-10">
                                    <input id="end_time" name="play_sfdate" data-date-format="yyyy-mm-dd hh:ii" 
                                           value="{{isset($item)?$item->play_sfdate:date("Y-m-d H:i")}} " 
                                           title="play_sfdate" type="text" class="form-control" placeholder="播放结束时间">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="sort" class="form-control" name="sort" value="{{ $item->sort }}"  >
                                            @else
                                                <input type="sort" class="form-control" name="sort" value="" >
                                            @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">限制用户</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="limit_user" class="form-control" name="limit_user" value="{{ $item->limit_user }}"  >
                                            @else
                                                <input type="limit_user" class="form-control" name="limit_user" value="" >
                                            @endif
                                    </div>
                                </div>
                                
                                <!--<div class="form-group">
                                    <label for="price" class="col-sm-2 control-label">购买所需道具价格</label>
                                    <div class="col-sm-10">
                                    @if (isset($item))
                                        <input type="text"  name="price" value="{{ $item->price }}"  >
                                    @else
                                        <input type="text" class="form-control" name="price" value="" >
                                    @endif
                                    </div>
                                </div>-->
 
                                @if (isset($item))
                                <input type="hidden"  name="id" value="{{ $item->id }}" >
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                @endif
                                
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-cancel pull-left" onclick="colse()">返回</button>
                                <button type="submit" class="btn btn-info pull-right _submitajaxpost_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/rollnotice/index") }}">提交</button>
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

    
            
    $(function () {  
      $('#start_time').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
        $('#end_time').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
     });
</script>
@endsection

@extends('layouts.'.(isset($item)?"layer":"admin"))

