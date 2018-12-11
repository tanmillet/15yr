
@if (!isset($item))
    @section('title', "活动管理")
    @section('content_title', '一元抢购')
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
                        <form method="POST" id="form" action="{{ url('/game/scarebuy/opeary') }}{{!isset($item)?"/0":""}}" class="form-horizontal" accept-charset="UTF-8" pjax-container="">
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
                                    <label for="scare_buy_name" class="col-sm-2 control-label">抢购名称</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="scare_buy_name" value="{{ $item->scare_buy_name }}"  >
                                            @else
                                                <input type="text" class="form-control" name="scare_buy_name" value="" >
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scare_buy_contens" class="col-sm-2 control-label">抢购内容</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="scare_buy_contens" value="{{ $item->scare_buy_contens }}"  >
                                            @else
                                                <input type="text" class="form-control" name="scare_buy_contens" value="" >
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scare_buy_contens" class="col-sm-2 control-label">奖品内容</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="scare_buy_goods_str" value="{{ $item->scare_buy_goods_str }}"  >
                                            @else
                                                <input type="text" class="form-control" name="scare_buy_goods_str" value="" >
                                            @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type" class="col-sm-2 control-label">抢购类型</label>
                                    <div class="col-sm-10">
                                        <select name="type" class="form-control"  id="type" @if (isset($item))disabled="disabled" @endif>
                                            @foreach($type_arr as $k=>$value)
                                                <option
                                            @if (isset($item) &&$item->type == $k )
                                                {{ 'selected' }}
                                            @endif
                                                value="{{ $k }}">{{$value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="space_time" class="col-sm-2 control-label">抢购开奖间隔时间(分钟)</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="space_time" value="{{ $item->space_time }}"  disabled="disabled" >
                                            @else
                                                <input type="text" class="form-control" name="space_time" value="" >
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                     <label for="role" class="col-sm-2 control-label">抢购生效时间</label>
                                     <div class="col-sm-10">
                                    <input id="start_time" name="start_time" data-date-format="yyyy-mm-dd hh:ii"  
                                           value="{{isset($item)?date("Y-m-d H:i",$item->start_time):date("Y-m-d H:i")}} " 
                                           title="start_time" type="text" class="form-control" placeholder="抢购生效时间">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="role" class="col-sm-2 control-label">抢购截止时间</label>
                                    <div class="col-sm-10">
                                    <input id="end_time" name="end_time" data-date-format="yyyy-mm-dd hh:ii" 
                                           value="{{isset($item)?date("Y-m-d H:i",$item->end_time):date("Y-m-d H:i")}} " 
                                           title="end_time" type="text" class="form-control" placeholder="抢购截止时间">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">购买类型</label>
                                    <div class="col-sm-10">
                                            @foreach($money_type as $k=>$val)
                                            <label for="role" class="col-sm-2 control-label" style="text-align:left;width:100%;padding:0px;margin-top:10px">
                                                <input type="checkbox" name="buy_type[]"  
                                                        @if (!empty($dealPrice)&& in_array($k,array_keys($dealPrice)))
                                                            {{ 'checked' }}
                                                        @endif
                                                    value="{{ $k }}">{{$val }}
                                                </input>
                                                <input type="text" name="price_{{$k}}" class="form-control" style="width:95%;float:right" placeholder="{{$val }}数量"+
                                                       @if (!empty($dealPrice)&& in_array($k,array_keys($dealPrice)))
                                                       value="{{$dealPrice[$k]}}"
                                                       @endif
                                                       ></input>
                                            </label>
                                            <br>
                                            @endforeach
                                        
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
                                
                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">机器人数量类型</label>
                                    <div class="col-sm-10">
                                        <select name="robot_num_type" class="form-control"  id="robot_num_type">
                                            @foreach($robot_num_type_arr as $k=>$value)
                                                <option
                                            @if (isset($item) &&$item->robot_num_type == $k )
                                                {{ 'selected' }}
                                            @endif
                                                value="{{ $k }}">{{$value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="robot_num" class="col-sm-2 control-label">机器人数量</label>
                                    <div class="col-sm-10">
                                            @if (isset($item))
                                                <input type="text" class="form-control" name="robot_num" value="{{ $item->robot_num }}"  >
                                            @else
                                                <input type="text" class="form-control" name="robot_num" value="0" >
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
                                <button type="submit" class="btn btn-info pull-right _submitajaxpost_" data-form-id="form"
                                        data-refresh-url="{{ url("/game/scarebuy/index") }}">提交</button>
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

