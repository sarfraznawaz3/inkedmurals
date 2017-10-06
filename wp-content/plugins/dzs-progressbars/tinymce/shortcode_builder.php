<?php

function dzsprg_shortcode_builder_output_selector(){

    global $dzsprg;

    ?>
    <div class="skins-container ">

        <div class="skins-all-models active">
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
                    foreach ($dzsprg->db_skins as $lab=>$skin) {


                        // -- tbc
//                                                    break;
                        $skin = $lab;

                        $arr = $dzsprg->get_skin($skin);


//                                            print_r($arr);

                        $cat = '';

                        if(isset($arr['bars']['mainsettings']['category'])){
                            $cat = $arr['bars']['mainsettings']['category'];
                        }
                        if(isset($arr['bars'])){

                            ?>
                        <a class="zfolio-item from-generator" data-category="<?php echo $cat; ?>"  style="display:block; " href="<?php echo DZSHelpers::add_query_arg( array(
                            'skin' => $skin,
                        ), dzs_curr_url() ); ?>">
                            <div class="zfolio-item--inner">
                                <div class="dzsprg-container the-feature-con "><?php

                                    echo $dzsprg->output_progress_bars($arr, array(
                                        'auto-init' => 'off',
                                    ));


                                    ?>
                                </div>

                                <div class="item-meta">
                                    <div class="the-title"><?php echo $skin; ?></div>
                                </div>

<!--                                <i class="delete-skin-btn fa fa-times-circle" aria-hidden="true" data-lab="" data-nonce="--><?php //echo wp_create_nonce('dzsprg_lab_'.$skin); ?><!--"></i>-->
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



    </div><?php

}

function dzsprg_shortcode_builder(){
    global $dzsprg;


    $curr_skin = '';
    $custom_args = array();
    $has_content = false;

    // -- get from shortcode sel parameter

    if(isset($_GET['skin']) && $_GET['skin']){

$curr_skin = $_GET['skin'];
    }


    if($curr_skin){

    $skin_data = $dzsprg->get_skin($curr_skin);


//    print_r($skin_data);

    $skin_data_json = json_encode($skin_data);


//    echo '$skin_data_json - '.$skin_data_json.'|||';



    if(strpos($skin_data_json, '{{arg3')!==false){

        $has_content = true;
    }


    preg_match_all("/{{arg(.{0,1})-default(.*?)}}/", $skin_data_json, $output_array);


    foreach ($output_array[0] as $lab => $oai){


//        echo $output_array[1][$lab].',';

        if($output_array[1][$lab] && intval($output_array[1][$lab])>3){
//            echo $output_array[1][$lab].',';
            $aux = array(
                    'index'=>$output_array[1][$lab],
                    'type'=>'text',
                    'default'=>$output_array[2][$lab],
            );

            if(strpos($output_array[2][$lab],'#')===0){


                $aux['type']='colorpicker';

            }

            array_push($custom_args, $aux);
        }

    }

//    print_r($custom_args);
    }

?>
<script>
    window.dzsprg_custom_args = [<?php
            $i3=0;
    foreach ($custom_args as $ca){
        if($i3){
            echo ',';
        }
        echo '"arg'.$ca['index'].'"';

        $i3++;
    }
        ?>];
</script>
<div class="maincon">

    <h2>DZS <?php echo __('Progress Bars Generator','dzsvg'); ?></h2>

    <input type="hidden" class="textinput small-input" name="skin" value="<?php echo $curr_skin; ?>"/>


    <?php
    if($curr_skin){

        ?>
        <div class="preview-con">
        <div class="preview-inner">

            <iframe src=""></iframe>
        </div>
        </div>




        <div class="setting type_any">
            <h6 class="setting-label"><?php echo __('Percent'); ?></h6>
            <input type="text" class="textinput small-input" name="arg1_perc" value="100"/>
            <div class="sidenote"><?php echo __(''); ?></div>
        </div>
        <div class="setting type_any">
            <h6 class="setting-label"><?php echo __('Percent Number'); ?></h6>
            <input type="text" class="textinput small-input" name="arg2_maxnr" value="100"/>
            <div class="sidenote"><?php echo __(''); ?></div>
        </div>

        <?php
        if($has_content){
        ?>
        <div class="setting type_any">
            <h6 class="setting-label"><?php echo __('Content'); ?></h6>
            <!--        <textarea class="textinput medium-textarea with-tinymce" name="content" ></textarea>-->

            <?php

            $content = '';
            $editor_id = 'content';


            $args = array(
                'media_buttons' => true,
                'drag_drop_upload' => true,
            );

            wp_editor( $content, $editor_id, $args );

            ?>
            <div class="sidenote"><?php echo __(''); ?></div>
        </div>



            <?php

        }



        $used_indexes = array();
        foreach ($custom_args as $ca){

            if(in_array($ca['index'],$used_indexes)){
                continue;
            }
            array_push($used_indexes, $ca['index']);
            ?>
        <div class="setting type_any">
            <h6 class="setting-label"><?php echo __('Custom Parameter'); echo ' '.$ca['index']; ?></h6><?php

            $type = $ca['type'];

            if($type=='text'){

                echo DZSHelpers::generate_input_text('arg'.$ca['index'], array(
                    'class'=>'textinput',
                    'type'=>'text',
                    'seekval'=>$ca['default'],
                ));

            }
            if($type=='colorpicker'){

                ?><div class="flex-for-input-with-colorpicker"><?php

                echo DZSHelpers::generate_input_text('arg'.$ca['index'], array(
'class'=>'textinput',
'type'=>'colorpicker',
'seekval'=>$ca['default'],
                ));

                ?></div><?php
            }


            ?><div class="sidenote"><?php echo __('this is a custom parameter that was set in the builder'); ?></div></div><?php
        }
            ?>









        <p><span style="display:inline-block; text-align: center;" class=" button-primary btn-master-generate"><?php echo __("Generate"); ?></span></p>
        <div class="clear"></div>

        <div class="output-div"></div>

        <?php
    }else{
        dzsprg_shortcode_builder_output_selector();
    }
    ?>



    <?php
    if($curr_skin){
        ?><br><h2><?php echo __("Select other skin"); ?></h2><?php
        dzsprg_shortcode_builder_output_selector();

    }else{
    }
    ?>
</div>
<?php




}