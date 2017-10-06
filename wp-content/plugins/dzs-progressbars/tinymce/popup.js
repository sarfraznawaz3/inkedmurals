

var includemediasupport = '';



function htmlEncode(arg){
    return $('<div/>').text(arg).html();
}

function htmlDecode(value){
    return $('<div/>').html(arg).text();
}



jQuery(document).ready(function($){



    var tinymce_settings = {
        script_url : dzsprg_builder_settings.base_url + 'tinymce/tinymce/tinymce.min.js'
        ,mode : "textareas"
        ,theme : "modern"
        ,plugins : "image,code,media,hr,fullscreen,advlist"
        ,relative_urls : false
        ,remove_script_host : false
        ,image_advtab: true
        ,convert_urls : true
        ,forced_root_block : ""
        ,extended_valid_elements: 'span[class],a[*]'
        ,content_css: '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css'
        ,theme_advanced_toolbar_location : "top"
        //,theme_advanced_toolbar_align : "left"
        //,theme_advanced_statusbar_location : "bottom"
        ,toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code | addimagebutton | fontawesome "

        ,setup : function(ed) {

        }
    };




    //--- 1 generator per page
    var coll_buffer=0;
    var fout='';
    var file_frame;
    var auxa = [];
    var inter_refresh_preview = null;

    var _cmain = $('#generator-dzsprg');
    
    reskin_select();

    var shortcode_inshortcode_actualcontent = '';



    var initmarkup_ishtml = false;
    var initmarkup_isshortcode = false;

    //console.info($('.changer-skin'));

    $('.changer-skin').bind('change',change_skin);
    $(document).on('change','.textinput',handle_change);
    $(document).on('mouseover','.preview-con',handle_mouse);
    $('.btn-master-generate').bind('click',click_master_generate);


    $('textarea.with-tinymce').tinymce(tinymce_settings);


        setTimeout(function(){
            $('*[name=arg1_perc]').trigger('change');
        },100);

    refresh_all();



    setTimeout(function() {
        if ($('*[name=content]').length) {

            if (tinyMCE) {

                var editor = tinyMCE.get('content');
                if (editor) {

                    editor.on('change', function (e) {
                        $('*[name=arg1_perc]').trigger('change');
                    });
                }
            }
        }
    },1000);

    function handle_mouse(e) {
        var _t = $(this);


        if (e.type == 'mouseover') {

            //$('*[name=arg1_perc]').trigger('change');

            var _c = _t.find('iframe').contents();

            //console.info(_c, _c.find('.dzs-progress-bar'));

            if(_c.find('.dzs-progress-bar').get(0) && _c.find('.dzs-progress-bar').get(0).api_restart_and_reinit){

                _c.find('.dzs-progress-bar').get(0).api_restart_and_reinit();
            }
        }
    }


    function handle_change(e){
        var _t = $(this);


        if(e.type=='change'){

            prepare_fout();



            $('.preview-inner').children().remove();
            $('.preview-inner').append('<iframe src="'+window.dzsprg_builder_settings.site_url+'/index.php?dzsprg_preview=on&shortcode='+encodeURIComponent(fout)+'" width="'+window.innerWidth+'" height="'+window.innerHeight+'"></iframe>')
        }
    }


      
    function prepare_fout(){
        //console.log($('img'));
        var cnt = '';
        fout = '';
        var _c = null;
        var _cmain = $('#generator-dzsprg');



        var aux='';

        fout+='[dzsprogressbar';
        _c = $('*[name=skin]');
        if(_c.val()!=''){
            fout+=' skin="'+_c.val()+'"';
        }

        aux='arg1_perc'
        _c = $('*[name='+aux+']');
        if(_c.val()!=''){
            fout+=' '+aux+'="'+_c.val()+'"';
        }
        aux='arg2_maxnr'
        _c = $('*[name='+aux+']');
        if(_c.val()!=''){
            fout+=' '+aux+'="'+_c.val()+'"';
        }




        for(var i3 in window.dzsprg_custom_args){

            aux = window.dzsprg_custom_args[i3];
            _c = $('*[name='+aux+']');

            if(_c.hasClass('wp-editor-area')){
                fout+=tinyMCE.get(aux).getContent({format : 'raw'});
            }else{

                if(_c.val()){
                    fout+=' '+aux+'="'+_c.val()+'"';
                }
            }
        }



        fout+=']';


        aux='content';




        _c = $('*[name='+aux+']');
        if(_c.hasClass('wp-editor-area')){
            if(tinyMCE.get(aux)){

                fout+=tinyMCE.get(aux).getContent({format : 'raw'});
            }
        }else{

            if(_c.val()){
                fout+=_c.val();
            }
        }


        aux='content2';




        fout+='[/dzsprogressbar]';



        if(window.console) { console.log(fout); }

        return fout;
    }

    function change_skin(){
        var _t = $(this);

        refresh_all();
    }

    function refresh_all(){


    }

    function click_master_generate(){
        prepare_fout();
        tinymce_add_content(fout);
        return false;
    }

    function tinymce_add_content(arg){

        if(typeof(top.dzsprg_receiver)=='function'){
            top.dzsprg_receiver(arg);
        }else{
            jQuery('.output-div').text(arg).html();
            jQuery('.output-div').wrapInner('<div class="output-shortcode"></div>');
            jQuery('.output-div').prepend('<h3>Output</h3>')
        }
    }
})

function reskin_select(){
    for(i=0;i<jQuery('select').length;i++){
        var $cache = jQuery('select').eq(i);
        //console.log($cache.parent().attr('class'));
		
        if($cache.hasClass('styleme')==false || $cache.parent().hasClass('select_wrapper') || $cache.parent().hasClass('select-wrapper')){
            continue;
        }
        var sel = ($cache.find(':selected'));
        $cache.wrap('<div class="select-wrapper"></div>')
        $cache.parent().prepend('<span>' + sel.text() + '</span>')
    }
    jQuery('.select-wrapper select').unbind();
    jQuery(document).on('change','.select-wrapper select',change_select);	
        
    function change_select(){
        var selval = (jQuery(this).find(':selected').text());
        jQuery(this).parent().children('span').text(selval);
    }
}
