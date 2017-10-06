<form method="post" name="send_modal" action="options.php" class="wowbox">
	<?php wp_nonce_field('update-options'); ?>
	<?php $options = get_option('style_side_menu'); ?>	
	<div class="itembox">
		<div class="item-title">
			<h3>Style</h3>									
		</div>
		<div class="inside" style="display: block;">								
			<div class="wow-admin-col">									
				<div class="wow-admin-col-4">
					Position:<br/>									
					<select name='style_side_menu[position]' onchange="changetype();">        
						<option value="left" <?php if($options['position']=='left') { echo 'selected="selected"'; } ?>>right</option>
						<option value="right" <?php if($options['position']=='right') { echo 'selected="selected"'; } ?>>left</option>        
					</select>								
				</div>
				<div class="wow-admin-col-4">
					Align <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<select disabled="disabled">				
						<option>Center</option>					
					</select>								
				</div>
				<div class="wow-admin-col-4">
					Margin <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="10" disabled="disabled" /> 								
				</div>
			</div>
			<div class="wow-admin-col">
				<div class="wow-admin-col-4">
					Item Width (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="240" disabled="disabled" />								
				</div>
				<div class="wow-admin-col-4">
					Item Height (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="400" disabled="disabled" />								
				</div>
				<div class="wow-admin-col-4">
					Gap between items (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="1" disabled="disabled" />								
				</div>
			</div>
			<div class="wow-admin-col">
				<div class="wow-admin-col-4">
					Font size (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="14" disabled="disabled" />								
				</div>
				<div class="wow-admin-col-4">
					Font style <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
					<select disabled="disabled">																	
						<option>Normal</option>	
					</select>																	
				</div>
				<div class="wow-admin-col-4">
					Font weight <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
					<select disabled="disabled">
						<option>Bold</option>					
					</select>																	
				</div>
			</div>
			<div class="wow-admin-col">
				<div class="wow-admin-col-4">
					Icon size (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="24" disabled="disabled" />								
				</div>
				<div class="wow-admin-col-4">
					Custom image width (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="32" disabled="disabled" />								
				</div>
				<div class="wow-admin-col-4">
					Border width (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="0" disabled="disabled" />								
				</div>
			</div>
			<div class="wow-admin-col">
				<div class="wow-admin-col-4">
					Border radius top (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="0" disabled="disabled" />
				</div>
				<div class="wow-admin-col-4">
					Border radius bottom (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>									
					<input type='text'  value="0" disabled="disabled" />									
				</div>
				<div class="wow-admin-col-4">
					Border color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>	
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/background.jpg">																
				</div>			
			</div>
		</div>
	</div>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="style_side_menu" />
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
</form>
