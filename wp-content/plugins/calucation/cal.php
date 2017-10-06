<?php
/*
Plugin Name: Plugin Cal
Plugin URI: 
Description: Creates a blank plugin template to activate and edit online. Plugins-->Plugin Maker
Version: 1.0
Author: v
Author URI:
*/

add_action('admin_menu', 'test_plugin_setup_menu');

 

function test_plugin_setup_menu(){

        add_menu_page( 'Test Plugin Page', 'calucation Plugin', 'manage_options', 'calucation-plugin', 'test_init' );

}

 

function test_init(){

//        test_handle_post();



?>

        <h1>Hello World!</h1>

        <h2>Upload a File</h2>

        <!-- Form to handle the upload - The enctype value here is very important -->

        <form  method="post" action="" name="calculationForm" id="calculationForm">

         <lable>Height</label>

         <select class="heightDropDown" name="height">

            <option value="11">11</option>

            <option value="12">12</option>

          

           <option value="14">14</option>

        </select> 

       <lable>Width</label>

      <select class="widthDropDown" name="width">

       <option value="11">11</option>

       <option value="12">12</option>

       <option value="14">14</option>

       

      </select> 

       <h3>Price:$ <span id="total_price">59.29</span></h3>

        </form>

	<script>

	jQuery(document).ready(function(){

	var heightVal = '';

	var widthtVal = '';

	jQuery(".heightDropDown").change(function(){

		heightVal = jQuery(this).val();

		jQuery.ajax({

                    url: "http://inkedvinyl.synergyframeworks.com/price_api.php",

                    type: 'POST',

                    data: jQuery("#calculationForm").serializeArray(),

                    async: false,

                    success: function (data) {
                      var result = JSON.parse(data);
                      // console.log(result[0].price);
                      jQuery("#total_price").html(result[0].price);
                    }

                });

	});

	jQuery(".widthDropDown").change(function(){

		widthtVal = jQuery(this).val();

                jQuery.ajax({

                    url: "http://inkedvinyl.synergyframeworks.com/price_api.php",

                    type: 'POST',

                    data: jQuery("#calculationForm").serializeArray(),

                    async: false,

                    success: function (data) {
                      var result = JSON.parse(data);
                      // console.log(result[0].price);
                      jQuery("#total_price").html(result[0].price);
                    }

                });

	});

});

</script>

<?php

}

 



?>
