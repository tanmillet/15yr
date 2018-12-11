@if (!isset($item))
@section('title', "道具管理")
@section('content_title', '道具信息')
@section('content_title_small', isset($item) ? "编辑ID: $item->id " : "增加")
@endif

<!-- 图片上传即使预览插件 -->

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

                                            value="{{ $game['value'] }}" >{{$game['name'] }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_name" class="col-sm-2 control-label">道具名称</label>
                            <div class="col-sm-10">
                                @if (isset($item))
                                <input type="text" class="form-control"  id="name" name="name"  value= @if(isset($goodsInfo['name'])){{$goodsInfo['name']}}@endif >
                                @else
                                <input type="text" class="form-control" name="name" value="" >
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">菜谱封面</label>
                        <div class="col-sm-10">
                           {!!(new \App\Server\UploadeFile)->showImage();!!}
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具描述</label>
                            <div class="col-sm-10">
                               <input type="text" id="remark" class="form-control" name="remark"   value= @if(isset($goodsInfo['remark'])){{$goodsInfo['remark']}}@endif>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">邮件内容</label>
                            <div class="col-sm-10">
                               <input type="text" id="email_content" class="form-control" name="email_content"   value= @if(isset($goodsInfo['email_content'])){{$goodsInfo['email_content']}}@endif>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">显示场景</label>
                            <div class="col-sm-10">
                               <select name="show_type" id="show_type" class="form-control">
                                @foreach($goods['show']['show_type'] as $key=>$val)
                                <option value="{{$key}}"  @if(isset($goodsInfo['show_type'])&&$key==$goodsInfo['show_type']) selected="selected" @endif>{{$val}}</option>
                                 @endforeach
                                </select>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">彩券商城类型</label>
                            <div class="col-sm-10">
                               <select name="ticket_show_type" id="show_type" class="form-control">
                                @foreach($goods['show']['ticket_show_type'] as $key=>$val)
                                <option value="{{$key}}"  @if(isset($goodsInfo['ticket_show_type'])&&$key==$goodsInfo['ticket_show_type']) selected="selected" @endif>{{$val}}</option>
                                 @endforeach
                                </select>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具类型</label>
                            <div class="col-sm-10">
                              <select name="type" id="type" class="form-control">
                                @foreach($goods['show']['goods_type'] as $key=>$val)
                                <option value="{{$key}}"   @if(isset($goodsInfo['type'])&&$key==$goodsInfo['type']) selected @endif>{{$val}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                         
                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">使用次数</label>
                            <div class="col-sm-10">
                               <input  id="use_number" class="form-control" name="use_number"  value= @if(isset($goodsInfo['use_number'])){{$goodsInfo['use_number']}}@endif>
                            </div>
                        </div>
                       <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具数量</label>
                            <div class="col-sm-10">
                               <input  id="number" name="number"  class="form-control" value= @if(isset($goodsInfo['number'])){{$goodsInfo['number']}}@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具描述</label>
                            <div class="col-sm-10">
                               <input type="text" id="remark" class="form-control" name="remark"   value= @if(isset($goodsInfo['remark'])){{$goodsInfo['remark']}}@endif>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">邮件内容</label>
                            <div class="col-sm-10">
                               <input type="text" id="email_content" class="form-control" name="email_content"   value= @if(isset($goodsInfo['email_content'])){{$goodsInfo['email_content']}}@endif >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">显示场景</label>
                            <div class="col-sm-10">
                              <select name="show_type" id="show_type"  class="form-control">
                                        @foreach($goods['show']['show_type'] as $key=>$val)
                                        <option value="{{$key}}"  @if(isset($goodsInfo['show_type'])&&$key==$goodsInfo['show_type']) selected="selected" @endif>{{$val}}</option>
                                         @endforeach
                                        </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">彩券商城类型</label>
                            <div class="col-sm-10">
                                <select name="ticket_show_type" id="show_type" class="form-control">
                                @foreach($goods['show']['ticket_show_type'] as $key=>$val)
                                <option value="{{$key}}"  @if(isset($goodsInfo['ticket_show_type'])&&$key==$goodsInfo['ticket_show_type']) selected="selected" @endif>{{$val}}</option>
                                 @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具类型</label>
                            <div class="col-sm-10">
                               <select name="type" id="type" class="form-control">
                                    @foreach($goods['show']['goods_type'] as $key=>$val)
                                    <option value="{{$key}}"   @if(isset($goodsInfo['type'])&&$key==$goodsInfo['type']) selected @endif>{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                            
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">使用次数</label>
                            <div class="col-sm-10">
                               <input  id="use_number" name="use_number" class="form-control" value= @if(isset($goodsInfo['use_number'])){{$goodsInfo['use_number']}}@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具数量</label>
                            <div class="col-sm-10">
                               <input  id="number" name="number" class="form-control" value= @if(isset($goodsInfo['number'])){{$goodsInfo['number']}}@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">赠送数量</label>
                            <div class="col-sm-10">
                               <input  id="number" name="give_number" class="form-control" value= @if(isset($goodsInfo['give_number'])){{$goodsInfo['give_number']}}@endif>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">购买类型</label>
                            <div class="col-sm-10">
                               <select name="is_buy" id="is_buy" class="form-control">
                                    @foreach($goods['show']['is_buy'] as $key=>$val)
                                    <option value="{{$key}}"  @if(isset($goodsInfo['is_buy'])&&$key==$goodsInfo['is_buy']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">是否显示</label>
                            <div class="col-sm-10">
                               <select name="is_show" id="is_show" class="form-control">
                                    @foreach($goods['show']['is_show'] as $key=>$val)
                                    <option value="{{$key}}" @if(isset($goodsInfo['is_show'])&&$key==$goodsInfo['is_show']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">背包类型</label>
                            <div class="col-sm-10">
                               <select name="bp_type" id="bp_type" class="form-control">
                                    @foreach($goods['show']['bp_type'] as $key=>$val)
                                    <option value="{{$key}}"  @if(isset($goodsInfo['bp_type'])&&$key==$goodsInfo['bp_type']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">背包是否显示</label>
                            <div class="col-sm-10">
                               <select name="bp_isshow" id="bp_isshow" class="form-control">
                                    @foreach($goods['show']['bp_isshow'] as $key=>$val)
                                    <option value="{{$key}}" @if(isset($goodsInfo['bp_isshow'])&&$key==$goodsInfo['bp_isshow']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">使用次数</label>
                            <div class="col-sm-10">
                               <input  id="use_number" name="use_number" class="form-control" value= @if(isset($goodsInfo['use_number'])){{$goodsInfo['use_number']}}@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具有效时间类型</label>
                            <div class="col-sm-10">
                               <select name="valid_time_type" id="valid_time_type" class="form-control">
                                    @foreach($goods['show']['valid_time_type'] as $key=>$val)
                                    <option value="{{$key}}"  @if(isset($goodsInfo['valid_time_type'])&&$key==$goodsInfo['valid_time_type']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">道具有效时间（小时）</label>
                            <div class="col-sm-10">
                               <input  id="valid_time" name="valid_time" class="form-control" value= @if(isset($goodsInfo['valid_time'])){{$goodsInfo['valid_time']}}@endif>
                            </div>
                        </div>
                        <div class="form-group">
                           <label for="scare_buy_contens" class="col-sm-2 control-label">购买类型</label>
                            <div class="col-sm-10">
                                <table class="table table-bordered">
                               @foreach($goods['show']['buy_type'] as $key=>$val)
                               <tr>
                                   <th class="text-center" style="vertical-align: middle!important;">{{$val}}</th>
                                   <th>
                                <div class="col-xs-2">实价<input type="text" class="form-control input-mini" name="price_{{$key}}"value= @if(isset($goods['show']['goods_price'][$key]['price'])){{$goods['show']['goods_price'][$key]['price']}}@endif></div>
                                        <div class="col-xs-2"> 虚假 <input type="text"  class="form-control"  name="sham_price_{{$key}}"value= @if(isset($goods['show']['goods_price'][$key]['sham_price'])){{$goods['show']['goods_price'][$key]['sham_price']}}@endif></div>
                                        <div class="col-xs-2">第三方道具ID<input type="text"  class="form-control" name="three_gid_{{$key}}"value= @if(isset($goods['show']['goods_price'][$key]['three_gid'])){{$goods['show']['goods_price'][$key]['three_gid']}}@endif></div>
                                    </th>
                                </tr>
                             @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">VIP等级</label>
                            <div class="col-sm-10">
                               <select name="is_vip" id="is_vip" class="form-control">
                                    @foreach($goods['show']['is_vip'] as $key=>$val)
                                    <option value="{{$key}}"  @if(isset($goodsInfo['is_vip'])&&$key==$goodsInfo['is_vip']) selected="selected" @endif>{{$val}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="scare_buy_contens" class="col-sm-2 control-label">赠送道具(道具id:赠送道具分数,道具id,如果没有填写分数默认是1份)</label>
                            <div class="col-sm-10">
                               {!!(new \App\Server\GoodsSelected)->show();!!}
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

@extends('layouts.'.(isset($item)?"layer":"admin"))

