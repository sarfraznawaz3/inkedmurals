<?php
$output = '<img src="{{selected_image}}" class="{{css_class}}" width="150" height="150">';
return array(
		'label' =>'Image',
		'name' => 'image',
		'fields' => array(
					array(
						'type'	=>'upload',
						'name'  => "selected_image",
						'label' => "Select Image",
						'default'	=>'http://via.placeholder.com/150x150'
						),
				 	array(
			 			'type'	=>'text',
						'name'=>"css_class",
						'label'=>"Custom CSS Class",
						'default'=>''
						)
					),
		'front_template'=> $output 
);