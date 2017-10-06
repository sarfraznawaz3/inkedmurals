console.log('ceva alceva');

window.htmleditor_sel = 'notset';
window.mceeditor_sel = 'notset';
window.dzsprg_widget_shortcode = null;

jQuery(document).ready(function($){
    if(typeof(dzsprg_builder_settings)=='undefined'){
        if(window.console){ console.log('dzsprg_builder_settings not defined'); };
        return;
    }






    console.warn('wp-content-media-buttons - ', $('#wp-content-media-buttons'));

    $('#wp-content-media-buttons').append('<button type="button" id="dzsprg-shortcode-generator" class="dzs-shortcode-button button " data-editor="content"><span class="the-icon"><svg height="20.412px" style="enable-background:new 0 0 30 20.412;" version="1.1" viewBox="0 0 30 20.412" width="30px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="table_setting"><g><path d="M4.5,1.165c-0.276,0-0.5,0.224-0.5,0.5v3.651c0,0.776-0.687,1.432-1.5,1.432S1,6.093,1,5.316V1.665    c0-0.276-0.224-0.5-0.5-0.5S0,1.389,0,1.665v3.651c0,1.341,1.122,2.432,2.5,2.432S5,6.658,5,5.316V1.665    C5,1.389,4.776,1.165,4.5,1.165z" style="fill:#575756;"/><path d="M2.5,6.165c0.276,0,0.5-0.224,0.5-0.5v-4c0-0.276-0.224-0.5-0.5-0.5S2,1.389,2,1.665v4    C2,5.941,2.224,6.165,2.5,6.165z" style="fill:#575756;"/><path d="M3.5,8.165c-0.276,0-0.5,0.224-0.5,0.5v10.096c0,0.261-0.224,0.473-0.5,0.473    c-0.28,0-0.5-0.208-0.5-0.473V8.665c0-0.276-0.224-0.5-0.5-0.5S1,8.389,1,8.665v10.096c0,0.813,0.673,1.473,1.5,1.473    S4,19.573,4,18.761V8.665C4,8.389,3.776,8.165,3.5,8.165z" style="fill:#575756;"/><path d="M28.491,0.84c-0.769,0-1.343,0.553-1.503,1.461l-1.046,7.657c-0.029,0.214,0.082,0.423,0.276,0.518    L27,10.854v8.029c0,0.857,0.659,1.528,1.5,1.528s1.5-0.671,1.5-1.528V2.369C30,1.511,29.337,0.84,28.491,0.84z M29,18.884    c0,0.312-0.206,0.528-0.5,0.528S28,19.195,28,18.884v-8.342c0-0.191-0.109-0.366-0.281-0.45l-0.736-0.357l0.993-7.279    c0.05-0.281,0.18-0.616,0.516-0.616C28.786,1.84,29,2.062,29,2.369V18.884z" style="fill:#575756;"/><path d="M15.5,3.404c-3.518,0-6.379,2.808-6.379,6.26c0,3.452,2.861,6.261,6.379,6.261    c3.516,0,6.376-2.809,6.376-6.261C21.876,6.212,19.016,3.404,15.5,3.404z M15.5,14.925c-2.966,0-5.379-2.36-5.379-5.261    s2.413-5.26,5.379-5.26c2.964,0,5.376,2.359,5.376,5.26S18.464,14.925,15.5,14.925z" style="fill:#575756;"/><path d="M15.5,0c-5.435,0-9.856,4.335-9.856,9.664c0,5.33,4.421,9.666,9.856,9.666    c5.435,0,9.857-4.336,9.857-9.666C25.356,4.335,20.935,0,15.5,0z M15.5,18.33c-4.883,0-8.856-3.888-8.856-8.666    C6.644,4.887,10.616,1,15.5,1s8.857,3.887,8.857,8.664C24.356,14.442,20.383,18.33,15.5,18.33z" style="fill:#575756;"/></g></g><g id="Warstwa_1"/></svg></span> <span class="the-label"> '+dzsprg_builder_settings.translate_add_shortcode+'</span></button>');





    $('#dzsprg-shortcode-generator').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsprg_builder_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }

        window.open_ultibox(null,{

            type: 'iframe'
            ,source: dzsprg_builder_settings.shortcode_generator_url + parsel
            ,scaling: 'fill' // -- this is the under description
            ,suggested_width: 1200 // -- this is the under description
            ,suggested_height: 800 // -- this is the under description
            ,item: null // -- we can pass the items from here too

        })



        return false;
    })



})