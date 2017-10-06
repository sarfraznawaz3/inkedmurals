


jQuery(document).ready(function($){


    var inter_update_preview = 0;


    var includemediasupport = '';
    var tinymce_external_plugins = {};

    var builder_all_settings_inited = false;

    var initial_cache_offset_top = -1;

    if(includemediasupport==',filemanager'){
        tinymce_external_plugins = { "filemanager" : dzsprg_builder_settings.thepath + 'filemanager/plugin.min.js'};
    }


    var tinymce_settings = {
        script_url : dzsprg_builder_settings.thepath + 'tinymce/jscripts/tiny_mce/tiny_mce.js'
        ,mode : "textareas"
        ,theme : "modern"
        ,fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt 70px"
        ,plugins : "image,code,media,hr,fullscreen,advlist,fontawesome"+includemediasupport
        ,relative_urls : false
        ,remove_script_host : false
        ,image_advtab: true
        ,convert_urls : true
        ,forced_root_block : ""
        ,extended_valid_elements: 'span[class],a[*]'
        ,content_css: 'http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
        ,theme_advanced_toolbar_location : "top"
        //,theme_advanced_toolbar_align : "left"
        //,theme_advanced_statusbar_location : "bottom"
        ,toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code  | fontawesome | responsivefilemanager | fontsizeselect"

        ,external_filemanager_path:dzsprg_builder_settings.thepath + 'builder/tinymce/filemanager/'
        ,filemanager_title:"Responsive Filemanager"
        ,external_plugins: tinymce_external_plugins
        ,setup : function(ed) {
        }
    };


    if(window.override_tinymce_settings){
        tinymce_settings = $.extend({}, window.override_tinymce_settings);
    }

    console.info(tinymce_settings);

    var _areaCanvas = $('.dzsprg-builder-con--canvas-area').eq(0);
    var _areaLayers = $('.dzsprg-builder-con--layers-area').eq(0);


    dzsscr_init('.scroller-con');


    _areaLayers.children('.dd').nestable({
        'maxDepth':2
        ,action_drag_start: function(_arg,_argItem){
            _argItem.find('.with-tinymce.activated').removeClass('activated').tinymce().remove();

        }
        ,action_drag_stop: function(){
            arrange_layers();
            update_fields({
                'call_from':'stop_sortable'
            });
        }
    });


//    _areaLayers.sortable({
//        placeholder: "ui-state-highlight"
//        ,handle: '.sortable-handle-con'
//        ,start: function(arg1,arg2){
//            var _t = arg2.item;
////            console.info(_t.find('.with-tinymce.activated'))
//            _t.find('.with-tinymce.activated').removeClass('activated').tinymce().remove();
//
//        }
//        ,stop: function(){
//            arrange_layers();
//            update_fields({
//                'call_from':'stop_sortable'
//            });
//        }
//    });






    $('.saveconfirmer').removeClass('active');
//    _areaLayers.disableSelection();



//    console.info($('input[name="bars[mainsettings][maxperc]"]').eq(0).val());
    $( ".jqueryui-slider.for-perc" ).slider({
        range: "max",
        min: 1,
        max: 100,
        value: $('input[name="bars[mainsettings][maxperc]"]').eq(0).val(),
        slide: function( e, ui ) {
//            $( "#amount" ).val( ui.value );
            var _t = $(this);
            var _par = _t.parent();
            _par.find('input').eq(0).val(ui.value);
            _par.find('input').eq(0).trigger('change');
        }
    });

    $('.builder-add-rect').bind('click', click_add_btn);
    $('.builder-add-text').bind('click', click_add_btn);
    $('.builder-add-circ').bind('click', click_add_btn);
    $('input[name="builder_skin_name"]').bind('change', change_skin_name);
    $('.btn-show-select').bind('click', click_show_select);
    $('.btn-save-skin').bind('click', click_save_skin);
    $('.btn-preview').bind('click' ,click_preview);


    $(document).on('mouseover','.builder-layer', handle_mouse);
    $(document).on('mouseleave','.builder-layer', handle_mouse);
    $(document).on('click','.builder-layer--head', click_layer_head);
    $(document).on('click','.dzs-button-on-off,.delete-skin-btn,.btn-insert-icon', handle_mouse);
    $(window).on('scroll', handle_scroll);

    $(document).delegate('.builder-field','change', change_builder_field);
    $(document).delegate('.builder-layer--btn-delete','click', click_layer_delete);
    $(document).delegate('.dzsprg-builder-con--canvas-area > *', 'click', click_layer_in_canvas);


    tinymce_settings.setup  = function(ed) {
        ed.on('change', function(e) {
            update_fields({
                'call_from':'change_tinymce'
            });
        });
    }


    var _c = _areaLayers.children().find('textarea.with-tinymce');
    _c.each(function(){
        var _t = $(this);
        if(_t.hasClass('activated')==false){

            if($.fn.tinymce){

                _t.addClass('activated').tinymce(tinymce_settings);
            }
        }
    })


    $('#tabs-mainsettings').dzstabsandaccordions({
        'design_tabsposition' : 'top'
        ,design_transition: 'fade'
        ,design_tabswidth: 'fullwidth'
        ,toggle_breakpoint : '4000'
        ,toggle_type: 'toggle'
        ,settings_appendWholeContent: true
    });
    init_dzstabsandaccordions();
    reskin_select();
    arrange_layers();

    setTimeout(function(){
        builder_all_settings_inited = true;
        update_fields({
            'call_from':'firsttimeout'
        })
    }, 1000);


    function click_preview(){

        console.info('click_preview()', _areaCanvas.hasClass('inited'));

        if(builder_all_settings_inited){

            if(_areaCanvas.hasClass('inited')){
                _areaCanvas.css('height', $('input[name="bars[mainsettings][height]"]').eq(0).val());
                _areaCanvas.get(0).api_restart_and_reinit();
            }else{
                // _areaCanvas.css('opacity',1);
                _areaCanvas.css('height', $('input[name="bars[mainsettings][height]"]').eq(0).val());
                _areaCanvas.progressbars({
                    'initon' : 'init'
                    ,'maxperc' : $('input[name="bars[mainsettings][maxperc]"]').eq(0).val()
                    ,'maxnr' : $('input[name="bars[mainsettings][maxnr]"]').eq(0).val()
                    ,'animation_time' : $('input[name="bars[mainsettings][animation_time]"]').eq(0).val()
                });
            }
        }
    }

    function click_layer_in_canvas(){
        var _t = $(this);
        var _par = _t.parent();
        var ind = _par.children().index(_t);

        _areaLayers.find('.builder-layer').eq(ind).addClass('active');


        if(_areaLayers.children().eq(ind) && _areaLayers.children().eq(ind).offset()){

            $('html').animate({
                scrollTop: _areaLayers.children().eq(ind).offset().top
            }, 300);
        }
    }
    function click_layer_delete(){
        var r = confirm("Confirm Item Delete");
        var _t = $(this);
        var _par = _t.parent().parent();
        if (r == true) {
            _par.remove();
        }
        arrange_layers();
        update_fields({
            'call_from':'layer_delete'
        });
    }
    function click_save_skin(){

        var mainarray = $('form[name="builder-form"]').serialize();
//        console.info(mainarray);


        if(window.ajaxurl){

        }else{
            window.ajaxurl = 'builder.php';
        }
        var data = {
            action: 'dzsprg_saveskin'
            ,postdata: mainarray
            ,currSkin: dzsprg_builder_settings.currSkin
        };

        $('.saveconfirmer').html('Saving...');
        $('.saveconfirmer').addClass('active');
//        return false;
        $.post(window.ajaxurl, data, function(response) {
            if(window.console != undefined){
                console.log('Got this from the server: ' + response);
            }
            if(response.indexOf('success - ')>-1){
                $('.saveconfirmer').html('Options saved.');
            }else{

                $('.saveconfirmer').html('Seems there was a error saving....');
            }
            setTimeout(function(){

                $('.saveconfirmer').removeClass('active');
            },2000);
        });
        return false;
    }
    function change_skin_name(){
        var _t = $(this);
        $('form#create-custom-skin').attr('action', 'builder.php?skin='+_t.val());
    }
    function click_add_btn(e){
//        console.info(_areaCanvas);
        var _t = $(this);

        var _areaLayers_lars = _areaLayers.find('.dd > .dd-list').eq(0);

        if(_t.hasClass('builder-add-rect')){
            _areaLayers_lars.append(''+dzsprg_builder_settings.struct_layer+'');
        }
        if(_t.hasClass('builder-add-text')){
            _areaLayers_lars.append(''+dzsprg_builder_settings.struct_layer_text+'');
            //_areaLayers_lars.children().last().find('.the-title').html('text');
            _areaLayers_lars.children().last().find('input[name*="[text]"]').html('text');
            _areaLayers_lars.children().last().removeClass('type-rect').addClass('type-text');

            var _c = _areaLayers.children().last().find('textarea.with-tinymce');
            if(_c.hasClass('activated')==false){

                _areaLayers_lars.children().last().find('textarea.with-tinymce').addClass('activated').tinymce(tinymce_settings);
            }
        }
        if(_t.hasClass('builder-add-circ')){
            _areaLayers_lars.append(''+dzsprg_builder_settings.struct_layer_circ+'');
        }

        init_dzstabsandaccordions();
        arrange_layers();
        reskin_select();
        update_fields({
            'call_from':'add_btn'
        });

    }

    function click_show_select(){
        var _t = $(this);
        var _par = _t.parent();

        _par.toggleClass('active');
        $('.super-select--options').eq(0)[0].reinit();
    }

    function change_builder_field(){
        var _t = $(this);
        var _t_name = _t.attr('name');

        if(_t_name.indexOf('[top]')>-1){
            if(_t.val()!='auto'){
                _t.parent().parent().parent().find('input[name*="[bottom]"]').eq(0).val('auto');
            }
        }
        if(_t_name.indexOf('[bottom]')>-1){
            if(_t.val()!='auto'){
                _t.parent().parent().parent().find('input[name*="[top]"]').eq(0).val('auto');
            }
        }
        if(_t_name.indexOf('[left]')>-1){
            if(_t.val()!='auto'){
                _t.parent().parent().parent().find('input[name*="[right]"]').eq(0).val('auto');
            }
        }
        if(_t_name.indexOf('[right]')>-1){
            if(_t.val()!='auto'){
                _t.parent().parent().parent().find('input[name*="[left]"]').eq(0).val('auto');
            }
        }
        update_fields({
            'call_from':'change_builder_field'
        });
    }

    function update_fields(pargs){

        var margs = {

            'call_from': 'default'
        };

        if(pargs){
            margs = $.extend(margs,pargs);
        }

        // console.info(margs);
        // console.info('update_fields()',margs );
        var i3 = 0;
        _areaLayers.find('.builder-layer').each(function(){
            var _t = $(this);
            var ind = _t.parent().children().index(_t);
            var _ite = _areaCanvas.children().eq(ind);

            _t.attr('id','bf'+i3);
            _t.attr('data-index',''+i3);



            //console.info('_areaCanvas - ',_areaCanvas,_areaCanvas.children(),_areaCanvas.children().length);
            //console.info('_ite - ',_ite, ' ind - ',ind);

//            console.info(_t);
            _t.find('.builder-field').each(function(){
                var _t_bf = $(this); // -- builder field


                //console.info(_t_bf);
                var props_obj = {};
                if( _ite.attr('data-animprops')){
                    props_obj = JSON.parse(_ite.attr('data-animprops'));
                }

                if(_t_bf.attr('name')){
                    var arr_labels = ['width','height','top', 'left','bottom','right','margin_top', 'margin_left','margin_bottom','margin_right','border_radius','border','opacity','font_size','color','extra_classes','background_color'];

                    for(var i=0;i<arr_labels.length;i++){

                        if(String(_t_bf.attr('name').indexOf('['+arr_labels[i]+']')) > -1){

                            var aux = arr_labels[i];
                            var val = _t_bf.val();

                            //console.info(aux,' - ',val);
                            if( (aux=='top' || aux=='right' || aux=='bottom' || aux=='left' || aux=='width' || aux=='height') && val.indexOf('%')==-1 && val.indexOf('px')==-1 && val!='auto' ){
                                val+='px';
                            }
                            if((aux=='margin_top' || aux=='margin_right' || aux=='margin_bottom' || aux=='margin_left' || aux=='border_radius' || aux=='font_size' || aux=='background_color')){

                                aux = aux.replace('_', '-');
                            }

                            if((aux=='margin-top' || aux=='margin-right' || aux=='margin-bottom' || aux=='margin-left' || aux=='border-radius' || aux=='font-size' )){

                                if(val.indexOf('%')==-1 && val.indexOf('px')==-1 && val!='auto' && val.indexOf('{{')!=0 ){

                                    val+='px';
                                }
                            }


                            if(aux=='left'){
                                //console.info(_t_bf, val);
                            }

                            //console.info('aux - ',aux,' val - ',val);
                            if(aux=='top'){
                                if(val=='{{center}}' || val=='{{center}}px'){
                                    //console.error("HMM center");

                                    _ite.addClass('center-it-v');
                                    val = '';
                                }
                            }
                            if(aux=='extra_classes'){
                                _ite.addClass(val);
                            }

                            //console.info('label - ', aux, ' || val - ',val);
                            if(val.indexOf('{{')==-1){
                                _ite.css(aux, val);

                            }else{
                                _ite.css(aux, '');
//                                console.info(_ite.attr('animprops'));


                                props_obj[aux] = _t_bf.val();


                            }

                        }
                    }
                    var arr_labels_circ = ['circle_outside_fill','circle_inside_fill','circle_outer_width','circle_line_width'];
                    if(_t.hasClass('type-circ')){

                        for(var j=0;j<arr_labels_circ.length;j++){

                            if(String(_t_bf.attr('name').indexOf('['+arr_labels_circ[j]+']')) > -1){

                                var aux = arr_labels_circ[j];
                                var val = _t_bf.val();

                                props_obj[aux] = _t_bf.val();
                            }
                        }
                    }
//                    if(String(_t_bf.attr('name').indexOf('[text_align]')) > -1){
//                        _areaCanvas.children().eq(ind).css({
//                            'text-align' : _t_bf.val()
//                        })
//
//                    }
                    if(String(_t_bf.attr('name').indexOf('[position_type]')) > -1){

                        _areaCanvas.children().eq(ind).css({
                            'position' : _t_bf.val()
                        })

                    }
                    if(_t_bf.hasClass('with-tinymce') && _t_bf.hasClass('activated')){
//                        console.info(_t_bf, _t_bf.tinymce());
                        if(_t_bf.tinymce()==null){
                            setTimeout(function(argind, arg_t){
//                                console.info(argind, arg_t);
                                if(arg_t.tinymce()!=null) {
                                    var aux =  '';


                                    if(arg_t.tinymce()) {

                                        aux = arg_t.tinymce().getContent({format: 'raw'});
                                    }
                                    _areaCanvas.children().eq(argind).html(aux);
                                    //-- " conflicts with JSON stringify so lets remove dat
                                    aux = aux.replace(/"/g, "");
//                                    props_obj['text'] = aux;
                                }
                            }, 300, ind, _t_bf)
                        }else{

                            var aux =  '';


                            console.warn('_t_bf - ',_t_bf,_t_bf.tinymce());
                            if(_t_bf.tinymce(),_t_bf.tinymce().getContent){

                                aux = _t_bf.tinymce().getContent({format: 'raw'});
                            }
                            _areaCanvas.children().eq(ind).html(aux);
                            //-- " conflicts with JSON stringify so lets remove dat
                            aux = aux.replace(/"/g, "");
//                            props_obj['text'] = aux;
                        }
//

                    }

                    if(String(_t_bf.attr('name').indexOf('[background_color]')) > -1){

                        _areaCanvas.children().eq(ind).css({
                            'background-color' : _t_bf.val()
                        })

                    }

                    _ite.attr('data-animprops', JSON.stringify(props_obj));
                }
            })

            i3++;
        })


        var aux='';
        aux+='&lt;div class="dzs-progress-bar auto-init skin-'+dzsprg_builder_settings.currSkin+'" style="';

        var arr_labels = ['width','height','margin_top', 'margin_left','margin_bottom','margin_right','max_width'];

//        console.info($('input[name*="bars[mainsettings]"]'));
        $('input[name*="bars[mainsettings]"]').each(function(){
            var _t2 = $(this);

//            console.info(_t2, aux);
            for(var i=0;i<arr_labels.length;i++) {

                //console.info('i - ',i, arr_labels[i], '_t2 - ',_t2, String(_t2.attr('name').indexOf('[' + arr_labels[i] + ']')))

                if (String(_t2.attr('name').indexOf('[' + arr_labels[i] + ']')) > -1) {

                    var aux_lab = arr_labels[i];
                    var val = _t2.val();

                    if ((aux_lab == 'margin_top' || aux_lab == 'margin_right' || aux_lab == 'margin_bottom' || aux_lab == 'margin_left' || aux_lab == 'max_width')) {

                        aux_lab = aux_lab.replace('_', '-');
                        if (val.indexOf('%') == -1 && val != 'auto') {

                            val += 'px';
                        }
                    }
                    aux+=''+aux_lab+':'+val+';';
                    //console.info('found _t2 - ',_t2,'i - ',i, arr_labels[i] , aux_lab, aux);
                }
            }
        })
        aux+='"';

        aux+=' data-animprops=\'{';

        var auxlab = 'animation_time';
        var firstset = false;
        if($('input[name*="bars[mainsettings]['+auxlab+']"]').length>0 && $('input[name*="bars[mainsettings]['+auxlab+']"]').val()!=''){
            aux+='"'+auxlab+'":"'+$('input[name*="bars[mainsettings]['+auxlab+']"]').val()+'"';
            firstset=true;
        }

        auxlab = 'maxperc';
        if($('input[name*="bars[mainsettings]['+auxlab+']"]').length>0 && $('input[name*="bars[mainsettings]['+auxlab+']"]').val()!=''){
            if(firstset){ aux+=','; };
            aux+='"'+auxlab+'":"'+$('input[name*="bars[mainsettings]['+auxlab+']"]').val()+'"';
            firstset=true;
        }
        auxlab = 'maxnr';
        if($('input[name*="bars[mainsettings]['+auxlab+']"]').length>0 && $('input[name*="bars[mainsettings]['+auxlab+']"]').val()!=''){
            if(firstset){ aux+=','; };
            aux+='"'+auxlab+'":"'+$('input[name*="bars[mainsettings]['+auxlab+']"]').val()+'"';
            firstset=true;
        }
        auxlab = 'initon';
        if($('select[name*="bars[mainsettings]['+auxlab+']"]').length>0 && $('select[name*="bars[mainsettings]['+auxlab+']"]').val()!=''){
            if(firstset){ aux+=','; };
            aux+='"'+auxlab+'":"'+$('select[name*="bars[mainsettings]['+auxlab+']"]').val()+'"';
            firstset=true;
        }


        aux+='}\''

        aux+='&gt;';

        var aux_items = htmlEncode(_areaCanvas.html());
//        console.info(aux_items);

        // -- sanitizing
        aux_items = aux_items.replace(/id="(.*?)" /g ," ");
        aux_items = aux_items.replace(/data-mce-style="(.*?)"/g ,"");
        aux_items = aux_items.replace(/data-animprops="(.*?)" /g ,"data-animprops='$1' ");
        aux_items = aux_items.replace(/&amp;quot;/g,'"');


//        console.info(aux_items);
        aux+=aux_items ;
        aux+='&lt;/div&gt;';

        $('.dzsprg-output-div').html(aux);


        if(inter_update_preview){
            clearTimeout(inter_update_preview);
        }

        inter_update_preview = setTimeout(update_preview,100);
    }

    function update_preview(){

        click_preview();
    }

    function init_dzstabsandaccordions(){

        _areaLayers.children().find('.dzs-tabs').dzstabsandaccordions({
            'design_tabsposition' : 'top'
            ,design_transition: 'fade'
            ,design_tabswidth: 'fullwidth'
            ,toggle_breakpoint : '4000'
            ,toggle_type: 'toggle'
            ,settings_appendWholeContent: true
        });

        reskin_select();
        window.farbtastic_reinit();


        $( ".jqueryui-slider.for-fontsize" ).slider({
            range: "max",
            min: 11,
            max: 72,
            value: 24,
            slide: function( e, ui ) {
//            $( "#amount" ).val( ui.value );
                var _t = $(this);
                var _par = _t.parent();
                _par.find('input').eq(0).val(ui.value);
                _par.find('input').eq(0).trigger('change');
            }
        });
    }

    function arrange_layers(){

        console.error('arrange_layers', _areaCanvas);
        _areaCanvas.children().remove();


        var _c = $('*[name="bars[mainsettings][max_width]"]').eq(0);

        if(_c.val()){
            _areaCanvas.css({
                'max-width': _c.val()+'px'
                ,'margin-left': 'auto'
                ,'margin-right': 'auto'
            })
        }
//        console.info(_areaLayers.children());
        var i3 = 0;
        for(var i=0;i<_areaLayers.find('.builder-layer').length;i++){
            var _layer = _areaLayers.find('.builder-layer').eq(i);

            _layer.find('textarea.with-tinymce').each(function(){
                var _t = $(this);
                if(_t.hasClass('activated')==false){
                    if($.fn.tinymce) {
                        _t.addClass('activated').tinymce(tinymce_settings);
                    }
                }
            })


            //console.info('_layer - ',_layer, i);

            _layer.find('*[name^="bars["]').each(function(){
                var _t = $(this);
//                console.info(_t);

                var aux = _t.attr('name');

                aux = aux.replace(/bars\[(0|[1-9][0-9]*)\]/g, "bars["+i+"]");

                _t.attr('name',aux);
            })

//            console.info(_layer);

            var aux_type = _layer.find('.the-title').eq(0).attr('data-type');

            if(aux_type=='circ'){
                _areaCanvas.append('<canvas id="pbi-frombuilder-'+i+'" class="progress-bar-item progress-bar-item--'+aux_type+'" data-type="'+aux_type+'"></canvas>')
            }else{
                _areaCanvas.append('<div id="pbi-frombuilder-'+i+'"  class="progress-bar-item progress-bar-item--'+aux_type+'" data-type="'+aux_type+'"></div>')
            }

        }


//        console.info($('.dzsprg-output-con'), String(htmlEncode(_areaCanvas.html())));
    }

    function htmlEncode(value){
        //create a in-memory div, set it's inner text(which jQuery automatically encodes)
        //then grab the encoded contents back out.  The div never exists on the page.
        return $('<div/>').text(value).html();
    }

    function htmlDecode(value){
        return $('<div/>').html(value).text();
    }

    function handle_scroll(e){

        var st = $(window).scrollTop();

        var _cache = $('.main-col-for-canvas');

        // console.info(st, _cache.offset().top);


        if(initial_cache_offset_top==-1){
            initial_cache_offset_top = _cache.offset().top;
        }

        var auxt = st - initial_cache_offset_top + 10;


        _cache.css('top','0');

        if(auxt + initial_cache_offset_top > $('#tabs-mainsettings').offset().top - _cache.outerHeight()- 10 ){


            auxt = $('#tabs-mainsettings').offset().top - _cache.outerHeight() - initial_cache_offset_top - 10;
            console.warn('hitlimit', $('#tabs-mainsettings').offset().top , _cache.outerHeight(), initial_cache_offset_top, auxt);
        }

        if(st>initial_cache_offset_top){

        }else{
            auxt = 0;
        }



        _cache.css('top', auxt);

    }
    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='mouseover'){
            if(_t.hasClass('builder-layer')){
                //console.info('mouseover');


                var aux = '#pbi-frombuilder-'+_t.attr('data-index');
                //console.info(aux);
                $(aux).addClass('hovered');
            }
        }
        if(e.type=='mouseleave'){

            if(_t.hasClass('builder-layer')) {
                $('.progress-bar-item').removeClass('hovered');
                if (_t.hasClass('builder-layer--head')) {
                    console.info('mouseleave');
                }
            }
        }
        if(e.type=='click'){

            if(_t.hasClass('btn-insert-icon')) {
                var _con=_t.parent();
                var _conpar=_t.parent().parent().parent();


                var val = _con.find('.iconselector-waiter').eq(0).val();

                var tid = _conpar.find('.with-tinymce').eq(0).attr('id');
                var ed = tinyMCE.get(tid);

                if(ed){
                    var data2 = ed.getContent({format: "raw"});

                    console.info(data2);

                    if(data2.indexOf('</div>')>-1){

                        data2 = data2.replace(new RegExp('<\/div>$'), '<i class="fa fa-'+val+'"></i></div>');
                    }else{
                        data2+='<i class="fa fa-'+val+'"></i>';
                    }

                    ed.setContent(data2, {format : 'raw'});
                    _conpar.find('.with-tinymce').trigger('change');

                    setTimeout(function(){
                        console.log('content - ',ed.getContent({format : 'raw'}));
                    },1000)
                }
                console.warn(val, _conpar.find('.with-tinymce').eq(0),_conpar.find('.with-tinymce').eq(0).attr('id') );



                return false;
            }
            if(_t.hasClass('delete-skin-btn')) {

                var _con = _t.parent().parent();

                console.info("CEVA");
                var mainarray = $('form[name="builder-form"]').serialize();
//        console.info(mainarray);


                if(window.ajaxurl){

                }else{
                    window.ajaxurl = 'builder.php';
                }
                var data = {
                    action: 'dzsprg_ajax_delete_skin'
                    ,postdata: _con.attr('data-skin')
                    ,nonce: _t.attr('data-nonce')
                };

                $('.saveconfirmer').html('Saving...');
                $('.saveconfirmer').addClass('active');
//        return false;
                $.post(window.ajaxurl, data, function(response) {
                    if(window.console != undefined){
                        console.log('Got this from the server: ' + response);
                    }
                    if(response.indexOf('success - ')>-1){
                        $('.saveconfirmer').html('Options saved.');

                        _con.remove();

                        setTimeout(function(){

                            if(_con.parent().parent().hasClass('zfolio')){

                                console.warn("HANDLE RESIZE",_con.parent().parent().get(0),_con.parent().parent().get(0).api_handle_resize )
                                _con.parent().parent().get(0).api_handle_resize();
                            }
                        },500)
                    }else{

                        $('.saveconfirmer').html('Seems there was a error saving....');
                    }
                    setTimeout(function(){

                        $('.saveconfirmer').removeClass('active');
                    },2000);
                });
                return false;
            }

            if(_t.hasClass('active')) {



                var _ca = $('.skins-first-models').eq(0);
                var _cn = $('.skins-all-models').eq(0);

                _ca.css('height', 'auto');
                _ca.css('display', 'block');

                var auxh = _ca.height();
                var auxbh = _cn.height();

                _ca.css('height', '0');
                _cn.css('height', auxbh);

                setTimeout(function(){
                    _ca.css('height', auxh);
                    _cn.css('height', 0);

                },50)

                setTimeout(function(){
                    _ca.css('height', 'auto');
                    _cn.css('display', 'none');

                },450)

                _ca.removeClass('nonactive').addClass('active');
                _cn.removeClass('active').addClass('nonactive');




                _t.removeClass('active');


            }else{

                var _ca = $('.skins-all-models').eq(0);
                var _cn = $('.skins-first-models').eq(0);

                _ca.css('height', 'auto');
                _ca.css('display', 'block');

                var auxh = _ca.height();
                var auxbh = _cn.height();

                _ca.css('height', '0');
                _cn.css('height', auxbh);

                setTimeout(function(){
                    _ca.css('height', auxh);
                    _cn.css('height', 0);



                },50)

                setTimeout(function(){
                },250)

                setTimeout(function(){
                    _ca.css('height', 'auto');
                    _cn.css('display', 'none');

                    dzszfl_init(_ca.find('.zfolio').eq(0), {init_each: true});
                    dzsprb_init(_ca.find('.zfolio .dzs-progress-bar'), {init_each: true});

                    setTimeout(function(){
                        _ca.find('.zfolio').eq(0).get(0).api_handle_resize();
                    },100);
                },450)

                _ca.removeClass('nonactive').addClass('active');
                _cn.removeClass('active').addClass('nonactive');



                _t.addClass('active');
            }
        }
    }

    function click_layer_head(e){
        var _t = $(this);

        if(_t.find('.sortable-handle-con').has($(e.target)).length > 0){
            return ;
        }
        _t.parent().toggleClass('active');
    }

    function reskin_select(){
        $(document).undelegate(".select-wrapper select", "change");
        $(document).delegate(".select-wrapper select", "change",  change_select);

//        console.info($('select'));
        $('select.styleme').each(function(){

            var _cache = $(this);
//            console.log(_cache);

            if(_cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
                return;
            }
            var sel = (_cache.find(':selected'));
//            console.info(sel, _cache.val());
            _cache.wrap('<div class="select-wrapper"></div>')
            _cache.parent().prepend('<span>' + sel.text() + '</span>');
            _cache.trigger('change');
        })


        function change_select(){
            var selval = ($(this).find(':selected').text());
            $(this).parent().children('span').text(selval);
        }

    }
});



/* @projectDescription jQuery Serialize Anything - Serialize anything (and not just forms!)
 * @author Bramus! (Bram Van Damme)
 * @version 1.0
 * @website: http://www.bram.us/
 * @license : BSD
 */

(function($) {

    $.fn.serializeAnything = function() {

        var toReturn    = [];
        var els         = $(this).find(':input').get();

        $.each(els, function() {
            if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
                var val = $(this).val();
                toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
            }
        });

        return toReturn.join("&").replace(/%20/g, "+");

    }

})(jQuery);