<?php
require_once(dirname(__FILE__).'/dzs_functions.php');

class DZSProgressBars {

    public $theurl = '';
    public $base_url = '';
    public $pluginmode = "plugin";
    public $adminpagename = 'dzsprg_builder';
    public $adminpagename_mainoptions = 'dzsprg_builder_mainoptions';
    public $currSkin = 'custom';
    public $currSkin_nr = 0;
    private $noticed = false;
    private $notices = array();
    private $dbname_skins = 'dzsprg_skins';
    private $dbname_mainoptions = 'dzsprg_mo';
    public $db_skins = array();
    private $db_skin_data = array();
    private $db_mainoptions = array();
    public $capability_admin = 'manage_options';
    private $builder_frontend_errors = array();
    public $db_skins_default = '';

    function __construct() {

        if ($this->pluginmode == 'theme') {
            $this->theurl = THEME_URL.'plugins/dzs-progressbars/';
        } else {
            $this->theurl = plugins_url('',__FILE__).'/';
        }

        $this->base_url = $this->theurl;

        if (isset($_GET['skin']) == false || $_GET['skin'] == '') {
            $this->currSkin = 'custom';
        } else {
            $this->currSkin = $_GET['skin'];
        }


        $this->load_main_data();

        
        

//        print_r($_GET);
        if (isset($_GET['generatorpage']) && $_GET['generatorpage'] == 'dzsprg_generatepage') {
            $this->show_generator();
        }

        if (isset($_POST['dzsprg_duplicate']) && $_POST['dzsprg_duplicate'] == 'on') {
            $this->post_duplicate_skin();
        }
        if (isset($_POST['action']) && $_POST['action'] == 'dzsprg_saveskin') {
            $this->ajax_dzsprg_saveskin();
        }



        // --- check posts
        if(isset($_GET['dzsprg_shortcode_builder']) && $_GET['dzsprg_shortcode_builder']=='on'){
//            dzsprgrx_shortcode_builder();

            include_once(dirname(__FILE__).'/tinymce/shortcode_builder.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }

        add_action('wp_ajax_dzsprg_ajax_save_mo',array($this,'ajax_save_mo'));
        add_action('wp_ajax_dzsprg_ajax_delete_skin',array($this,'ajax_delete_skin'));



        add_action('admin_menu',array($this,'handle_admin_menu'));
        add_action('wp_head',array($this,'handle_wp_head'));
        add_action('wp_footer',array($this,'handle_wp_footer'));
        add_action('admin_head',array($this,'handle_admin_head'));
//        add_action('admin_footer',array($this,'handle_admin_footer'),30000321);
        add_action('admin_notices',array($this,'handle_admin_notices'));


        add_shortcode('dzsprogressbar',array($this,'shortcode_dzsprogressbar'));
        add_action('init',array($this,'handle_init'));

        
    }

    function load_main_data(){





        $defaultOpts = array(
            'extra_css' => '',
            'always_embed' => 'off',
            'dashboard_content' => '',
        );
        $this->db_mainoptions = get_option($this->dbname_mainoptions);

        // -- default opts / inject into db
        if ($this->db_mainoptions == '') {
            $this->db_mainoptions = $defaultOpts;
            update_option($this->dbname_mainoptions,$this->db_mainoptions);
        }

        $this->db_mainoptions = array_merge($defaultOpts,$this->db_mainoptions);




        $db_skins_aux = get_option($this->dbname_skins);

//        echo $this->dbname_skins;
//        print_r($db_skins_aux);

        if ($db_skins_aux == '') {

            $this->db_skins_default =file_get_contents(dirname(__FILE__).'/sampledata/sampledata.txt');
            if($this->db_skins_default){
                $this->db_skins = unserialize($this->db_skins_default);

//                print_r($this->db_skins);

                foreach($this->db_skins as $lab=>$val){


                    $arr = $val ;

                    update_option($this->dbname_skins.'_'.$lab,$arr);

                    unset($this->db_skins[$lab]['bars']);

                }
                update_option($this->dbname_skins,$this->db_skins);
            }

        } else {
            $this->db_skins = ($db_skins_aux);

//            print_r($db_skins_aux);
        }


        $this->db_skin_data = get_option($this->dbname_skins.'_'.$this->currSkin);

        if(is_array($this->db_skin_data)==false){
            $this->db_skin_data = array('bars'=>array());
        }



        if($this->db_mainoptions['dashboard_content']){
            add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
        }
    }

    function post_duplicate_skin(){


        $sw = false;
        $lab_dup = $this->currSkin . '_copy';

        $ind = 0;
        while($sw){
            $sw = false;
            if($ind){
                $lab_dup = $this->currSkin . '_copy'.$ind;
            }
            foreach ($this->db_skins as $lab => $val) {
                if ($lab === $lab_dup) {

                    $ind++;

                    $sw = true;
                    $ind++;
                    break;
                }else{

                }


            }
        }



        $source_skin = $this->currSkin;
        $this->currSkin = $lab_dup;




        $aux = $this->db_skins[$source_skin];

        $this->db_skins[$lab_dup] = array();


        update_option($this->dbname_skins,$this->db_skins);
        update_option($this->dbname_skins.'_'.$lab_dup,$this->get_skin($source_skin));
        header('Location: '. admin_url( 'admin.php?page=dzsprg_builder&skin='.$lab_dup));


//        echo 'lab_dup - '.$lab_dup;

//        print_r($_POST);
//        print_r($this->db_skins);
    }



    function ajax_save_mo() {



        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);
        update_option($this->dbname_mainoptions,$auxarray);
        echo 'success - '.__('saved');
        die();
    }

    function ajax_delete_skin() {



        $auxarray = array();
        //parsing post data


        if ( ! wp_verify_nonce( $_POST['nonce'], 'dzsprg_lab_'.$_POST['postdata'] ) ) {
            // This nonce is not valid.
            die( 'error - Security check' );
        } else {
            // The nonce was valid.
            // Do stuff here.
        }

//        print_r($this->db_skins);

        unset($this->db_skins[$_POST['postdata']]);

        $aux = update_option($this->dbname_skins,$this->db_skins);

        update_option($this->dbname_skins.'_'.$_POST['postdata'],'');


        echo 'success - '.__('saved');
        die();
    }


    public function output_progress_bars($arr, $pargs=array()){

        $fout = '';

        $margs = array(
            'arg1_perc' => '80',
            'arg2_maxnr' => '80',
            'skin' => 'custom',
            'thousand_separate_character' => '',
        );

        if ($pargs == '') {
            $pargs = array();
        }


//        print_r($arr);
        $margs = array_merge($margs,$pargs);

        $fout.='<div class="dzs-progress-bar auto-init" style="';

        $arr_labels = array('width','height','margin_top','margin_left','margin_bottom','margin_right');

        foreach ($arr_labels as $lab2) {
//                    print_r($arr); print_r($arr['bars']['mainsettings']); print_r($arr['bars']['mainsettings'][$lab2]);
            if (isset($arr['bars']['mainsettings']) && isset($arr['bars']['mainsettings'][$lab2])) {
//                        $fout.=''.$lab2.':'.$arr['bars']['mainsettings'][$lab2].';';
                $fout.=''.str_replace('_','-',$lab2).':'.$arr['bars']['mainsettings'][$lab2].';';
            }
        }

        $fout.='"';

        if($margs['thousand_separate_character']){

            $fout.=' data-options=\'{thousand_separate_character: "'.$margs['thousand_separate_character'].'"}\'';
        }


        $firstset = false;

        $fout.=' data-hmm data-animprops=\'{';

        $arr_labels = array('animation_time','maxperc','maxnr','initon');

//                print_r($arr);
        foreach ($arr_labels as $lab2) {

            if ($firstset) {
                $fout.=',';
            }
            if (isset($arr['bars']['mainsettings']) && isset($arr['bars']['mainsettings'][$lab2])) {
                $fout.='"'.$lab2.'":"'.$arr['bars']['mainsettings'][$lab2].'"';
                $firstset = true;
            }
        }
        $fout.='}\'';



        $fout.='>';


        if(isset($arr['bars']) && is_array($arr['bars'])) {
            foreach ($arr['bars'] as $lab3 => $bar) {
                if ($lab3 === 'mainsettings') {
                    continue;
                }

//                    print_r($bar);

                if ($bar['type'] == 'circ') {
                    $fout .= '<canvas';
                } else {
                    $fout .= '<div';
                }

                $fout .= ' class="progress-bar-item progress-bar-item--' . $bar['type'] . ' ' . $bar['extra_classes'] . '" data-type="' . $bar['type'] . '"';

                $fout .= ' style="';
                $all_labs = array('position_type', 'background_color', 'width', 'height', 'top', 'left', 'bottom', 'right', 'margin_top', 'margin_left', 'margin_bottom', 'margin_right', 'border_radius', 'border', 'opacity', 'font_size', 'color', 'extra_classes');

                foreach ($all_labs as $lab4) {
                    $auxlab = $lab4;
                    $val = $bar[$lab4];


                    if ($auxlab == 'position_type') {
                        $auxlab = 'position';
                    }

                    if (($auxlab == 'margin_top' || $auxlab == 'margin_right' || $auxlab == 'margin_bottom' || $auxlab == 'margin_left' || $auxlab == 'border_radius' || $auxlab == 'font_size' || $auxlab == 'background_color')) {

                        $auxlab = str_replace('_', '-', $auxlab);
                    }

                    if (($auxlab == 'top' || $auxlab == 'right' || $auxlab == 'bottom' || $auxlab == 'left' || $auxlab == 'width' || $auxlab == 'height' || $auxlab == 'margin-top' || $auxlab == 'font-size') && strpos($val, '%') === false && strpos($val, 'px') === false && $val !== 'auto') {
                        $val .= 'px';
                    }


                    // -- we eliminate {{arg here
                    if (strpos($val, '{{') === false && $val != '') {

                        $fout .= $auxlab . ':' . $val . ';';
                    } else {


                        preg_match("/{{arg(.{0,1})-default(.*?)}}/", $val, $output_array);

                        if (count($output_array)) {
                            $val = $output_array[2];
                        }

                        $fout .= $auxlab . ':' . $val . ';';

                    }
                }


                $fout .= '"';


                $firstset = false;
                $all_labs = array('width', 'height', 'top', 'left', 'bottom', 'right', 'margin_top', 'margin_left', 'margin_bottom', 'margin_right', 'border_radius', 'border', 'opacity', 'font_size', 'color', 'extra_classes');
                $circle_labs = array('circle_outside_fill', 'circle_inside_fill', 'circle_outer_width', 'circle_line_width');

                if ($bar['type'] == 'circ') {
                    $all_labs = array_merge($all_labs, $circle_labs);
                }

//                    print_r($all_labs);


                $fout .= ' data-animprops=\'{';
                foreach ($all_labs as $lab4) {
                    $auxlab = $lab4;
                    $val = $bar[$lab4];


                    if (($auxlab == 'margin_top' || $auxlab == 'margin_right' || $auxlab == 'margin_bottom' || $auxlab == 'margin_left' || $auxlab == 'border_radius' || $auxlab == 'font_size')) {

                        $auxlab = str_replace('_', '-', $auxlab);
                    }


                    if ((strpos($val, '{{') !== false || in_array($auxlab, $circle_labs)) && $val != '') {
                        if ($firstset) {
                            $fout .= ',';
                        }
                        $fout .= '"' . $auxlab . '":"' . $val . '"';
                        $firstset = true;
                    }
                }
                $fout .= '}\'';

                $fout .= '>';

                if ($bar['type'] == 'text') {
                    $fout .= $bar['text'];
                }

                if ($bar['type'] == 'circ') {
                    $fout .= '</canvas>';
                } else {
                    $fout .= '</div>';
                }
            }
        }else{
            error_log("seems that arr bars does not exist - ".print_rr($arr, array('echo'=>false)));
        }
        $fout.='</div>';

        return $fout;

    }


    function shortcode_dzsprogressbar($pargs,$content = null) {
        // [dzsprogressbar skin="prev2" arg1_perc="80" arg2_maxnr="80" thousand_separate_character="."]content[/dzsprogressbar]


        $fout = '';
        $margs = array(
            'arg1_perc' => '80',
            'arg2_maxnr' => '80',
            'skin' => 'custom',
            'thousand_separate_character' => '',
        );

        if ($pargs == '') {
            $pargs = array();
        }

        $margs = array_merge($margs,$pargs);
        
//        $margs['arg1_perc'] = do_shortcode($margs['arg1_perc']);
//        $margs['arg2_maxnr'] = do_shortcode($margs['arg2_maxnr']);
        $margs['arg1_perc'] = ($margs['arg1_perc']);
        $margs['arg2_maxnr'] = ($margs['arg2_maxnr']);


//        $content = wpb_js_remove_wpautop($content,true);

        $this->enqueue_main_scripts();
        //


        foreach ($this->db_skins as $lab => $skin) {
            if ($lab == $margs['skin']) {


                $skin_data = $this->get_skin($lab);


//                print_r($skin_data);

                $skin_data_json = json_encode($skin_data);
                for($ind=4;$ind<10;$ind++){


                    if(isset($margs['arg'.$ind])){

//                        echo 'arg - '.$margs['arg'.$ind];

//                        echo 'skin_data_json - '.$skin_data_json."<br><br>";

                        $pat2 = '/{{arg'.$ind.'-.*?}}/i';
                        $skin_data_json = preg_replace($pat2,$margs['arg'.$ind],$skin_data_json);


//                        echo 'skin_data_json - '.$skin_data_json."\n\n";

                    }
                }
                $skin_data = json_decode($skin_data_json, true);

                $fout.=$this->output_progress_bars($skin_data, $margs);

                break;
//                print_r($skin);
            }
        }
        $pat1 = '/{{arg1-.*?}}/i';
        $fout = preg_replace($pat1,$margs['arg1_perc'],$fout);


        $pat2 = '/{{arg2-.*?}}/i';
        $fout = preg_replace($pat2,$margs['arg2_maxnr'],$fout);


        $pat3 = '/{{arg3-.*?}}/i';
        if ($content) {
            $fout = preg_replace($pat3,$content,$fout);
        }




        return $fout;
//        return "<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
    }
    
    function wp_dashboard_setup(){
        
        wp_add_dashboard_widget(
                'dzsprg_dashboard_widget_comments', // Widget slug.
                'Zoom Progress Bars', // Title.
                array($this, 'dashboard_comments_display') // Display function.
        );
    }

    function get_skin($arg){




        $fits = array();



        if(is_array($arg)){
            $arg = '';
        }

        $db_skin_data_aux = get_option($this->dbname_skins.'_'.$arg);

        if($db_skin_data_aux == ''){
            $db_skin_data_aux = array();
        }else{
        }





        return $db_skin_data_aux;
    }
    
    function dashboard_comments_display() {

        echo do_shortcode($this->db_mainoptions['dashboard_content']);
    }

    function enqueue_main_scripts() {

        wp_enqueue_script('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.js');
        wp_enqueue_style('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.css');
        wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }

    function handle_init() {

//        echo 'init';


        wp_enqueue_script('jquery');
        
        
        if(is_admin()){
            
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions) {
                wp_enqueue_script('dzsprg_admin',$this->theurl."admin/admin_mo.js");
                wp_enqueue_style('iphone.checkbox',$this->theurl.'admin/checkbox/checkbox.css');
            }
            wp_enqueue_style('dzsprg_admin_global',$this->theurl.'admin/admin_global.css');

//            wp_enqueue_script('dzstaa_configreceiver', $this->theurl . 'tinymce/receiver.js');




            if (current_user_can('edit_posts') || current_user_can('edit_pages')) {

                wp_enqueue_script('dzsprg-add-generators', $this->base_url . 'shortcodegenerator/add-generators-to-mce.js');


                wp_enqueue_style('dzsulb', $this->base_url.'libs/ultibox/ultibox.css');
                wp_enqueue_script('dzsulb', $this->base_url.'libs/ultibox/ultibox.js');
            }



            if(isset($_GET['dzsprg_shortcode_builder']) && $_GET['dzsprg_shortcode_builder']=='on') {

                wp_enqueue_style('dzsprg_shortcode_builder', $this->base_url . 'tinymce/popup.css');
                wp_enqueue_script('dzsprg_shortcode_builder', $this->base_url . 'tinymce/popup.js');

                wp_enqueue_style('dzs.farbtastic',$this->theurl.'libs/farbtastic/farbtastic.css');
                wp_enqueue_script('dzs.farbtastic',$this->theurl.'libs/farbtastic/farbtastic.js');

                wp_enqueue_script('tinymce.js',$this->theurl.'libs/jquery.tinymce.min.js');


                wp_enqueue_script('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.js');
                wp_enqueue_style('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.css');
                wp_enqueue_style('dzszfl',$this->theurl.'libs/zfolio/zfolio.css');
                wp_enqueue_script('dzszfl',$this->theurl.'libs/zfolio/zfolio.js');
                wp_enqueue_script('dzszfl.isotope',$this->theurl.'libs/zfolio/jquery.isotope.min.js');
            }
        }





        if(is_admin()){
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions) {
                wp_enqueue_script('dzsprg_admin',$this->theurl."admin/admin_mo.js");
                wp_enqueue_style('iphone.checkbox',$this->theurl.'admin/checkbox/checkbox.css');
            }
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
//                echo 'harambe';

//                wp_enqueue_script('wp-tinymce');
//                wp_enqueue_script('tinymce');
//                wp_enqueue_script('editor');

//                wp_enqueue_script( 'common' );
//                wp_enqueue_script( 'jquery-color' );
//                wp_print_scripts('editor');
//                if (function_exists('add_thickbox')) add_thickbox();
//                wp_print_scripts('media-upload');
//                if (function_exists('wp_tiny_mce')) wp_tiny_mce();
//                wp_admin_css();
//                wp_enqueue_script('utils');
//                do_action("admin_print_styles-post-php");
//                do_action('admin_print_styles');


                
                if (function_exists('wp_tiny_mce')) {
//                    echo 'harambe';

//                    add_filter('teeny_mce_before_init', create_function('$a', '
//    $a["theme"] = "advanced";
//    $a["skin"] = "wp_theme";
//    $a["height"] = "200";
//    $a["width"] = "800";
//    $a["onpageload"] = "";
//    $a["mode"] = "exact";
//    $a["elements"] = "intro";
//    $a["editor_selector"] = "mceEditor";
//    $a["plugins"] = "safari,inlinepopups,spellchecker";
//
//    $a["forced_root_block"] = false;
//    $a["force_br_newlines"] = true;
//    $a["force_p_newlines"] = false;
//    $a["convert_newlines_to_brs"] = true;
//
//    return $a;'));
//
//                    wp_enqueue_script('wp-tinymce');
//                    wp_enqueue_script('tinymce');
//                    wp_enqueue_script('editor');
//                    wp_tiny_mce(true);
                }

            }

        }else{

        }




        $arr_skins = array();
        foreach ($this->db_skins as $lab => $skin) {
            $arr_skins[$lab] = $lab;
        }

//        print_r($arr_skins);
        if(function_exists('vc_map')){
            vc_map(array(
                "name" => __("Zoom Progress Bar"),
                "base" => "dzsprogressbar",
                "class" => "",
                "front_enqueue_js" => $this->theurl.'js/frontend_backbone.js',
                "category" => __('Content'),
                "params" => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Skin'),
                        'param_name' => 'skin',
                        'value' => $arr_skins,
                        'description' => __('Select a skin from the one\'s you built in the generator.')
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Percent"),
                        "param_name" => "arg1_perc",
                        "value" => __("100"),
                        "description" => __("The percent on which the progress bar goes. A value from 0 to 100.")
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Percent Number"),
                        "param_name" => "arg2_maxnr",
                        "value" => __("400"),
                        "description" => __("A number that can be increased based on percent. Can be any number.")
                    ),
                    array(
                        "type" => "textarea_html",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Text"),
                        "param_name" => "content",
                        "value" => __("<p>I am test text block. Click edit button to change this text.</p>"),
                        "description" => __("Enter your text for skins that receive a text parameter.")
                    )
                )
            ));
        }
    }

    function ajax_dzsprg_saveskin() {



        parse_str($_POST['postdata'],$auxarray);

        $this->currSkin = $_POST['currSkin'];

//        print_r($auxarray);


//        print_r($this->db_skins);

        $sw_skin_exists = false;
        foreach ($this->db_skins as $lab=>$val){
            if($lab===$this->currSkin){
                $sw_skin_exists = true;
            }
        }

        if(!$sw_skin_exists){
            $this->db_skins[$this->currSkin] = array();
            $aux = update_option($this->dbname_skins,$this->db_skins);

        }



//        $this->db_skins[$this->currSkin] = $auxarray;

        $aux = update_option($this->dbname_skins.'_'.$this->currSkin,$auxarray);

//        echo $this->dbname_skins;
//        print_r($this->db_skins);

        if($aux){

            echo 'success - skin ( '.$this->currSkin.' ) saved';
        }else{

            echo __('error - skin  ( '.$this->currSkin.' )  not saved');
//            print_r($aux);
        }

        print_r($auxarray);

        die();
    }

    function handle_admin_menu() {

        global $current_user;


        $admin_cap = $this->capability_admin;


        $dzsvg_page = add_menu_page(__('DZS Progress Bars','dzsvg'),__('Progress Bars','dzsvg'),$admin_cap,$this->adminpagename,array($this,'admin_page_builder'),'div');
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Builder','dzsvg'),__('Progress Bars Builder','dzsvg'),$this->capability_admin,$this->adminpagename,array($this,'admin_page_builder'));
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Settings','dzsvg'),__('Progress Bars Settings','dzsvg'),$this->capability_admin,$this->adminpagename_mainoptions,array($this,'admin_page_mainoptions'));
    }

    function handle_wp_head() {

        if(isset($_GET['dzsprg_preview']) && $_GET['dzsprg_preview']=='on') {

//            echo '$_GET[\'shortcode\'] - '.$_GET['shortcode']."\n\n";
            ?>
            <style>
                body {
                    opacity: 0;
                }

                body.ready {
                    opacity: 1;
                }

                body > * {
                    display: none;
                }

                body > .dzsp-preview-con {
                    display: block;
                    padding: 40px;
                }
            </style><?php
        }
    }
    function handle_wp_footer() {

        if(isset($_GET['dzsprg_preview']) && $_GET['dzsprg_preview']=='on'){

//            echo '$_GET[\'shortcode\'] - '.$_GET['shortcode']."\n\n";
            ?>

            <?php

            if(isset($_GET['params'])){

                $params = json_decode($_GET['params']);
                ?>

                <div class="dzsp-preview-con"><?php echo $this->show_shortcode(array()); ?></div>
                <?php
            }

            if(isset($_GET['shortcode'])){

//                error_log(stripslashes($_GET['shortcode']));
                ?>

                <div class="dzsp-preview-con"><?php echo do_shortcode(stripslashes($_GET['shortcode'])) ?></div>
                <?php
            }
            ?>

            <script>jQuery(document).ready(function($){
                    $('body').addClass('dzsprg-preview ready');

                })</script>
            <?php
        }

    }

    function handle_admin_head() {

        wp_enqueue_style('dzsprg.admin.global',$this->theurl.'style/admin-global.css');


        $struct_item_default = str_replace(array("\r","\r\n","\n"),'',$this->generate_layer_item());
        $struct_item_text = str_replace(array("\r","\r\n","\n"),'',$this->generate_layer_item(array('type' => 'text','background_color' => 'transparent','text' => 'insert text here','height' => 'auto')));
        $struct_item_circ = str_replace(array("\r","\r\n","\n"),'',$this->generate_layer_item(array('type' => 'circ','background_color' => 'transparent','height' => '{{width}}')));


        echo '<script>window.dzsprg_builder_settings = { struct_layer: \''.$struct_item_default.'\''
                . ', struct_layer_text: \''.$struct_item_text.'\''
                . ', struct_layer_circ:\''.$struct_item_circ.'\''
                . ',currSkin : "'.$this->currSkin.'"'
                . ',theurl:"'.$this->theurl.'"'
                . ',base_url:"'.$this->base_url.'"'
                . ',site_url:"'.site_url().'"'
                . ',translate_add_shortcode:"'.__("Progress Bars").'"'
                . ',adminpageurl:"'.admin_url('admin.php?page='.$this->adminpagename).'"'
                . ',wpurl : "'.site_url().'" '
                . ',adminurl : "'.admin_url().'" 
        ,shortcode_generator_url: "'.admin_url('admin.php?page='.$this->adminpagename_mainoptions) . '&dzsprg_shortcode_builder=on"
           };';

//        echo ' var j = jQuery; ';

        echo '</script>';
        if(isset($_GET['dzsprg_shortcode_builder'])==false) {
            wp_enqueue_script('dzsprg_configreceiver', $this->base_url . 'tinymce/receiver.js');
        }
    }

    function handle_admin_footer() {

        ?>
        <script>
            setTimeout(function(){

                tinyMCEPreInit.baseURL = window.dzsprg_builder_settings.theurl + 'tinymce/tinymce/tinymce.min.js';
            },5);
        </script>

        <?php
    }

    function admin_page_mainoptions() {
        //print_r($this->mainoptions);
        if (isset($_POST['dzsprg_delete_plugin_data']) && $_POST['dzsprg_delete_plugin_data'] == 'on') {
            delete_option($this->dbname_skins);
            delete_option($this->dbname_options);
        }
        //print_r($this->mainoptions);

        if (isset($_GET['dzsprg_shortcode_builder']) && $_GET['dzsprg_shortcode_builder'] == 'on') {
//            print_r($this->arr_for_select_post_type_opts);
            dzsprg_shortcode_builder();
        } else {


            ?>

            <div class="wrap">
                <h2><?php echo __('Progress Bars Main Settings', 'dzsprg'); ?></h2>
                <br/>
                <form class="mainsettings">


                    <p>
                        <button data-src="<?php echo $this->base_url ?>readme/index.html" data-type="iframe"
                                class="zoombox button-secondary" style="" data-bigwidth="1024" data-bigheight="768"
                                data-scaling="fill"><?php echo __("Documentation"); ?></button>
                        <a href="<?php $aux = add_query_arg('dzsprg_shortcode_builder', 'on', dzs_curr_url());
                        $aux = add_query_arg('from_mainpage', 'on', $aux);
                        echo $aux; ?>" class=" button-secondary"
                           style=""><?php echo __("Shortcode Generator", 'dzsprg'); ?></a></p>
                    <h3><?php echo __("Admin Options"); ?></h3>


                    <div class="setting">
                        <div class="label"><?php echo __('Always Embed Scripts?', 'dzsprg'); ?></div>
                        <div class="dzscheckbox skin-nova">
                            <?php echo DZSHelpers::generate_input_checkbox('always_embed', array('class' => '', 'id' => 'always_embed', 'val' => 'on', 'seekval' => $this->db_mainoptions['always_embed'])); ?>
                            <label for='always_embed'></label>
                        </div>
                        <div class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )', 'dzsprg'); ?></div>
                    </div>
                    <div class="setting">
                        <div class="label"><?php echo __('Extra CSS', 'dzsprg'); ?></div>
                        <?php echo DZSHelpers::generate_input_textarea('extra_css', array('val' => '', 'seekval' => $this->db_mainoptions['extra_css'])); ?>
                    </div>
                    <div class="setting">
                        <div class="label"><?php echo __('Dashboard Content', 'dzsprg'); ?></div>
                        <?php $lab = 'dashboard_content';
                        echo DZSHelpers::generate_input_textarea($lab, array('val' => '', 'seekval' => $this->db_mainoptions[$lab])); ?>
                        <div class="sidenote"><?php echo __('you can have a dashboard widget with this content', 'dzsprg'); ?></div>
                    </div>


                    <br/>
                    <a href='#'
                       class="button-primary save-btn save-mainoptions"><?php echo __('Save Options', 'dzsprg'); ?></a>
                </form>
                <br/><br/>
                <form class="mainsettings" method="POST">
                    <button name="dzsprg_delete_plugin_data" value="on"
                            class="button-secondary"><?php echo __('Delete Plugin Data', 'dzsprg'); ?></button>
                </form>
                <div class="saveconfirmer" style=""><img alt="" style="" id="save-ajax-loading2"
                                                         src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/>
                </div>
                <script>
                    jQuery(document).ready(function ($) {
                        page_mainoptions_ready();
                    })
                </script>
            </div>
            <div class="clear"></div><br/>
            <?php
        }
    }

    function admin_page_builder() {
//        wp_enqueue_script('tinymce.js',$this->theurl.'tinymce/tinymce/tinymce.min.js');
//        wp_enqueue_script('tinymce.jquery',$this->theurl.'tinymce/tinymce/jquery.tinymce.min.js');



        wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_script('farbtastic.colorpicker',$this->theurl.'colorpicker/farbtastic.js');
        wp_enqueue_style('farbtastic.colorpicker',$this->theurl.'colorpicker/farbtastic.css');



//        wp_enqueue_style('dzsprg.builder',$this->theurl.'style/builder.css');
//        wp_enqueue_script('dzsprg.builder',$this->theurl.'js/builder.js');


        wp_enqueue_script('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.js');
        wp_enqueue_style('dzs.progressbars',$this->theurl.'dzsprogressbars/dzsprogressbars.css');
        wp_enqueue_style('dzszfl',$this->theurl.'libs/zfolio/zfolio.css');
        wp_enqueue_script('dzszfl',$this->theurl.'libs/zfolio/zfolio.js');
        wp_enqueue_script('dzszfl.isotope',$this->theurl.'libs/zfolio/jquery.isotope.min.js');




        wp_enqueue_style('dzsiconselector',$this->theurl.'libs/dzsiconselector/dzsiconselector.css');
        wp_enqueue_script('dzsiconselector',$this->theurl.'libs/dzsiconselector/dzsiconselector.js');
        wp_enqueue_style('dzsprg.builder',$this->theurl.'libs/builder/builder.css');
        wp_enqueue_script('dzsprg.builder',$this->theurl.'libs/builder/builder.js');
        wp_enqueue_script('tinymce.js',$this->theurl.'libs/jquery.tinymce.min.js');
        wp_enqueue_script('jquery.nestable.js',$this->theurl.'libs/builder/jquery.nestable.js');

        wp_enqueue_script('dzs.scroller',$this->theurl.'dzsscroller/scroller.js');
        wp_enqueue_style('dzs.scroller',$this->theurl.'dzsscroller/scroller.css');
        wp_enqueue_script('dzs.tabsandaccordions',$this->theurl.'dzstabsandaccordions/dzstabsandaccordions.js');
        wp_enqueue_style('dzs.tabsandaccordions',$this->theurl.'dzstabsandaccordions/dzstabsandaccordions.css');
//        wp_enqueue_script('jquery.ui',$this->theurl.'jqueryui/jquery-ui.min.js');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_style('jquery.ui',$this->theurl.'jqueryui/jquery-ui.min.css');

//        print_r($this->db_skins);
        ?>

        <script>

            var includemediasupport = '';
            var tinymce_external_plugins = {};

            if(includemediasupport==',filemanager'){
                tinymce_external_plugins = { "filemanager" : dzsprg_builder_settings.theurl + 'filemanager/plugin.min.js'};
            }

            includemediasupport = '';
            window.override_tinymce_settings = {
                script_url : window.dzsprg_builder_settings.theurl + 'tinymce/tinymce/tinymce.min.js'
                ,mode : "textareas"
                ,theme : "modern"
                ,plugins : "hr,fullscreen"
                ,relative_urls : false
                ,remove_script_host : false
                ,image_advtab: true
                ,convert_urls : true
                ,forced_root_block : ""
                ,extended_valid_elements: 'span[class],a[*],i[*]'
                ,content_css: 'http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
                ,theme_advanced_toolbar_location : "top"
                //,theme_advanced_toolbar_align : "left"
                //,theme_advanced_statusbar_location : "bottom"
                ,toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code"

                ,setup : function(ed) {
                }
            };

        </script>
        <div class="wrap wrap-dzsprg-builder">
            <h2>DZS <?php echo __('Progress Bars Builder'); ?></h2>
            <section class="mcon-maindemo" style="position: relative; padding-top:0px; padding-bottom:50px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <br/>
                            <br/>
        <?php
        foreach ($this->builder_frontend_errors as $err) {
            echo $err;
        }
        ?>
                        </div>
                        <div class="col-md-6">
                            <h3 style="margin-top: 0; padding-top: 0;">Customize <strong>skin-<?php echo $this->currSkin; ?></strong> - <span class='btn-preview'>preview</span></h3>
                                <form method="post"><input type="hidden" name="dzsprg_skin_name" value="<?php echo $this->currSkin; ?>"/><input type="hidden" name="dzsprg_duplicate" value="on"/><input type="submit" class="button-secondary" name="dzsprg_duplicate_lab" value="<?php echo __("Duplicate Skin"); ?>"/></form>
                        </div>
                        <div class="col-md-6">
                            <div class="super-select float-right">
                                <span class="btn-show-select"><span class='arrow-symbol'>↳</span> Current Skin <strong>skin-<?php echo $this->currSkin; ?></strong> </span>
                                <div class="super-select--inner">
                                    <div class='scroller-con super-select--options'>
                                        <div class="inner">
                                            <div class='skin-option button-secondary'><a href="<?php echo admin_url('admin.php?page='.$this->adminpagename); ?>">skin-custom</a></div><?php
                            foreach ($this->db_skins as $lab => $skin) {
                                if ($lab == 'custom') {
                                    continue;
                                }
                                echo '<div class="skin-option button-secondary"><a href="'.admin_url('admin.php?page='.$this->adminpagename).'&skin='.$lab.'">skin-'.$lab.'</a></div>';
                            }
                            ?>
                                            <div class='skin-option button-secondary'>skin-<form id='create-custom-skin' method="POST" action="" style="display: inline-block; ; width: 90px;"><input class="subtile" type="text" name="builder_skin_name" placeholder="skin name" style="width: 100%;"/></form></div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden">
                        <?php wp_editor("ceva", "ceva"); ?>
                    </div>
                    <form name="builder-form" class="builder-form">

                        <div class="row">
                            <div class="dzsprg-builder-con">
                                <div class="col-md-8 main-col-for-canvas">
                                    <div class="dzsprg-builder-con--canvas-area dzs-progress-bar" style=";"></div>
                                    <div class="dzsprg-builder-con--add-area">
                                        <h6><?php echo __("ADD"); ?>:</h6>
                                        <span class="dzs-button builder-add-rect">
                                        <i class="fa fa-stop color-icon" aria-hidden="true"></i><span class="the-label"><?php echo __("Rectangle"); ?></span>
                                        </span>
                                        <span class="dzs-button builder-add-circ">
                                        <i class="fa fa-circle color-icon" aria-hidden="true"></i><span class="the-label"><?php echo __("Circle"); ?></span>
                                        </span>
                                        <span class="dzs-button builder-add-text">
                                        <i class="fa fa-font color-icon" aria-hidden="true"></i><span class="the-label"><?php echo __("Text"); ?></span>
                                        </span>
<!--                                        <span class="dzs-button builder-add-container">-->
<!--                                        <i class="fa fa-inbox color-icon" aria-hidden="true"></i><span class="the-label">--><?php //echo __("Container"); ?><!--</span>-->
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dzsprg-builder-con--layers-area"><!--begin layers area-->
                                        <div class="dd" id="nestable3" style="width: 100%; max-width: none; margin: 0 auto;">
                                            <ol class="dd-list">
        <?php
        if (isset($this->db_skin_data['bars'])) {
            $bars = $this->db_skin_data['bars'];
//                                    print_r($bars);
            foreach ($bars as $lab_bar => $val_bar) {
                if ($lab_bar !== 0 && $lab_bar == 'mainsettings') {
                    continue;
                }
//                                        print_r($val_bar);
                $aux = $val_bar;
                echo $this->generate_layer_item($aux);
            }
        }
        ?>
                                            </ol>
                                        </div>
                                        <!--end layers area--></div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                        <?php
                                        $mainsettings = array(
                                            'position_type' => 'relative',
                                            'index' => '0',
                                            'width' => '100%',
                                            'height' => 'auto',
                                            'animation_time' => '2000',
                                            'maxperc' => '100',
                                            'margin_top' => '0',
                                            'margin_right' => '0',
                                            'margin_bottom' => '0',
                                            'margin_left' => '0',
                                            'color' => '#eeeeee',
                                            'background_color' => '#285e8e',
                                            'type' => 'rect',
                                            'initon' => 'scroll',
                                            'maxnr' => '100',
                                        );

                                        $mainsettings_fromdb = array();

                                        if (isset($this->db_skin_data['bars']['mainsettings'])) {
                                            $mainsettings_fromdb = $this->db_skin_data['bars']['mainsettings'];
                                        }
                                        $mainsettings = array_merge($mainsettings,$mainsettings_fromdb);

//                        $mainsettings = array_merge();
                                        ?>
                                <div id="tabs-mainsettings" class="dzs-tabs skin-box">


                                    <div class="dzs-tab-tobe">
                                        <div class="tab-menu with-tooltip">
                                            <?php echo __("General"); ?>
                                        </div>
                                        <div class="tab-content">
                                            <?php

                                            $name = $this->currSkin;

                                            if(isset($mainsettings['name']) && $mainsettings['name']){
                                                $name = $mainsettings['name'];
                                            }

                                            $category = '';

                                            if(isset($mainsettings['category']) && $mainsettings['category']){
                                                $category = $mainsettings['category'];
                                            }

                                            $max_width = '';

                                            if(isset($mainsettings['max_width']) && $mainsettings['max_width']){
                                                $max_width = $mainsettings['max_width'];
                                            }

                                            ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="setting-label"><?php echo __("Name"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][width]" value="<?php echo $name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="setting-label"><?php echo __("Category"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][category]" value="<?php echo $category; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>

                                        </div>


                                    </div>

                                    <div class="dzs-tab-tobe">
                                        <div class="tab-menu with-tooltip">
                                            <?php echo __("Position"); ?>
                                        </div>
                                        <div class="tab-content">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="setting-label"><?php echo __("Width"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][width]" value="<?php echo $mainsettings['width']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="setting-label"><?php echo __("Max Width"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][max_width]" value="<?php echo $max_width; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="setting-label"><?php echo __("Height"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][height]" value="<?php echo $mainsettings['height']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>


                                            <hr>
                                            <div class="one-half" style="float:none; margin: 0 auto;">
                                                <div class="setting-label"><?php echo __("Margin Top"); ?></div>
                                                <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][margin_top]" value="<?php echo $mainsettings['margin_top']; ?>">
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="one-half">
                                                <div class="setting-label"><?php echo __("Margin Left"); ?></div>
                                                <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][margin_left]" value="<?php echo $mainsettings['margin_left']; ?>">
                                                </div>
                                            </div>
                                            <div class="one-half">
                                                <div class="setting-label"><?php echo __("Margin Right"); ?></div>
                                                <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][margin_right]" value="<?php echo $mainsettings['margin_right']; ?>">
                                                </div>
                                            </div>
                                            <div class="clear"></div>

                                            <div class="one-half" style="float:none; margin: 0 auto;">
                                                <div class="setting-label"><?php echo __("Margin Bottom"); ?></div>
                                                    <div class="setting"><input class="builder-field" type="text" name="bars[mainsettings][margin_bottom]" value="<?php echo $mainsettings['margin_bottom']; ?>">
                                                    </div>
                                                <div class="clear"></div>

                                            </div>


                                        </div>
                                    </div>


                                    <div class="dzs-tab-tobe">
                                        <div class="tab-menu with-tooltip">
                                            <?php echo __("Animation"); ?>
                                        </div>
                                        <div class="tab-content">
                                            <div class="setting">
                                                <div class="setting-label"><?php echo __("Animation Time"); ?></div>
                                                <input class="builder-field" type="text" name="bars[mainsettings][animation_time]" value="<?php echo $mainsettings['animation_time']; ?>">
                                                <div class="sidenote"><?php echo __("Animation Time in ms - 1000 ms = 1 second"); ?></div>
                                            </div>
                                            <div class="setting">
                                                <div class="setting-label"><?php echo __("Percent"); ?></div>
                                                <input class="builder-field" type="text" name="bars[mainsettings][maxperc]" value="<?php echo $mainsettings['maxperc']; ?>">

                                                <div class="jqueryui-slider for-perc"></div>
                                                <div class="sidenote"><?php echo __("Percent on which the animation goes - from 1 to 100"); ?></div>
                                            </div>
                                            <div class="setting">
                                                <div class="setting-label"><?php echo __("Animation Number"); ?></div>
                                                <input class="builder-field" type="text" name="bars[mainsettings][maxnr]" value="<?php echo $mainsettings['maxnr']; ?>">
                                                <div class="sidenote"><?php echo __("You can have a progress number which increments as the progress goes on. You insert it via {{percmaxnr}} in the text block ideally."); ?></div>
                                            </div>
                                            <div class="setting">
                                                <div class="setting-label"><?php echo __("Animation Starts on ..."); ?></div>
                                                <?php
                                                $lab = 'initon';
                                                echo DZSHelpers::generate_select('bars[mainsettings]['.$lab.']',array('options' => array('init','scroll'),'class' => 'styleme builder-field','seekval' => $mainsettings[$lab]));
                                                ?><div class="sidenote"><?php echo __("init - page load, scroll - when the page scrolls to it's location."); ?></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>

                            </div>
                        </div>
                        <div class="row">
                            <br/>
                            <br/>
                            <div class="col-md-6">
                                <button class="button-primary btn-save-skin" style="text-transform: none;"><?php echo __("Save Changes"); ?></button>
                            </div>
                            <div class="col-md-6" style="text-align: right;">
                                <?php echo __("version"); ?> 1.0
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h3><?php echo __("Skins"); ?></h3>

                                <div class="skins-container ">
                                    <div class="skins-first-models active dzs-row">

                                        <?php

                                        //                                print_r($this->db_skins);

                                        $il4 = 0;
                                        foreach ($this->db_skins as $lab=>$skin) {

                                            $skin = $lab;

//                                            echo 'skin - '; print_r($skin);


                                            $arr = $this->get_skin($skin);

//                                            print_r($arr);


                                            if(isset($arr['bars'])){


                                                echo '<a class="dzs-col-md-3" style="display:block; "';

                                                echo ' href="';
                                                echo DZSHelpers::add_query_arg( array(
                                                    'skin' => $skin,
                                                ), dzs_curr_url() );

                                                echo '"';
                                                echo '>';
                                                echo '<h6 class="skin-title">'.$skin.'</h6>';
                                                echo $this->output_progress_bars($arr);
                                                echo '</a>';
                                            }else{
                                                continue;
                                            }

//                                    echo 'i - '.$il4.' -- ';print_r($arr);
                                            $il4++;

                                            if($il4>3){
                                                break;
                                            }

                                        }
                                        ?>
                                        <div class="clear" style="clear: both;"></div>
                                    </div>
                                    <div class="skins-all-models nonactive">
                                        <div class="flex-select-search">

                                            <div class="selector-con selector-con-for-skin-forwall" id="selector-con-for-zfolio" style="text-align: center"><div class="categories"></div></div>


                                            <div class="search-con "><input class="input-field-zfolio" type="search" placeholder="Search..."/></div>
                                        </div>
                                        <br>
                                        <div class="zfolio skin-qcre auto-init delay-effects layout-4-cols-15-margin" data-options='{ design_item_thumb_height:"1",item_extra_class:"",excerpt_con_transition: "wipe"
,outer_con_selector_con: $(".selector-con-for-skin-forwall").eq(0)
,outer_con_search_con: $(".search-con > input").eq(0)
                                        }'>
                                            <div class="items ">
                                                <?php

                                                //                                print_r($this->db_skins);

                                                $il4 = 0;
                                                foreach ($this->db_skins as $lab=>$skin) {


                                                    // -- tbc
//                                                    break;
                                                    $skin = $lab;

                                                    $arr = $this->get_skin($skin);


//                                            print_r($arr);

                                                    $cat = '';

                                                    if(isset($arr['bars']['mainsettings']['category'])){
                                                        $cat = $arr['bars']['mainsettings']['category'];
                                                    }
                                                    if(isset($arr['bars'])){

                                                        ?>
                                                    <a class="zfolio-item "  data-category="<?php echo $cat; ?>"  style="display:block; " data-skin="<?php echo $skin; ?>" href="<?php echo DZSHelpers::add_query_arg( array(
                                                        'skin' => $skin,
                                                    ), dzs_curr_url() ); ?>">
                                                        <div class="zfolio-item--inner">
                                                            <div class="dzsprg-container the-feature-con "><?php

                                                                echo $this->output_progress_bars($arr, array(
                                                                    'auto-init' => 'off',
                                                                ));


                                                                ?>
                                                            </div>

                                                            <div class="item-meta">
                                                                <div class="the-title"><?php echo $skin; ?></div>
                                                            </div>


                                                            <i class="delete-skin-btn fa fa-times-circle" aria-hidden="true" data-lab="" data-nonce="<?php echo wp_create_nonce('dzsprg_lab_'.$skin); ?>"></i>
                                                        </div>

                                                        </a><?php


                                                    }else{
                                                        continue;
                                                    }

//                                    echo 'i - '.$il4.' -- ';print_r($arr);
                                                    $il4++;

                                                    if($il4>30){
                                                        break;
                                                    }

                                                }
                                                ?>
                                            </div>
                                            <div class="zfolio-preloader-circle-con zfolio-preloader-con">
                                                <div class="zfolio-preloader-circle"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="text-align: center;">
                                        <div class=" dzs-button-on-off">
                                            <div class="button-secondary dzs-button-off"><?php echo __("Show All"); ?></div>
                                            <div class="button-secondary dzs-button-on"><?php echo __("Show Less"); ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sidenote"><p><?php echo __('You can have variables that are replaced dinamically while the progress bars animate. 
                                        Might be overwhelming to understand them all but you can search 
                                        the examples for easily understanding. A cheatsheet can be found below:'); ?></p>
                                    <strong>{{perc}}</strong> - outputs the current percentage, for example if the progress is at 47% it will output 47%
                                    <br><strong>{{perc-decimal}}</strong> - outputs the current percentage in decimal form, for example if the progress is at 47% it will output 0.47
                                    <br><strong>{{perc100-decimal}}</strong> - it's the same as perc-decimal but the difference is it will go up until 1 even if the <strong>Percent</strong> is set to lower then 100%
                                    <br><strong>{{center}}</strong> - it will center the element, currently available only for the <strong>Top</strong> property
                                    <br><strong>{{percmaxnr}}</strong> - outputs the current number relative the percent, you set the number in the <strong>Animation Number</strong> field.
                                    For example if progress is at 47% and the Animation Number is 500 - the output will be <strong>235</strong>
                                    <br><br>

                                    <h4><?php echo __("Built In Arguments"); ?></h4>
                                    <strong>{{arg1-default60}}</strong> - this field will be replaced by the value you place in the editor for the percent
                                    <br><strong>{{arg2-default60}}</strong> - this field will be replaced by the value you place in the editor for the max number
                                    <br><strong>{{arg3-defaulttext}}</strong> - this field will be replaced by the value you place in the editor for the text content
                                    <br><br>
                                    <h4><?php echo __("Custom Arguments"); ?></h4>
                                    <strong>{{arg4-#ff0000}}</strong> - <?php echo __("You can also name your own variables. Place anywhere between arg4 and arg10 and have color options or text options that will appear in the shortcode generator - example in skin-prev1, skin-prev2 or skin-prev4 "); ?>



                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
            <div class="saveconfirmer active" style=""><span alt="" style="" id="save-ajax-loading2" ></span></div>
        </div>
        <?php
    }

    function handle_admin_notices() {
        if ($this->noticed) {
            return;
        }
        foreach ($this->notices as $notice) {
            echo $notice;
        }

        $this->noticed = true;
    }

    function generate_layer_item($pargs = array()) {

        $margs = array(
            'position_type' => 'relative',
            'index' => '0',
            'width' => 'default',
            'height' => '40',
            'top' => '0',
            'right' => 'auto',
            'bottom' => 'auto',
            'left' => '0',
            'margin_top' => '0',
            'margin_right' => '0',
            'margin_bottom' => '0',
            'margin_left' => '0',
            'border_radius' => '0',
            'border' => '0',
            'color' => '#eeeeee',
            'background_color' => '#285e8e',
            'type' => 'rect',
            'animation_brake' => '',
            'font_size' => '12',
            'text_align' => 'left',
            'opacity' => '1',
            'text' => '',
            'circle_outside_fill' => '#fb1919',
            'circle_inside_fill' => 'transparent',
            'circle_outer_width' => '{{perc-decimal}}',
            'circle_line_width' => '10',
            'extra_classes' => '',
        );

        $margs = array_merge($margs, $pargs);


        if($margs['width']=='default'){

            if($margs['type']=='circ'){
                $margs['width']='100%';
            }
            if($margs['type']=='rect'){
                $margs['width']='{{perc}}';
            }
        }
        if($margs['width']=='default') {
            $margs['width'] = '100%';
        }

        $struct_item = '';


        $title = '';

        if($margs['type']=='rect'){

            $title = '<i class="fa fa-stop color-icon" aria-hidden="true"></i>&nbsp;&nbsp; '.__("Rectangle");
        }

        if($margs['type']=='circ'){

            $title = '<i class="fa fa-circle color-icon" aria-hidden="true"></i>&nbsp;&nbsp; '.__("Circle");
        }

        if($margs['type']=='text'){

            $title = '<i class="fa fa-font color-icon" aria-hidden="true"></i>&nbsp;&nbsp; '.__("Text");
        }


        $struct_item = '<li class="dd-item dd3-item builder-layer type-'.$margs['type'].'"><div class="builder-layer--head">'
            . '<input type="hidden" name="bars['.$margs['index'].'][type]" value="'.$margs['type'].'"/>'
            . '<span class="the-title" data-type="'.$margs['type'].'">'.$title.'</span>'
            . '</div><span class="sortable-handle-con dd-handle"><span class="sortable-handle"></span></span>';

        // dd-content dd3-content

        $struct_item.='<div class="builder-layer--inside">';

        $struct_item.= '<div class="dzs-tabs skin-box">';

        if($margs['type']=='text'){
            $struct_item.='<div class="setting type-text">
<textarea class="builder-field with-tinymce" name="bars['.$margs['index'].'][text]">'.$margs['text'].'</textarea>';


            $struct_item.='<div class="iconselector" data-input-acts-as-search="on">
    <div class="flex-for-icon-selector">

        <span class="iconselector-preview"></span>
        <input type="text" class="style-iconselector iconselector-waiter"/>
        <span class="iconselector-btn">
            <i class="fa fa-caret-down"></i>
        </span>
        <button class="button-secondary btn-insert-icon">'.__("Insert Icon").'</button>
    </div>

    <div class="iconselector-clip">

        <span class="icon-select" data-theicon="bars fa-lg"><i class="fa fa-bars fa-lg"></i></span><span class="icon-select" data-theicon="flag"><i class="fa fa-flag"></i></span><span class="icon-select" data-theicon="caret-down"><i class="fa fa-caret-down"></i></span><span class="icon-select" data-theicon="flag fa-fw"><i class="fa fa-flag fa-fw"></i></span><span class="icon-select" data-theicon="cube fa-fw"><i class="fa fa-cube fa-fw"></i></span><span class="icon-select" data-theicon="camera-retro fa-fw"><i class="fa fa-camera-retro fa-fw"></i></span><span class="icon-select" data-theicon="file-image-o fa-fw"><i class="fa fa-file-image-o fa-fw"></i></span><span class="icon-select" data-theicon="spinner fa-fw"><i class="fa fa-spinner fa-fw"></i></span><span class="icon-select" data-theicon="check-square fa-fw"><i class="fa fa-check-square fa-fw"></i></span><span class="icon-select" data-theicon="credit-card fa-fw"><i class="fa fa-credit-card fa-fw"></i></span><span class="icon-select" data-theicon="pie-chart fa-fw"><i class="fa fa-pie-chart fa-fw"></i></span><span class="icon-select" data-theicon="won fa-fw"><i class="fa fa-won fa-fw"></i></span><span class="icon-select" data-theicon="file-text-o fa-fw"><i class="fa fa-file-text-o fa-fw"></i></span><span class="icon-select" data-theicon="hand-o-right fa-fw"><i class="fa fa-hand-o-right fa-fw"></i></span><span class="icon-select" data-theicon="play-circle fa-fw"><i class="fa fa-play-circle fa-fw"></i></span><span class="icon-select" data-theicon="github fa-fw"><i class="fa fa-github fa-fw"></i></span><span class="icon-select" data-theicon="medkit fa-fw"><i class="fa fa-medkit fa-fw"></i></span><span class="icon-select" data-theicon="caret-down"><i class="fa fa-caret-down"></i></span><span class="icon-select" data-theicon="flag"><i class="fa fa-flag"></i></span><span class="icon-select" data-theicon="angellist"><i class="fa fa-angellist"></i></span><span class="icon-select" data-theicon="area-chart"><i class="fa fa-area-chart"></i></span><span class="icon-select" data-theicon="at"><i class="fa fa-at"></i></span><span class="icon-select" data-theicon="bell-slash"><i class="fa fa-bell-slash"></i></span><span class="icon-select" data-theicon="bell-slash-o"><i class="fa fa-bell-slash-o"></i></span><span class="icon-select" data-theicon="bicycle"><i class="fa fa-bicycle"></i></span><span class="icon-select" data-theicon="binoculars"><i class="fa fa-binoculars"></i></span><span class="icon-select" data-theicon="birthday-cake"><i class="fa fa-birthday-cake"></i></span><span class="icon-select" data-theicon="bus"><i class="fa fa-bus"></i></span><span class="icon-select" data-theicon="calculator"><i class="fa fa-calculator"></i></span><span class="icon-select" data-theicon="cc"><i class="fa fa-cc"></i></span><span class="icon-select" data-theicon="cc-amex"><i class="fa fa-cc-amex"></i></span><span class="icon-select" data-theicon="cc-discover"><i class="fa fa-cc-discover"></i></span><span class="icon-select" data-theicon="cc-mastercard"><i class="fa fa-cc-mastercard"></i></span><span class="icon-select" data-theicon="cc-paypal"><i class="fa fa-cc-paypal"></i></span><span class="icon-select" data-theicon="cc-stripe"><i class="fa fa-cc-stripe"></i></span><span class="icon-select" data-theicon="cc-visa"><i class="fa fa-cc-visa"></i></span><span class="icon-select" data-theicon="copyright"><i class="fa fa-copyright"></i></span><span class="icon-select" data-theicon="eyedropper"><i class="fa fa-eyedropper"></i></span><span class="icon-select" data-theicon="futbol-o"><i class="fa fa-futbol-o"></i></span><span class="icon-select" data-theicon="google-wallet"><i class="fa fa-google-wallet"></i></span><span class="icon-select" data-theicon="ils"><i class="fa fa-ils"></i></span><span class="icon-select" data-theicon="ioxhost"><i class="fa fa-ioxhost"></i></span><span class="icon-select" data-theicon="lastfm"><i class="fa fa-lastfm"></i></span><span class="icon-select" data-theicon="lastfm-square"><i class="fa fa-lastfm-square"></i></span><span class="icon-select" data-theicon="line-chart"><i class="fa fa-line-chart"></i></span><span class="icon-select" data-theicon="meanpath"><i class="fa fa-meanpath"></i></span><span class="icon-select" data-theicon="newspaper-o"><i class="fa fa-newspaper-o"></i></span><span class="icon-select" data-theicon="paint-brush"><i class="fa fa-paint-brush"></i></span><span class="icon-select" data-theicon="paypal"><i class="fa fa-paypal"></i></span><span class="icon-select" data-theicon="pie-chart"><i class="fa fa-pie-chart"></i></span><span class="icon-select" data-theicon="plug"><i class="fa fa-plug"></i></span><span class="icon-select" data-theicon="shekel"><i class="fa fa-shekel"></i></span><span class="icon-select" data-theicon="sheqel"><i class="fa fa-sheqel"></i></span><span class="icon-select" data-theicon="slideshare"><i class="fa fa-slideshare"></i></span><span class="icon-select" data-theicon="soccer-ball-o"><i class="fa fa-soccer-ball-o"></i></span><span class="icon-select" data-theicon="toggle-off"><i class="fa fa-toggle-off"></i></span><span class="icon-select" data-theicon="toggle-on"><i class="fa fa-toggle-on"></i></span><span class="icon-select" data-theicon="trash"><i class="fa fa-trash"></i></span><span class="icon-select" data-theicon="tty"><i class="fa fa-tty"></i></span><span class="icon-select" data-theicon="twitch"><i class="fa fa-twitch"></i></span><span class="icon-select" data-theicon="wifi"><i class="fa fa-wifi"></i></span><span class="icon-select" data-theicon="yelp"><i class="fa fa-yelp"></i></span><span class="icon-select" data-theicon="adjust"><i class="fa fa-adjust"></i></span><span class="icon-select" data-theicon="anchor"><i class="fa fa-anchor"></i></span><span class="icon-select" data-theicon="archive"><i class="fa fa-archive"></i></span><span class="icon-select" data-theicon="area-chart"><i class="fa fa-area-chart"></i></span><span class="icon-select" data-theicon="arrows"><i class="fa fa-arrows"></i></span><span class="icon-select" data-theicon="arrows-h"><i class="fa fa-arrows-h"></i></span><span class="icon-select" data-theicon="arrows-v"><i class="fa fa-arrows-v"></i></span><span class="icon-select" data-theicon="asterisk"><i class="fa fa-asterisk"></i></span><span class="icon-select" data-theicon="at"><i class="fa fa-at"></i></span><span class="icon-select" data-theicon="automobile"><i class="fa fa-automobile"></i></span><span class="icon-select" data-theicon="ban"><i class="fa fa-ban"></i></span><span class="icon-select" data-theicon="bank"><i class="fa fa-bank"></i></span><span class="icon-select" data-theicon="bar-chart"><i class="fa fa-bar-chart"></i></span><span class="icon-select" data-theicon="bar-chart-o"><i class="fa fa-bar-chart-o"></i></span><span class="icon-select" data-theicon="barcode"><i class="fa fa-barcode"></i></span><span class="icon-select" data-theicon="bars"><i class="fa fa-bars"></i></span><span class="icon-select" data-theicon="beer"><i class="fa fa-beer"></i></span><span class="icon-select" data-theicon="bell"><i class="fa fa-bell"></i></span><span class="icon-select" data-theicon="bell-o"><i class="fa fa-bell-o"></i></span><span class="icon-select" data-theicon="bell-slash"><i class="fa fa-bell-slash"></i></span><span class="icon-select" data-theicon="bell-slash-o"><i class="fa fa-bell-slash-o"></i></span><span class="icon-select" data-theicon="bicycle"><i class="fa fa-bicycle"></i></span><span class="icon-select" data-theicon="binoculars"><i class="fa fa-binoculars"></i></span><span class="icon-select" data-theicon="birthday-cake"><i class="fa fa-birthday-cake"></i></span><span class="icon-select" data-theicon="bolt"><i class="fa fa-bolt"></i></span><span class="icon-select" data-theicon="bomb"><i class="fa fa-bomb"></i></span><span class="icon-select" data-theicon="book"><i class="fa fa-book"></i></span><span class="icon-select" data-theicon="bookmark"><i class="fa fa-bookmark"></i></span><span class="icon-select" data-theicon="bookmark-o"><i class="fa fa-bookmark-o"></i></span><span class="icon-select" data-theicon="briefcase"><i class="fa fa-briefcase"></i></span><span class="icon-select" data-theicon="bug"><i class="fa fa-bug"></i></span><span class="icon-select" data-theicon="building"><i class="fa fa-building"></i></span><span class="icon-select" data-theicon="building-o"><i class="fa fa-building-o"></i></span><span class="icon-select" data-theicon="bullhorn"><i class="fa fa-bullhorn"></i></span><span class="icon-select" data-theicon="bullseye"><i class="fa fa-bullseye"></i></span><span class="icon-select" data-theicon="bus"><i class="fa fa-bus"></i></span><span class="icon-select" data-theicon="cab"><i class="fa fa-cab"></i></span><span class="icon-select" data-theicon="calculator"><i class="fa fa-calculator"></i></span><span class="icon-select" data-theicon="calendar"><i class="fa fa-calendar"></i></span><span class="icon-select" data-theicon="calendar-o"><i class="fa fa-calendar-o"></i></span><span class="icon-select" data-theicon="camera"><i class="fa fa-camera"></i></span><span class="icon-select" data-theicon="camera-retro"><i class="fa fa-camera-retro"></i></span><span class="icon-select" data-theicon="car"><i class="fa fa-car"></i></span><span class="icon-select" data-theicon="caret-square-o-down"><i class="fa fa-caret-square-o-down"></i></span><span class="icon-select" data-theicon="caret-square-o-left"><i class="fa fa-caret-square-o-left"></i></span><span class="icon-select" data-theicon="caret-square-o-right"><i class="fa fa-caret-square-o-right"></i></span><span class="icon-select" data-theicon="caret-square-o-up"><i class="fa fa-caret-square-o-up"></i></span><span class="icon-select" data-theicon="cc"><i class="fa fa-cc"></i></span><span class="icon-select" data-theicon="certificate"><i class="fa fa-certificate"></i></span><span class="icon-select" data-theicon="check"><i class="fa fa-check"></i></span><span class="icon-select" data-theicon="check-circle"><i class="fa fa-check-circle"></i></span><span class="icon-select" data-theicon="check-circle-o"><i class="fa fa-check-circle-o"></i></span><span class="icon-select" data-theicon="check-square"><i class="fa fa-check-square"></i></span><span class="icon-select" data-theicon="check-square-o"><i class="fa fa-check-square-o"></i></span><span class="icon-select" data-theicon="child"><i class="fa fa-child"></i></span><span class="icon-select" data-theicon="circle"><i class="fa fa-circle"></i></span><span class="icon-select" data-theicon="circle-o"><i class="fa fa-circle-o"></i></span><span class="icon-select" data-theicon="circle-o-notch"><i class="fa fa-circle-o-notch"></i></span><span class="icon-select" data-theicon="circle-thin"><i class="fa fa-circle-thin"></i></span><span class="icon-select" data-theicon="clock-o"><i class="fa fa-clock-o"></i></span><span class="icon-select" data-theicon="close"><i class="fa fa-close"></i></span><span class="icon-select" data-theicon="cloud"><i class="fa fa-cloud"></i></span><span class="icon-select" data-theicon="cloud-download"><i class="fa fa-cloud-download"></i></span><span class="icon-select" data-theicon="cloud-upload"><i class="fa fa-cloud-upload"></i></span><span class="icon-select" data-theicon="code"><i class="fa fa-code"></i></span><span class="icon-select" data-theicon="code-fork"><i class="fa fa-code-fork"></i></span><span class="icon-select" data-theicon="coffee"><i class="fa fa-coffee"></i></span><span class="icon-select" data-theicon="cog"><i class="fa fa-cog"></i></span><span class="icon-select" data-theicon="cogs"><i class="fa fa-cogs"></i></span><span class="icon-select" data-theicon="comment"><i class="fa fa-comment"></i></span><span class="icon-select" data-theicon="comment-o"><i class="fa fa-comment-o"></i></span><span class="icon-select" data-theicon="comments"><i class="fa fa-comments"></i></span><span class="icon-select" data-theicon="comments-o"><i class="fa fa-comments-o"></i></span><span class="icon-select" data-theicon="compass"><i class="fa fa-compass"></i></span><span class="icon-select" data-theicon="copyright"><i class="fa fa-copyright"></i></span><span class="icon-select" data-theicon="credit-card"><i class="fa fa-credit-card"></i></span><span class="icon-select" data-theicon="crop"><i class="fa fa-crop"></i></span><span class="icon-select" data-theicon="crosshairs"><i class="fa fa-crosshairs"></i></span><span class="icon-select" data-theicon="cube"><i class="fa fa-cube"></i></span><span class="icon-select" data-theicon="cubes"><i class="fa fa-cubes"></i></span><span class="icon-select" data-theicon="cutlery"><i class="fa fa-cutlery"></i></span><span class="icon-select" data-theicon="dashboard"><i class="fa fa-dashboard"></i></span><span class="icon-select" data-theicon="database"><i class="fa fa-database"></i></span><span class="icon-select" data-theicon="desktop"><i class="fa fa-desktop"></i></span><span class="icon-select" data-theicon="dot-circle-o"><i class="fa fa-dot-circle-o"></i></span><span class="icon-select" data-theicon="download"><i class="fa fa-download"></i></span><span class="icon-select" data-theicon="edit"><i class="fa fa-edit"></i></span><span class="icon-select" data-theicon="ellipsis-h"><i class="fa fa-ellipsis-h"></i></span><span class="icon-select" data-theicon="ellipsis-v"><i class="fa fa-ellipsis-v"></i></span><span class="icon-select" data-theicon="envelope"><i class="fa fa-envelope"></i></span><span class="icon-select" data-theicon="envelope-o"><i class="fa fa-envelope-o"></i></span><span class="icon-select" data-theicon="envelope-square"><i class="fa fa-envelope-square"></i></span><span class="icon-select" data-theicon="eraser"><i class="fa fa-eraser"></i></span><span class="icon-select" data-theicon="exchange"><i class="fa fa-exchange"></i></span><span class="icon-select" data-theicon="exclamation"><i class="fa fa-exclamation"></i></span><span class="icon-select" data-theicon="exclamation-circle"><i class="fa fa-exclamation-circle"></i></span><span class="icon-select" data-theicon="exclamation-triangle"><i class="fa fa-exclamation-triangle"></i></span><span class="icon-select" data-theicon="external-link"><i class="fa fa-external-link"></i></span><span class="icon-select" data-theicon="external-link-square"><i class="fa fa-external-link-square"></i></span><span class="icon-select" data-theicon="eye"><i class="fa fa-eye"></i></span><span class="icon-select" data-theicon="eye-slash"><i class="fa fa-eye-slash"></i></span><span class="icon-select" data-theicon="eyedropper"><i class="fa fa-eyedropper"></i></span><span class="icon-select" data-theicon="fax"><i class="fa fa-fax"></i></span><span class="icon-select" data-theicon="female"><i class="fa fa-female"></i></span><span class="icon-select" data-theicon="fighter-jet"><i class="fa fa-fighter-jet"></i></span><span class="icon-select" data-theicon="file-archive-o"><i class="fa fa-file-archive-o"></i></span><span class="icon-select" data-theicon="file-audio-o"><i class="fa fa-file-audio-o"></i></span><span class="icon-select" data-theicon="file-code-o"><i class="fa fa-file-code-o"></i></span><span class="icon-select" data-theicon="file-excel-o"><i class="fa fa-file-excel-o"></i></span><span class="icon-select" data-theicon="file-image-o"><i class="fa fa-file-image-o"></i></span><span class="icon-select" data-theicon="file-movie-o"><i class="fa fa-file-movie-o"></i></span><span class="icon-select" data-theicon="file-pdf-o"><i class="fa fa-file-pdf-o"></i></span><span class="icon-select" data-theicon="file-photo-o"><i class="fa fa-file-photo-o"></i></span><span class="icon-select" data-theicon="file-picture-o"><i class="fa fa-file-picture-o"></i></span><span class="icon-select" data-theicon="file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i></span><span class="icon-select" data-theicon="file-sound-o"><i class="fa fa-file-sound-o"></i></span><span class="icon-select" data-theicon="file-video-o"><i class="fa fa-file-video-o"></i></span><span class="icon-select" data-theicon="file-word-o"><i class="fa fa-file-word-o"></i></span><span class="icon-select" data-theicon="file-zip-o"><i class="fa fa-file-zip-o"></i></span><span class="icon-select" data-theicon="film"><i class="fa fa-film"></i></span><span class="icon-select" data-theicon="filter"><i class="fa fa-filter"></i></span><span class="icon-select" data-theicon="fire"><i class="fa fa-fire"></i></span><span class="icon-select" data-theicon="fire-extinguisher"><i class="fa fa-fire-extinguisher"></i></span><span class="icon-select" data-theicon="flag"><i class="fa fa-flag"></i></span><span class="icon-select" data-theicon="flag-checkered"><i class="fa fa-flag-checkered"></i></span><span class="icon-select" data-theicon="flag-o"><i class="fa fa-flag-o"></i></span><span class="icon-select" data-theicon="flash"><i class="fa fa-flash"></i></span><span class="icon-select" data-theicon="flask"><i class="fa fa-flask"></i></span><span class="icon-select" data-theicon="folder"><i class="fa fa-folder"></i></span><span class="icon-select" data-theicon="folder-o"><i class="fa fa-folder-o"></i></span><span class="icon-select" data-theicon="folder-open"><i class="fa fa-folder-open"></i></span><span class="icon-select" data-theicon="folder-open-o"><i class="fa fa-folder-open-o"></i></span><span class="icon-select" data-theicon="frown-o"><i class="fa fa-frown-o"></i></span><span class="icon-select" data-theicon="futbol-o"><i class="fa fa-futbol-o"></i></span><span class="icon-select" data-theicon="gamepad"><i class="fa fa-gamepad"></i></span><span class="icon-select" data-theicon="gavel"><i class="fa fa-gavel"></i></span><span class="icon-select" data-theicon="gear"><i class="fa fa-gear"></i></span><span class="icon-select" data-theicon="gears"><i class="fa fa-gears"></i></span><span class="icon-select" data-theicon="gift"><i class="fa fa-gift"></i></span><span class="icon-select" data-theicon="glass"><i class="fa fa-glass"></i></span><span class="icon-select" data-theicon="globe"><i class="fa fa-globe"></i></span><span class="icon-select" data-theicon="graduation-cap"><i class="fa fa-graduation-cap"></i></span><span class="icon-select" data-theicon="group"><i class="fa fa-group"></i></span><span class="icon-select" data-theicon="hdd-o"><i class="fa fa-hdd-o"></i></span><span class="icon-select" data-theicon="headphones"><i class="fa fa-headphones"></i></span><span class="icon-select" data-theicon="heart"><i class="fa fa-heart"></i></span><span class="icon-select" data-theicon="heart-o"><i class="fa fa-heart-o"></i></span><span class="icon-select" data-theicon="history"><i class="fa fa-history"></i></span><span class="icon-select" data-theicon="home"><i class="fa fa-home"></i></span><span class="icon-select" data-theicon="image"><i class="fa fa-image"></i></span><span class="icon-select" data-theicon="inbox"><i class="fa fa-inbox"></i></span><span class="icon-select" data-theicon="info"><i class="fa fa-info"></i></span><span class="icon-select" data-theicon="info-circle"><i class="fa fa-info-circle"></i></span><span class="icon-select" data-theicon="institution"><i class="fa fa-institution"></i></span><span class="icon-select" data-theicon="key"><i class="fa fa-key"></i></span><span class="icon-select" data-theicon="keyboard-o"><i class="fa fa-keyboard-o"></i></span><span class="icon-select" data-theicon="language"><i class="fa fa-language"></i></span><span class="icon-select" data-theicon="laptop"><i class="fa fa-laptop"></i></span><span class="icon-select" data-theicon="leaf"><i class="fa fa-leaf"></i></span><span class="icon-select" data-theicon="legal"><i class="fa fa-legal"></i></span><span class="icon-select" data-theicon="lemon-o"><i class="fa fa-lemon-o"></i></span><span class="icon-select" data-theicon="level-down"><i class="fa fa-level-down"></i></span><span class="icon-select" data-theicon="level-up"><i class="fa fa-level-up"></i></span><span class="icon-select" data-theicon="life-bouy"><i class="fa fa-life-bouy"></i></span><span class="icon-select" data-theicon="life-buoy"><i class="fa fa-life-buoy"></i></span><span class="icon-select" data-theicon="life-ring"><i class="fa fa-life-ring"></i></span><span class="icon-select" data-theicon="life-saver"><i class="fa fa-life-saver"></i></span><span class="icon-select" data-theicon="lightbulb-o"><i class="fa fa-lightbulb-o"></i></span><span class="icon-select" data-theicon="line-chart"><i class="fa fa-line-chart"></i></span><span class="icon-select" data-theicon="location-arrow"><i class="fa fa-location-arrow"></i></span><span class="icon-select" data-theicon="lock"><i class="fa fa-lock"></i></span><span class="icon-select" data-theicon="magic"><i class="fa fa-magic"></i></span><span class="icon-select" data-theicon="magnet"><i class="fa fa-magnet"></i></span><span class="icon-select" data-theicon="mail-forward"><i class="fa fa-mail-forward"></i></span><span class="icon-select" data-theicon="mail-reply"><i class="fa fa-mail-reply"></i></span><span class="icon-select" data-theicon="mail-reply-all"><i class="fa fa-mail-reply-all"></i></span><span class="icon-select" data-theicon="male"><i class="fa fa-male"></i></span><span class="icon-select" data-theicon="map-marker"><i class="fa fa-map-marker"></i></span><span class="icon-select" data-theicon="meh-o"><i class="fa fa-meh-o"></i></span><span class="icon-select" data-theicon="microphone"><i class="fa fa-microphone"></i></span><span class="icon-select" data-theicon="microphone-slash"><i class="fa fa-microphone-slash"></i></span><span class="icon-select" data-theicon="minus"><i class="fa fa-minus"></i></span><span class="icon-select" data-theicon="minus-circle"><i class="fa fa-minus-circle"></i></span><span class="icon-select" data-theicon="minus-square"><i class="fa fa-minus-square"></i></span><span class="icon-select" data-theicon="minus-square-o"><i class="fa fa-minus-square-o"></i></span><span class="icon-select" data-theicon="mobile"><i class="fa fa-mobile"></i></span><span class="icon-select" data-theicon="mobile-phone"><i class="fa fa-mobile-phone"></i></span><span class="icon-select" data-theicon="money"><i class="fa fa-money"></i></span><span class="icon-select" data-theicon="moon-o"><i class="fa fa-moon-o"></i></span><span class="icon-select" data-theicon="mortar-board"><i class="fa fa-mortar-board"></i></span><span class="icon-select" data-theicon="music"><i class="fa fa-music"></i></span><span class="icon-select" data-theicon="navicon"><i class="fa fa-navicon"></i></span><span class="icon-select" data-theicon="newspaper-o"><i class="fa fa-newspaper-o"></i></span><span class="icon-select" data-theicon="paint-brush"><i class="fa fa-paint-brush"></i></span><span class="icon-select" data-theicon="paper-plane"><i class="fa fa-paper-plane"></i></span><span class="icon-select" data-theicon="paper-plane-o"><i class="fa fa-paper-plane-o"></i></span><span class="icon-select" data-theicon="paw"><i class="fa fa-paw"></i></span><span class="icon-select" data-theicon="pencil"><i class="fa fa-pencil"></i></span><span class="icon-select" data-theicon="pencil-square"><i class="fa fa-pencil-square"></i></span><span class="icon-select" data-theicon="pencil-square-o"><i class="fa fa-pencil-square-o"></i></span><span class="icon-select" data-theicon="phone"><i class="fa fa-phone"></i></span><span class="icon-select" data-theicon="phone-square"><i class="fa fa-phone-square"></i></span><span class="icon-select" data-theicon="photo"><i class="fa fa-photo"></i></span><span class="icon-select" data-theicon="picture-o"><i class="fa fa-picture-o"></i></span><span class="icon-select" data-theicon="pie-chart"><i class="fa fa-pie-chart"></i></span><span class="icon-select" data-theicon="plane"><i class="fa fa-plane"></i></span><span class="icon-select" data-theicon="plug"><i class="fa fa-plug"></i></span><span class="icon-select" data-theicon="plus"><i class="fa fa-plus"></i></span><span class="icon-select" data-theicon="plus-circle"><i class="fa fa-plus-circle"></i></span><span class="icon-select" data-theicon="plus-square"><i class="fa fa-plus-square"></i></span><span class="icon-select" data-theicon="plus-square-o"><i class="fa fa-plus-square-o"></i></span><span class="icon-select" data-theicon="power-off"><i class="fa fa-power-off"></i></span><span class="icon-select" data-theicon="print"><i class="fa fa-print"></i></span><span class="icon-select" data-theicon="puzzle-piece"><i class="fa fa-puzzle-piece"></i></span><span class="icon-select" data-theicon="qrcode"><i class="fa fa-qrcode"></i></span><span class="icon-select" data-theicon="question"><i class="fa fa-question"></i></span><span class="icon-select" data-theicon="question-circle"><i class="fa fa-question-circle"></i></span><span class="icon-select" data-theicon="quote-left"><i class="fa fa-quote-left"></i></span><span class="icon-select" data-theicon="quote-right"><i class="fa fa-quote-right"></i></span><span class="icon-select" data-theicon="random"><i class="fa fa-random"></i></span><span class="icon-select" data-theicon="recycle"><i class="fa fa-recycle"></i></span><span class="icon-select" data-theicon="refresh"><i class="fa fa-refresh"></i></span><span class="icon-select" data-theicon="remove"><i class="fa fa-remove"></i></span><span class="icon-select" data-theicon="reorder"><i class="fa fa-reorder"></i></span><span class="icon-select" data-theicon="reply"><i class="fa fa-reply"></i></span><span class="icon-select" data-theicon="reply-all"><i class="fa fa-reply-all"></i></span><span class="icon-select" data-theicon="retweet"><i class="fa fa-retweet"></i></span><span class="icon-select" data-theicon="road"><i class="fa fa-road"></i></span><span class="icon-select" data-theicon="rocket"><i class="fa fa-rocket"></i></span><span class="icon-select" data-theicon="rss"><i class="fa fa-rss"></i></span><span class="icon-select" data-theicon="rss-square"><i class="fa fa-rss-square"></i></span><span class="icon-select" data-theicon="search"><i class="fa fa-search"></i></span><span class="icon-select" data-theicon="search-minus"><i class="fa fa-search-minus"></i></span><span class="icon-select" data-theicon="search-plus"><i class="fa fa-search-plus"></i></span><span class="icon-select" data-theicon="send"><i class="fa fa-send"></i></span><span class="icon-select" data-theicon="send-o"><i class="fa fa-send-o"></i></span><span class="icon-select" data-theicon="share"><i class="fa fa-share"></i></span><span class="icon-select" data-theicon="share-alt"><i class="fa fa-share-alt"></i></span><span class="icon-select" data-theicon="share-alt-square"><i class="fa fa-share-alt-square"></i></span><span class="icon-select" data-theicon="share-square"><i class="fa fa-share-square"></i></span><span class="icon-select" data-theicon="share-square-o"><i class="fa fa-share-square-o"></i></span><span class="icon-select" data-theicon="shield"><i class="fa fa-shield"></i></span><span class="icon-select" data-theicon="shopping-cart"><i class="fa fa-shopping-cart"></i></span><span class="icon-select" data-theicon="sign-in"><i class="fa fa-sign-in"></i></span><span class="icon-select" data-theicon="sign-out"><i class="fa fa-sign-out"></i></span><span class="icon-select" data-theicon="signal"><i class="fa fa-signal"></i></span><span class="icon-select" data-theicon="sitemap"><i class="fa fa-sitemap"></i></span><span class="icon-select" data-theicon="sliders"><i class="fa fa-sliders"></i></span><span class="icon-select" data-theicon="smile-o"><i class="fa fa-smile-o"></i></span><span class="icon-select" data-theicon="soccer-ball-o"><i class="fa fa-soccer-ball-o"></i></span><span class="icon-select" data-theicon="sort"><i class="fa fa-sort"></i></span><span class="icon-select" data-theicon="sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i></span><span class="icon-select" data-theicon="sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i></span><span class="icon-select" data-theicon="sort-amount-asc"><i class="fa fa-sort-amount-asc"></i></span><span class="icon-select" data-theicon="sort-amount-desc"><i class="fa fa-sort-amount-desc"></i></span><span class="icon-select" data-theicon="sort-asc"><i class="fa fa-sort-asc"></i></span><span class="icon-select" data-theicon="sort-desc"><i class="fa fa-sort-desc"></i></span><span class="icon-select" data-theicon="sort-down"><i class="fa fa-sort-down"></i></span><span class="icon-select" data-theicon="sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i></span><span class="icon-select" data-theicon="sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i></span><span class="icon-select" data-theicon="sort-up"><i class="fa fa-sort-up"></i></span><span class="icon-select" data-theicon="space-shuttle"><i class="fa fa-space-shuttle"></i></span><span class="icon-select" data-theicon="spinner"><i class="fa fa-spinner"></i></span><span class="icon-select" data-theicon="spoon"><i class="fa fa-spoon"></i></span><span class="icon-select" data-theicon="square"><i class="fa fa-square"></i></span><span class="icon-select" data-theicon="square-o"><i class="fa fa-square-o"></i></span><span class="icon-select" data-theicon="star"><i class="fa fa-star"></i></span><span class="icon-select" data-theicon="star-half"><i class="fa fa-star-half"></i></span><span class="icon-select" data-theicon="star-half-empty"><i class="fa fa-star-half-empty"></i></span><span class="icon-select" data-theicon="star-half-full"><i class="fa fa-star-half-full"></i></span><span class="icon-select" data-theicon="star-half-o"><i class="fa fa-star-half-o"></i></span><span class="icon-select" data-theicon="star-o"><i class="fa fa-star-o"></i></span><span class="icon-select" data-theicon="suitcase"><i class="fa fa-suitcase"></i></span><span class="icon-select" data-theicon="sun-o"><i class="fa fa-sun-o"></i></span><span class="icon-select" data-theicon="support"><i class="fa fa-support"></i></span><span class="icon-select" data-theicon="tablet"><i class="fa fa-tablet"></i></span><span class="icon-select" data-theicon="tachometer"><i class="fa fa-tachometer"></i></span><span class="icon-select" data-theicon="tag"><i class="fa fa-tag"></i></span><span class="icon-select" data-theicon="tags"><i class="fa fa-tags"></i></span><span class="icon-select" data-theicon="tasks"><i class="fa fa-tasks"></i></span><span class="icon-select" data-theicon="taxi"><i class="fa fa-taxi"></i></span><span class="icon-select" data-theicon="terminal"><i class="fa fa-terminal"></i></span><span class="icon-select" data-theicon="thumb-tack"><i class="fa fa-thumb-tack"></i></span><span class="icon-select" data-theicon="thumbs-down"><i class="fa fa-thumbs-down"></i></span><span class="icon-select" data-theicon="thumbs-o-down"><i class="fa fa-thumbs-o-down"></i></span><span class="icon-select" data-theicon="thumbs-o-up"><i class="fa fa-thumbs-o-up"></i></span><span class="icon-select" data-theicon="thumbs-up"><i class="fa fa-thumbs-up"></i></span><span class="icon-select" data-theicon="ticket"><i class="fa fa-ticket"></i></span><span class="icon-select" data-theicon="times"><i class="fa fa-times"></i></span><span class="icon-select" data-theicon="times-circle"><i class="fa fa-times-circle"></i></span><span class="icon-select" data-theicon="times-circle-o"><i class="fa fa-times-circle-o"></i></span><span class="icon-select" data-theicon="tint"><i class="fa fa-tint"></i></span><span class="icon-select" data-theicon="toggle-down"><i class="fa fa-toggle-down"></i></span><span class="icon-select" data-theicon="toggle-left"><i class="fa fa-toggle-left"></i></span><span class="icon-select" data-theicon="toggle-off"><i class="fa fa-toggle-off"></i></span><span class="icon-select" data-theicon="toggle-on"><i class="fa fa-toggle-on"></i></span><span class="icon-select" data-theicon="toggle-right"><i class="fa fa-toggle-right"></i></span><span class="icon-select" data-theicon="toggle-up"><i class="fa fa-toggle-up"></i></span><span class="icon-select" data-theicon="trash"><i class="fa fa-trash"></i></span><span class="icon-select" data-theicon="trash-o"><i class="fa fa-trash-o"></i></span><span class="icon-select" data-theicon="tree"><i class="fa fa-tree"></i></span><span class="icon-select" data-theicon="trophy"><i class="fa fa-trophy"></i></span><span class="icon-select" data-theicon="truck"><i class="fa fa-truck"></i></span><span class="icon-select" data-theicon="tty"><i class="fa fa-tty"></i></span><span class="icon-select" data-theicon="umbrella"><i class="fa fa-umbrella"></i></span><span class="icon-select" data-theicon="university"><i class="fa fa-university"></i></span><span class="icon-select" data-theicon="unlock"><i class="fa fa-unlock"></i></span><span class="icon-select" data-theicon="unlock-alt"><i class="fa fa-unlock-alt"></i></span><span class="icon-select" data-theicon="unsorted"><i class="fa fa-unsorted"></i></span><span class="icon-select" data-theicon="upload"><i class="fa fa-upload"></i></span><span class="icon-select" data-theicon="user"><i class="fa fa-user"></i></span><span class="icon-select" data-theicon="users"><i class="fa fa-users"></i></span><span class="icon-select" data-theicon="video-camera"><i class="fa fa-video-camera"></i></span><span class="icon-select" data-theicon="volume-down"><i class="fa fa-volume-down"></i></span><span class="icon-select" data-theicon="volume-off"><i class="fa fa-volume-off"></i></span><span class="icon-select" data-theicon="volume-up"><i class="fa fa-volume-up"></i></span><span class="icon-select" data-theicon="warning"><i class="fa fa-warning"></i></span><span class="icon-select" data-theicon="wheelchair"><i class="fa fa-wheelchair"></i></span><span class="icon-select" data-theicon="wifi"><i class="fa fa-wifi"></i></span><span class="icon-select" data-theicon="wrench"><i class="fa fa-wrench"></i></span><span class="icon-select" data-theicon="file"><i class="fa fa-file"></i></span><span class="icon-select" data-theicon="file-archive-o"><i class="fa fa-file-archive-o"></i></span><span class="icon-select" data-theicon="file-audio-o"><i class="fa fa-file-audio-o"></i></span><span class="icon-select" data-theicon="file-code-o"><i class="fa fa-file-code-o"></i></span><span class="icon-select" data-theicon="file-excel-o"><i class="fa fa-file-excel-o"></i></span><span class="icon-select" data-theicon="file-image-o"><i class="fa fa-file-image-o"></i></span><span class="icon-select" data-theicon="file-movie-o"><i class="fa fa-file-movie-o"></i></span><span class="icon-select" data-theicon="file-o"><i class="fa fa-file-o"></i></span><span class="icon-select" data-theicon="file-pdf-o"><i class="fa fa-file-pdf-o"></i></span><span class="icon-select" data-theicon="file-photo-o"><i class="fa fa-file-photo-o"></i></span><span class="icon-select" data-theicon="file-picture-o"><i class="fa fa-file-picture-o"></i></span><span class="icon-select" data-theicon="file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i></span><span class="icon-select" data-theicon="file-sound-o"><i class="fa fa-file-sound-o"></i></span><span class="icon-select" data-theicon="file-text"><i class="fa fa-file-text"></i></span><span class="icon-select" data-theicon="file-text-o"><i class="fa fa-file-text-o"></i></span><span class="icon-select" data-theicon="file-video-o"><i class="fa fa-file-video-o"></i></span><span class="icon-select" data-theicon="file-word-o"><i class="fa fa-file-word-o"></i></span><span class="icon-select" data-theicon="file-zip-o"><i class="fa fa-file-zip-o"></i></span><span class="icon-select" data-theicon="info-circle fa-lg fa-li"><i class="fa fa-info-circle fa-lg fa-li"></i></span><span class="icon-select" data-theicon="circle-o-notch"><i class="fa fa-circle-o-notch"></i></span><span class="icon-select" data-theicon="cog"><i class="fa fa-cog"></i></span><span class="icon-select" data-theicon="gear"><i class="fa fa-gear"></i></span><span class="icon-select" data-theicon="refresh"><i class="fa fa-refresh"></i></span><span class="icon-select" data-theicon="spinner"><i class="fa fa-spinner"></i></span><span class="icon-select" data-theicon="check-square"><i class="fa fa-check-square"></i></span><span class="icon-select" data-theicon="check-square-o"><i class="fa fa-check-square-o"></i></span><span class="icon-select" data-theicon="circle"><i class="fa fa-circle"></i></span><span class="icon-select" data-theicon="circle-o"><i class="fa fa-circle-o"></i></span><span class="icon-select" data-theicon="dot-circle-o"><i class="fa fa-dot-circle-o"></i></span><span class="icon-select" data-theicon="minus-square"><i class="fa fa-minus-square"></i></span><span class="icon-select" data-theicon="minus-square-o"><i class="fa fa-minus-square-o"></i></span><span class="icon-select" data-theicon="plus-square"><i class="fa fa-plus-square"></i></span><span class="icon-select" data-theicon="plus-square-o"><i class="fa fa-plus-square-o"></i></span><span class="icon-select" data-theicon="square"><i class="fa fa-square"></i></span><span class="icon-select" data-theicon="square-o"><i class="fa fa-square-o"></i></span><span class="icon-select" data-theicon="cc-amex"><i class="fa fa-cc-amex"></i></span><span class="icon-select" data-theicon="cc-discover"><i class="fa fa-cc-discover"></i></span><span class="icon-select" data-theicon="cc-mastercard"><i class="fa fa-cc-mastercard"></i></span><span class="icon-select" data-theicon="cc-paypal"><i class="fa fa-cc-paypal"></i></span><span class="icon-select" data-theicon="cc-stripe"><i class="fa fa-cc-stripe"></i></span><span class="icon-select" data-theicon="cc-visa"><i class="fa fa-cc-visa"></i></span><span class="icon-select" data-theicon="credit-card"><i class="fa fa-credit-card"></i></span><span class="icon-select" data-theicon="google-wallet"><i class="fa fa-google-wallet"></i></span><span class="icon-select" data-theicon="paypal"><i class="fa fa-paypal"></i></span><span class="icon-select" data-theicon="area-chart"><i class="fa fa-area-chart"></i></span><span class="icon-select" data-theicon="bar-chart"><i class="fa fa-bar-chart"></i></span><span class="icon-select" data-theicon="bar-chart-o"><i class="fa fa-bar-chart-o"></i></span><span class="icon-select" data-theicon="line-chart"><i class="fa fa-line-chart"></i></span><span class="icon-select" data-theicon="pie-chart"><i class="fa fa-pie-chart"></i></span><span class="icon-select" data-theicon="bitcoin"><i class="fa fa-bitcoin"></i></span><span class="icon-select" data-theicon="btc"><i class="fa fa-btc"></i></span><span class="icon-select" data-theicon="cny"><i class="fa fa-cny"></i></span><span class="icon-select" data-theicon="dollar"><i class="fa fa-dollar"></i></span><span class="icon-select" data-theicon="eur"><i class="fa fa-eur"></i></span><span class="icon-select" data-theicon="euro"><i class="fa fa-euro"></i></span><span class="icon-select" data-theicon="gbp"><i class="fa fa-gbp"></i></span><span class="icon-select" data-theicon="ils"><i class="fa fa-ils"></i></span><span class="icon-select" data-theicon="inr"><i class="fa fa-inr"></i></span><span class="icon-select" data-theicon="jpy"><i class="fa fa-jpy"></i></span><span class="icon-select" data-theicon="krw"><i class="fa fa-krw"></i></span><span class="icon-select" data-theicon="money"><i class="fa fa-money"></i></span><span class="icon-select" data-theicon="rmb"><i class="fa fa-rmb"></i></span><span class="icon-select" data-theicon="rouble"><i class="fa fa-rouble"></i></span><span class="icon-select" data-theicon="rub"><i class="fa fa-rub"></i></span><span class="icon-select" data-theicon="ruble"><i class="fa fa-ruble"></i></span><span class="icon-select" data-theicon="rupee"><i class="fa fa-rupee"></i></span><span class="icon-select" data-theicon="shekel"><i class="fa fa-shekel"></i></span><span class="icon-select" data-theicon="sheqel"><i class="fa fa-sheqel"></i></span><span class="icon-select" data-theicon="try"><i class="fa fa-try"></i></span><span class="icon-select" data-theicon="turkish-lira"><i class="fa fa-turkish-lira"></i></span><span class="icon-select" data-theicon="usd"><i class="fa fa-usd"></i></span><span class="icon-select" data-theicon="won"><i class="fa fa-won"></i></span><span class="icon-select" data-theicon="yen"><i class="fa fa-yen"></i></span><span class="icon-select" data-theicon="align-center"><i class="fa fa-align-center"></i></span><span class="icon-select" data-theicon="align-justify"><i class="fa fa-align-justify"></i></span><span class="icon-select" data-theicon="align-left"><i class="fa fa-align-left"></i></span><span class="icon-select" data-theicon="align-right"><i class="fa fa-align-right"></i></span><span class="icon-select" data-theicon="bold"><i class="fa fa-bold"></i></span><span class="icon-select" data-theicon="chain"><i class="fa fa-chain"></i></span><span class="icon-select" data-theicon="chain-broken"><i class="fa fa-chain-broken"></i></span><span class="icon-select" data-theicon="clipboard"><i class="fa fa-clipboard"></i></span><span class="icon-select" data-theicon="columns"><i class="fa fa-columns"></i></span><span class="icon-select" data-theicon="copy"><i class="fa fa-copy"></i></span><span class="icon-select" data-theicon="cut"><i class="fa fa-cut"></i></span><span class="icon-select" data-theicon="dedent"><i class="fa fa-dedent"></i></span><span class="icon-select" data-theicon="eraser"><i class="fa fa-eraser"></i></span><span class="icon-select" data-theicon="file"><i class="fa fa-file"></i></span><span class="icon-select" data-theicon="file-o"><i class="fa fa-file-o"></i></span><span class="icon-select" data-theicon="file-text"><i class="fa fa-file-text"></i></span><span class="icon-select" data-theicon="file-text-o"><i class="fa fa-file-text-o"></i></span><span class="icon-select" data-theicon="files-o"><i class="fa fa-files-o"></i></span><span class="icon-select" data-theicon="floppy-o"><i class="fa fa-floppy-o"></i></span><span class="icon-select" data-theicon="font"><i class="fa fa-font"></i></span><span class="icon-select" data-theicon="header"><i class="fa fa-header"></i></span><span class="icon-select" data-theicon="indent"><i class="fa fa-indent"></i></span><span class="icon-select" data-theicon="italic"><i class="fa fa-italic"></i></span><span class="icon-select" data-theicon="link"><i class="fa fa-link"></i></span><span class="icon-select" data-theicon="list"><i class="fa fa-list"></i></span><span class="icon-select" data-theicon="list-alt"><i class="fa fa-list-alt"></i></span><span class="icon-select" data-theicon="list-ol"><i class="fa fa-list-ol"></i></span><span class="icon-select" data-theicon="list-ul"><i class="fa fa-list-ul"></i></span><span class="icon-select" data-theicon="outdent"><i class="fa fa-outdent"></i></span><span class="icon-select" data-theicon="paperclip"><i class="fa fa-paperclip"></i></span><span class="icon-select" data-theicon="paragraph"><i class="fa fa-paragraph"></i></span><span class="icon-select" data-theicon="paste"><i class="fa fa-paste"></i></span><span class="icon-select" data-theicon="repeat"><i class="fa fa-repeat"></i></span><span class="icon-select" data-theicon="rotate-left"><i class="fa fa-rotate-left"></i></span><span class="icon-select" data-theicon="rotate-right"><i class="fa fa-rotate-right"></i></span><span class="icon-select" data-theicon="save"><i class="fa fa-save"></i></span><span class="icon-select" data-theicon="scissors"><i class="fa fa-scissors"></i></span><span class="icon-select" data-theicon="strikethrough"><i class="fa fa-strikethrough"></i></span><span class="icon-select" data-theicon="subscript"><i class="fa fa-subscript"></i></span><span class="icon-select" data-theicon="superscript"><i class="fa fa-superscript"></i></span><span class="icon-select" data-theicon="table"><i class="fa fa-table"></i></span><span class="icon-select" data-theicon="text-height"><i class="fa fa-text-height"></i></span><span class="icon-select" data-theicon="text-width"><i class="fa fa-text-width"></i></span><span class="icon-select" data-theicon="th"><i class="fa fa-th"></i></span><span class="icon-select" data-theicon="th-large"><i class="fa fa-th-large"></i></span><span class="icon-select" data-theicon="th-list"><i class="fa fa-th-list"></i></span><span class="icon-select" data-theicon="underline"><i class="fa fa-underline"></i></span><span class="icon-select" data-theicon="undo"><i class="fa fa-undo"></i></span><span class="icon-select" data-theicon="unlink"><i class="fa fa-unlink"></i></span><span class="icon-select" data-theicon="angle-double-down"><i class="fa fa-angle-double-down"></i></span><span class="icon-select" data-theicon="angle-double-left"><i class="fa fa-angle-double-left"></i></span><span class="icon-select" data-theicon="angle-double-right"><i class="fa fa-angle-double-right"></i></span><span class="icon-select" data-theicon="angle-double-up"><i class="fa fa-angle-double-up"></i></span><span class="icon-select" data-theicon="angle-down"><i class="fa fa-angle-down"></i></span><span class="icon-select" data-theicon="angle-left"><i class="fa fa-angle-left"></i></span><span class="icon-select" data-theicon="angle-right"><i class="fa fa-angle-right"></i></span><span class="icon-select" data-theicon="angle-up"><i class="fa fa-angle-up"></i></span><span class="icon-select" data-theicon="arrow-circle-down"><i class="fa fa-arrow-circle-down"></i></span><span class="icon-select" data-theicon="arrow-circle-left"><i class="fa fa-arrow-circle-left"></i></span><span class="icon-select" data-theicon="arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i></span><span class="icon-select" data-theicon="arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i></span><span class="icon-select" data-theicon="arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i></span><span class="icon-select" data-theicon="arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i></span><span class="icon-select" data-theicon="arrow-circle-right"><i class="fa fa-arrow-circle-right"></i></span><span class="icon-select" data-theicon="arrow-circle-up"><i class="fa fa-arrow-circle-up"></i></span><span class="icon-select" data-theicon="arrow-down"><i class="fa fa-arrow-down"></i></span><span class="icon-select" data-theicon="arrow-left"><i class="fa fa-arrow-left"></i></span><span class="icon-select" data-theicon="arrow-right"><i class="fa fa-arrow-right"></i></span><span class="icon-select" data-theicon="arrow-up"><i class="fa fa-arrow-up"></i></span><span class="icon-select" data-theicon="arrows"><i class="fa fa-arrows"></i></span><span class="icon-select" data-theicon="arrows-alt"><i class="fa fa-arrows-alt"></i></span><span class="icon-select" data-theicon="arrows-h"><i class="fa fa-arrows-h"></i></span><span class="icon-select" data-theicon="arrows-v"><i class="fa fa-arrows-v"></i></span><span class="icon-select" data-theicon="caret-down"><i class="fa fa-caret-down"></i></span><span class="icon-select" data-theicon="caret-left"><i class="fa fa-caret-left"></i></span><span class="icon-select" data-theicon="caret-right"><i class="fa fa-caret-right"></i></span><span class="icon-select" data-theicon="caret-square-o-down"><i class="fa fa-caret-square-o-down"></i></span><span class="icon-select" data-theicon="caret-square-o-left"><i class="fa fa-caret-square-o-left"></i></span><span class="icon-select" data-theicon="caret-square-o-right"><i class="fa fa-caret-square-o-right"></i></span><span class="icon-select" data-theicon="caret-square-o-up"><i class="fa fa-caret-square-o-up"></i></span><span class="icon-select" data-theicon="caret-up"><i class="fa fa-caret-up"></i></span><span class="icon-select" data-theicon="chevron-circle-down"><i class="fa fa-chevron-circle-down"></i></span><span class="icon-select" data-theicon="chevron-circle-left"><i class="fa fa-chevron-circle-left"></i></span><span class="icon-select" data-theicon="chevron-circle-right"><i class="fa fa-chevron-circle-right"></i></span><span class="icon-select" data-theicon="chevron-circle-up"><i class="fa fa-chevron-circle-up"></i></span><span class="icon-select" data-theicon="chevron-down"><i class="fa fa-chevron-down"></i></span><span class="icon-select" data-theicon="chevron-left"><i class="fa fa-chevron-left"></i></span><span class="icon-select" data-theicon="chevron-right"><i class="fa fa-chevron-right"></i></span><span class="icon-select" data-theicon="chevron-up"><i class="fa fa-chevron-up"></i></span><span class="icon-select" data-theicon="hand-o-down"><i class="fa fa-hand-o-down"></i></span><span class="icon-select" data-theicon="hand-o-left"><i class="fa fa-hand-o-left"></i></span><span class="icon-select" data-theicon="hand-o-right"><i class="fa fa-hand-o-right"></i></span><span class="icon-select" data-theicon="hand-o-up"><i class="fa fa-hand-o-up"></i></span><span class="icon-select" data-theicon="long-arrow-down"><i class="fa fa-long-arrow-down"></i></span><span class="icon-select" data-theicon="long-arrow-left"><i class="fa fa-long-arrow-left"></i></span><span class="icon-select" data-theicon="long-arrow-right"><i class="fa fa-long-arrow-right"></i></span><span class="icon-select" data-theicon="long-arrow-up"><i class="fa fa-long-arrow-up"></i></span><span class="icon-select" data-theicon="toggle-down"><i class="fa fa-toggle-down"></i></span><span class="icon-select" data-theicon="toggle-left"><i class="fa fa-toggle-left"></i></span><span class="icon-select" data-theicon="toggle-right"><i class="fa fa-toggle-right"></i></span><span class="icon-select" data-theicon="toggle-up"><i class="fa fa-toggle-up"></i></span><span class="icon-select" data-theicon="arrows-alt"><i class="fa fa-arrows-alt"></i></span><span class="icon-select" data-theicon="backward"><i class="fa fa-backward"></i></span><span class="icon-select" data-theicon="compress"><i class="fa fa-compress"></i></span><span class="icon-select" data-theicon="eject"><i class="fa fa-eject"></i></span><span class="icon-select" data-theicon="expand"><i class="fa fa-expand"></i></span><span class="icon-select" data-theicon="fast-backward"><i class="fa fa-fast-backward"></i></span><span class="icon-select" data-theicon="fast-forward"><i class="fa fa-fast-forward"></i></span><span class="icon-select" data-theicon="forward"><i class="fa fa-forward"></i></span><span class="icon-select" data-theicon="pause"><i class="fa fa-pause"></i></span><span class="icon-select" data-theicon="play"><i class="fa fa-play"></i></span><span class="icon-select" data-theicon="play-circle"><i class="fa fa-play-circle"></i></span><span class="icon-select" data-theicon="play-circle-o"><i class="fa fa-play-circle-o"></i></span><span class="icon-select" data-theicon="step-backward"><i class="fa fa-step-backward"></i></span><span class="icon-select" data-theicon="step-forward"><i class="fa fa-step-forward"></i></span><span class="icon-select" data-theicon="stop"><i class="fa fa-stop"></i></span><span class="icon-select" data-theicon="youtube-play"><i class="fa fa-youtube-play"></i></span><span class="icon-select" data-theicon="warning"><i class="fa fa-warning"></i></span><span class="icon-select" data-theicon="adn"><i class="fa fa-adn"></i></span><span class="icon-select" data-theicon="android"><i class="fa fa-android"></i></span><span class="icon-select" data-theicon="angellist"><i class="fa fa-angellist"></i></span><span class="icon-select" data-theicon="apple"><i class="fa fa-apple"></i></span><span class="icon-select" data-theicon="behance"><i class="fa fa-behance"></i></span><span class="icon-select" data-theicon="behance-square"><i class="fa fa-behance-square"></i></span><span class="icon-select" data-theicon="bitbucket"><i class="fa fa-bitbucket"></i></span><span class="icon-select" data-theicon="bitbucket-square"><i class="fa fa-bitbucket-square"></i></span><span class="icon-select" data-theicon="bitcoin"><i class="fa fa-bitcoin"></i></span><span class="icon-select" data-theicon="btc"><i class="fa fa-btc"></i></span><span class="icon-select" data-theicon="cc-amex"><i class="fa fa-cc-amex"></i></span><span class="icon-select" data-theicon="cc-discover"><i class="fa fa-cc-discover"></i></span><span class="icon-select" data-theicon="cc-mastercard"><i class="fa fa-cc-mastercard"></i></span><span class="icon-select" data-theicon="cc-paypal"><i class="fa fa-cc-paypal"></i></span><span class="icon-select" data-theicon="cc-stripe"><i class="fa fa-cc-stripe"></i></span><span class="icon-select" data-theicon="cc-visa"><i class="fa fa-cc-visa"></i></span><span class="icon-select" data-theicon="codepen"><i class="fa fa-codepen"></i></span><span class="icon-select" data-theicon="css3"><i class="fa fa-css3"></i></span><span class="icon-select" data-theicon="delicious"><i class="fa fa-delicious"></i></span><span class="icon-select" data-theicon="deviantart"><i class="fa fa-deviantart"></i></span><span class="icon-select" data-theicon="digg"><i class="fa fa-digg"></i></span><span class="icon-select" data-theicon="dribbble"><i class="fa fa-dribbble"></i></span><span class="icon-select" data-theicon="dropbox"><i class="fa fa-dropbox"></i></span><span class="icon-select" data-theicon="drupal"><i class="fa fa-drupal"></i></span><span class="icon-select" data-theicon="empire"><i class="fa fa-empire"></i></span><span class="icon-select" data-theicon="facebook"><i class="fa fa-facebook"></i></span><span class="icon-select" data-theicon="facebook-square"><i class="fa fa-facebook-square"></i></span><span class="icon-select" data-theicon="flickr"><i class="fa fa-flickr"></i></span><span class="icon-select" data-theicon="foursquare"><i class="fa fa-foursquare"></i></span><span class="icon-select" data-theicon="ge"><i class="fa fa-ge"></i></span><span class="icon-select" data-theicon="git"><i class="fa fa-git"></i></span><span class="icon-select" data-theicon="git-square"><i class="fa fa-git-square"></i></span><span class="icon-select" data-theicon="github"><i class="fa fa-github"></i></span><span class="icon-select" data-theicon="github-alt"><i class="fa fa-github-alt"></i></span><span class="icon-select" data-theicon="github-square"><i class="fa fa-github-square"></i></span><span class="icon-select" data-theicon="gittip"><i class="fa fa-gittip"></i></span><span class="icon-select" data-theicon="google"><i class="fa fa-google"></i></span><span class="icon-select" data-theicon="google-plus"><i class="fa fa-google-plus"></i></span><span class="icon-select" data-theicon="google-plus-square"><i class="fa fa-google-plus-square"></i></span><span class="icon-select" data-theicon="google-wallet"><i class="fa fa-google-wallet"></i></span><span class="icon-select" data-theicon="hacker-news"><i class="fa fa-hacker-news"></i></span><span class="icon-select" data-theicon="html5"><i class="fa fa-html5"></i></span><span class="icon-select" data-theicon="instagram"><i class="fa fa-instagram"></i></span><span class="icon-select" data-theicon="ioxhost"><i class="fa fa-ioxhost"></i></span><span class="icon-select" data-theicon="joomla"><i class="fa fa-joomla"></i></span><span class="icon-select" data-theicon="jsfiddle"><i class="fa fa-jsfiddle"></i></span><span class="icon-select" data-theicon="lastfm"><i class="fa fa-lastfm"></i></span><span class="icon-select" data-theicon="lastfm-square"><i class="fa fa-lastfm-square"></i></span><span class="icon-select" data-theicon="linkedin"><i class="fa fa-linkedin"></i></span><span class="icon-select" data-theicon="linkedin-square"><i class="fa fa-linkedin-square"></i></span><span class="icon-select" data-theicon="linux"><i class="fa fa-linux"></i></span><span class="icon-select" data-theicon="maxcdn"><i class="fa fa-maxcdn"></i></span><span class="icon-select" data-theicon="meanpath"><i class="fa fa-meanpath"></i></span><span class="icon-select" data-theicon="openid"><i class="fa fa-openid"></i></span><span class="icon-select" data-theicon="pagelines"><i class="fa fa-pagelines"></i></span><span class="icon-select" data-theicon="paypal"><i class="fa fa-paypal"></i></span><span class="icon-select" data-theicon="pied-piper"><i class="fa fa-pied-piper"></i></span><span class="icon-select" data-theicon="pied-piper-alt"><i class="fa fa-pied-piper-alt"></i></span><span class="icon-select" data-theicon="pinterest"><i class="fa fa-pinterest"></i></span><span class="icon-select" data-theicon="pinterest-square"><i class="fa fa-pinterest-square"></i></span><span class="icon-select" data-theicon="qq"><i class="fa fa-qq"></i></span><span class="icon-select" data-theicon="ra"><i class="fa fa-ra"></i></span><span class="icon-select" data-theicon="rebel"><i class="fa fa-rebel"></i></span><span class="icon-select" data-theicon="reddit"><i class="fa fa-reddit"></i></span><span class="icon-select" data-theicon="reddit-square"><i class="fa fa-reddit-square"></i></span><span class="icon-select" data-theicon="renren"><i class="fa fa-renren"></i></span><span class="icon-select" data-theicon="share-alt"><i class="fa fa-share-alt"></i></span><span class="icon-select" data-theicon="share-alt-square"><i class="fa fa-share-alt-square"></i></span><span class="icon-select" data-theicon="skype"><i class="fa fa-skype"></i></span><span class="icon-select" data-theicon="slack"><i class="fa fa-slack"></i></span><span class="icon-select" data-theicon="slideshare"><i class="fa fa-slideshare"></i></span><span class="icon-select" data-theicon="soundcloud"><i class="fa fa-soundcloud"></i></span><span class="icon-select" data-theicon="spotify"><i class="fa fa-spotify"></i></span><span class="icon-select" data-theicon="stack-exchange"><i class="fa fa-stack-exchange"></i></span><span class="icon-select" data-theicon="stack-overflow"><i class="fa fa-stack-overflow"></i></span><span class="icon-select" data-theicon="steam"><i class="fa fa-steam"></i></span><span class="icon-select" data-theicon="steam-square"><i class="fa fa-steam-square"></i></span><span class="icon-select" data-theicon="stumbleupon"><i class="fa fa-stumbleupon"></i></span><span class="icon-select" data-theicon="stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i></span><span class="icon-select" data-theicon="tencent-weibo"><i class="fa fa-tencent-weibo"></i></span><span class="icon-select" data-theicon="trello"><i class="fa fa-trello"></i></span><span class="icon-select" data-theicon="tumblr"><i class="fa fa-tumblr"></i></span><span class="icon-select" data-theicon="tumblr-square"><i class="fa fa-tumblr-square"></i></span><span class="icon-select" data-theicon="twitch"><i class="fa fa-twitch"></i></span><span class="icon-select" data-theicon="twitter"><i class="fa fa-twitter"></i></span><span class="icon-select" data-theicon="twitter-square"><i class="fa fa-twitter-square"></i></span><span class="icon-select" data-theicon="vimeo-square"><i class="fa fa-vimeo-square"></i></span><span class="icon-select" data-theicon="vine"><i class="fa fa-vine"></i></span><span class="icon-select" data-theicon="vk"><i class="fa fa-vk"></i></span><span class="icon-select" data-theicon="wechat"><i class="fa fa-wechat"></i></span><span class="icon-select" data-theicon="weibo"><i class="fa fa-weibo"></i></span><span class="icon-select" data-theicon="weixin"><i class="fa fa-weixin"></i></span><span class="icon-select" data-theicon="windows"><i class="fa fa-windows"></i></span><span class="icon-select" data-theicon="wordpress"><i class="fa fa-wordpress"></i></span><span class="icon-select" data-theicon="xing"><i class="fa fa-xing"></i></span><span class="icon-select" data-theicon="xing-square"><i class="fa fa-xing-square"></i></span><span class="icon-select" data-theicon="yahoo"><i class="fa fa-yahoo"></i></span><span class="icon-select" data-theicon="yelp"><i class="fa fa-yelp"></i></span><span class="icon-select" data-theicon="youtube"><i class="fa fa-youtube"></i></span><span class="icon-select" data-theicon="youtube-play"><i class="fa fa-youtube-play"></i></span><span class="icon-select" data-theicon="youtube-square"><i class="fa fa-youtube-square"></i></span><span class="icon-select" data-theicon="ambulance"><i class="fa fa-ambulance"></i></span><span class="icon-select" data-theicon="h-square"><i class="fa fa-h-square"></i></span><span class="icon-select" data-theicon="hospital-o"><i class="fa fa-hospital-o"></i></span><span class="icon-select" data-theicon="medkit"><i class="fa fa-medkit"></i></span><span class="icon-select" data-theicon="plus-square"><i class="fa fa-plus-square"></i></span><span class="icon-select" data-theicon="stethoscope"><i class="fa fa-stethoscope"></i></span><span class="icon-select" data-theicon="user-md"><i class="fa fa-user-md"></i></span><span class="icon-select" data-theicon="wheelchair"><i class="fa fa-wheelchair"></i></span><span class="icon-select" data-theicon="flag"><i class="fa fa-flag"></i></span><span class="icon-select" data-theicon="maxcdn"><i class="fa fa-maxcdn"></i></span>
    </div>
</div>';

            $struct_item.='
</div>';


        }



        $struct_item.='<div class="dzs-tab-tobe">
            <div class="tab-menu with-tooltip">
            '.__("Position").'
            </div>
            <div class="tab-content">
            <div class="setting">
            <div class="setting-label">'.("Type").'</div>';
        $lab = 'position_type';
        $struct_item.=DZSHelpers::generate_select('bars['.$margs['index'].']['.$lab.']', array('options' => array('relative','absolute'), 'class' => 'styleme builder-field', 'seekval' => $margs[$lab]));

        $struct_item.='</div>
            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Width</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][width]" value="'.$margs['width'].'">
                </div>
                </div>
            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Height</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][height]" value="'.$margs['height'].'">
                </div>
                </div>
    <div class="clear"></div>


            <hr>
            <div class="one-half" style="float:none; margin: 0 auto;">
            <div class="setting">
            <div class="setting-label">Top</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][top]" value="'.$margs['top'].'">
                </div>
                </div>
    <div class="clear"></div>

            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Left</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][left]" value="'.$margs['left'].'">
                </div>
                </div>
            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Right</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][right]" value="'.$margs['right'].'">
                </div>
                </div>
    <div class="clear"></div>

            <div class="one-half" style="float:none; margin: 0 auto;">
            <div class="setting">
            <div class="setting-label">Bottom</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][bottom]" value="'.$margs['bottom'].'">
                </div>
                </div>
    <div class="clear"></div>

            <hr>
            <div class="one-half" style="float:none; margin: 0 auto;">
            <div class="setting">
            <div class="setting-label">Margin Top</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][margin_top]" value="'.$margs['margin_top'].'">
                </div>
                </div>
    <div class="clear"></div>
            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Margin Left</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][margin_left]" value="'.$margs['margin_left'].'">
                </div>
                </div>
            <div class="one-half">
            <div class="setting">
            <div class="setting-label">Margin Right</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][margin_right]" value="'.$margs['margin_right'].'">
                </div>
                </div>
    <div class="clear"></div>

            <div class="one-half" style="float:none; margin: 0 auto;">
            <div class="setting">
            <div class="setting-label">Margin Bottom</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][margin_bottom]" value="'.$margs['margin_bottom'].'">
                </div>
                </div>
    <div class="clear"></div>

            </div>


        </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                Styling
                </div>
                <div class="tab-content">
                <div class="setting ">
            <div class="setting-label">Background Color</div>
            <input class="builder-field with-colorpicker" type="text" name="bars[' . $margs['index'] . '][background_color]" value="'.$margs['background_color'].'"><span class="picker-con picker-left"><span class="the-icon"></span><span class="picker"></span></span>
                </div>
                <div class="setting type-text">
            <div class="setting-label">Color</div>
            <input class="builder-field with-colorpicker" type="text" name="bars[' . $margs['index'] . '][color]" value="'.$margs['color'].'"><span class="picker-con picker-left"><span class="the-icon"></span><span class="picker"></span></span>
                </div>
                <div class="setting type-circ">
            <div class="setting-label">Outer Circle Color</div>
            <input class="builder-field with-colorpicker" type="text" name="bars[' . $margs['index'] . '][circle_outside_fill]" value="'.$margs['circle_outside_fill'].'"><span class="picker-con picker-left"><span class="the-icon"></span><span class="picker"></span></span>
                </div>
                <div class="setting type-circ">
            <div class="setting-label">Inner Circle Color</div>
            <input class="builder-field with-colorpicker" type="text" name="bars[' . $margs['index'] . '][circle_inside_fill]" value="'.$margs['circle_inside_fill'].'"><span class="picker-con picker-left"><span class="the-icon"></span><span class="picker"></span></span>
                </div>
                
                <div class="setting type-circ">
            <div class="setting-label">Arc Percentage</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][circle_outer_width]" value="'.$margs['circle_outer_width'].'">
                </div>
                <div class="setting type-circ">
            <div class="setting-label">Outer Circle Width</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][circle_line_width]" value="'.$margs['circle_line_width'].'">
                </div>
                <div class="setting type-rect">
            <div class="setting-label">'.__("Border Radius").'</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][border_radius]" value="'.$margs['border_radius'].'">
                </div>
                <div class="setting">
            <div class="setting-label">'.__("Border").'</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][border]" value="'.$margs['border'].'">
                </div>
                <div class="setting">
            <div class="setting-label">'.__("Opacity").'</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][opacity]" value="'.$margs['opacity'].'">
                </div>
                <div class="setting">
            <div class="setting-label">'.__("Font Size").'</div>
            <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][font_size]" value="'.$margs['font_size'].'">
                <div class="jqueryui-slider for-fontsize"></div>
                </div>
                <div class="setting ">
                    <div class="setting-label">Extra Classes</div>
                    <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][extra_classes]" value="'.$margs['extra_classes'].'">
                </div>
                <!--
            <div class="setting type-text">
            <div class="setting-label">Text Align</div>';
        $lab = 'text_align';
        $struct_item.=DZSHelpers::generate_select('bars['.$margs['index'].']['.$lab.']', array('options' => array('left','center','right'), 'class' => 'styleme builder-field', 'seekval' => $margs[$lab]));

        $struct_item.='</div>
-->
                <br>
                </div>
            </div>
            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                Animation
                </div>
                <div class="tab-content">
                
                <div class="setting">
                    <div class="setting-label">Animation Brake</div>
                    <input class="builder-field" type="text" name="bars[' . $margs['index'] . '][animation_brake]" value="'.$margs['animation_brake'].'">
                    <div class="sidenote">'.__('Test', 'dzsprg').'</div>
                </div>
            
                </div>
            </div>

        </div>';

        $struct_item.='<a href="#" class="builder-layer--btn-delete">Delete Item</a>';
        $struct_item.='</div>';
        $struct_item.='</li>';
        return $struct_item;
    }


}
