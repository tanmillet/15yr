@section('css')
    <link rel="stylesheet" href="{{ $assets_url}}/plugins/select2/select2.min.css">
<link rel="stylesheet"  href="{{ $assets_url}}/plugins/upload-file/upload.css">
@endsection

    <select class="form-control" name="goods_selectd[]" title="goods" multiple="multiple" id="goods_selectd">
            @foreach($goods_selected_info as $id=>$goods_selected)
                <option disabled="true">{{$goods_selected['type_name']}}</option>
                @foreach($goods_selected['data'] as $id=>$name)
                    <option
                    @if($check_goods_selected)
                            @if(in_array($id,array_keys($check_goods_selected)))
                                {{ 'selected' }}
                            @endif
                    @endif
                    value="{{ $id }}"   >&nbsp;&nbsp;&nbsp;&nbsp;{{$name }}
                    </option>
                @endforeach
            @endforeach
    </select>
    <input  type="hidden" name="{{$return_name}}" id="submit_goods_selected" value="{{$goods_id_str}}">
    

@section('js')
<script src="{{ $assets_url }}/plugins/select2/select2.full.min.js"></script>
<script src="{{ $assets_url }}/plugins/select2/i18n/zh-CN.js"></script>
<script>
    var goods_select_num = {!!$goods_selected_num!!};
    var goods_id_str_selected="";
    $(function(){
        var setx = $('#goods_selectd').select2({
            placeholder: "道具选择",
            language: "zh-CN",
            templateSelection:changeSelect,
        }).on('select2:selecting', function (evt) {
            formatState(evt);
           // show();
       //console.log(evt);
       //var evt.params.data.id;
      //alert(evt.params.data.id);
      // alert(evt.target.id);
    }).on("select2:close", function (evt) {  
        changeSubmitGoods();
    });  

    
    function changeSelect(state){
        var index_id = state.id;
        console.log(goods_select_num[index_id]);
        if(!goods_select_num[index_id]){
            num = 1;
        }else{
            num = goods_select_num[index_id];
        }
        var markup = $("<span>"+state.text+"-数量："+num+"</span>");
        changeSubmitGoods();
       return markup;
    }
    function formatState(evt){
       // if (state.loading) return state.text;
            console.log(evt.params);
            var span_text =evt.params.args.data.text; 
            var index_id = evt.params.args.data.id; 
            var num_text=",数量:1";
            var dom_content= '<div class="form-group" style="margin-top:10px"><label for="scare_buy_name" class="col-sm-2 control-label">道具名称</label>'+
                            '<div class="col-sm-10"> <input type="text" class="form-control"  value="'+span_text+'" readonly="readonly"></div></div>'+
                            '<div class="form-group"><label for="scare_buy_name" class="col-sm-2 control-label">道具数量</label>'+
                            '<div class="col-sm-10" style="margin-top:10px"> <input type="number" class="form-control" name="layui_goods_num" id="layui_goods_num" value="" > </div></div>' ;
            layer.open({
                    type: 1,
                    title:"<h3 style='line-height:40px'>填写道具数量</h3>",
                    skin: 'layui-layer-demo', //样式类名
                    closeBtn: 0, //不显示关闭按钮
                    shadeClose: false, //点击遮罩关闭
                    anim: 2,
                    content:dom_content,
                    area: ['550px', '300px'],
                    btn:['提交'],
                    yes: function (index, layero) {  
                       
                       var num = $(layero).find("#layui_goods_num").val();
                       if(num){
                        goods_select_num[index_id] = num;  
                        layer.close(index);
                        $('#goods_selectd').select2({
                            placeholder: "道具选择",
                            language: "zh-CN",
                            templateSelection:changeSelect,
                        });
                         return true;
                       }{
                           alert("请填写道具数量！")
                           return false;
                       }
                    }
            });
             //var markup = $("<span>"+span_text+num_text+"</span>");
          return false;
    }
    
    function changeSubmitGoods(){
        var goods_selected_num = $('#goods_selectd').val();
        $.each(goods_selected_num,function(key,value){
            goods_selected_num[key] = value+":"+ goods_select_num[value];
        })
        if(goods_selected_num){
            var submit_goods_selected = goods_selected_num.join();
            $("#submit_goods_selected").val(submit_goods_selected);
        }
    }
    
 
  
       //alert(evt.tokenizer);
});
</script>

@endsection