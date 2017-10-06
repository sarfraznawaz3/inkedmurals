<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php include ('include/data.php'); ?>
<form action="admin.php?page=<?php echo $pluginname;?>" method="post">
	<div class="wowcolom">
		<div id="wow-leftcol">
			<input placeholder="Name for menu Item" type='text' name='title' value="<?php echo $title; ?>" class="wow-title"/>
			<div class="tab-box wow-admin">
				<ul class="tab-nav">
					<li><a href="#t1"><i class="fa fa-cog" aria-hidden="true"></i> Item</a></li>		
				</ul>
				<div class="tab-panels">
					<div id="t1">			
						<div class="itembox">
							<div class="item-title">
								<h3>Item</h3>									
							</div>
							<div class="inside" style="display: block;">
								<div class="wow-admin-col">
									<div class="wow-admin-col-6">Icon: custom <input disabled="disabled" type="checkbox" value="1" ><br/>
										<select name="menu_icon" id="font_icon">									
											<?php
												include_once ('icon.php');										
											?>
										</select>
										<input type="hidden" value="<?php echo $menu_icon; ?>" id="menu_icon">
									</div>
									<div class="wow-admin-col-6">
										Order:<br/>
										<input  placeholder="" type='text' name='menu_order' value="<?php if($menu_order == ''){ echo '0';} else{ echo $menu_order;} ?>" onkeyup="return proverka(this);" onchange="return proverka(this);" />
									</div>
								</div>
								<div class="wow-admin-col">
									<div class="wow-admin-col-6">
										Item type:<br/>
										<select name='menu_type' onchange="changetype();">        
											<option value="link" <?php if($menu_type=='link') { echo 'selected="selected"'; } ?>>link</option>
											<option value="block" <?php if($menu_type=='block') { echo 'selected="selected"'; } ?>>modal window</option>
										</select><br/>
										<div id="block_text" style="width:80%;">Make sure to set modal window to 'Click on a link or button' and 'All posts and pages</div>
									</div>
									<div class="wow-admin-col-6" id="menu_type_link"><?php esc_attr_e("Link", "wow-marketings") ?>:<br/>
										<input  placeholder="" type='text' name='menu_link' value="<?php echo $menu_link; ?>" />
									</div>
									<div class="wow-admin-col-6" id="menu_type_block"><?php esc_attr_e("Modal window ID", "wow-marketings") ?>:<br/>
										<input  placeholder="" type='text' name='menu_id' value="<?php echo $menu_id; ?>" /><br/>
										(<b><?php esc_attr_e("e.g.", "wow-marketings") ?></b>: wow-modal-id-1)
									</div>
								</div>
								<div class="wow-admin-col">
									<div class="wow-admin-col-4">	
										Font Color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/white.jpg">
									</div>
									<div class="wow-admin-col-4">	
										Icon Color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/white.jpg">
									</div>
									<div class="wow-admin-col-4">	
										Background <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/background.jpg">
									</div>									
								</div>
								<div class="wow-admin-col">
									<div class="wow-admin-col-4">	
										Background Hover <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/hbackground.jpg">
									</div>
									<div class="wow-admin-col-4"></div>
									<div class="wow-admin-col-4"></div>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>	
		<div id="wow-rightcol">
			<div class="wowbox">
				<h3>Publish</h3>
				<div class="wow-admin" style="display: block;">
					<div class="wow-admin" style="display: block;">
						<div class="wow-admin-col">
							<div class="wow-admin-col-6">
								<?php if ($id != ""){ echo '<p class="wowdel"><a href="admin.php?page='.$pluginname.'&info=del&did='.$id.'">Delete</a></p>';}; ?>
							</div>
							<div class="wow-admin-col-6 right">
								<p/>
								<input name="submit" id="submit" class="button button-primary" value="<?php echo $btn; ?>" type="submit">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="wowbox">
				<h3>Display</h3>
				<div class="inside wow-admin" style="display: block;">
					<div class="wow-admin-col wow-wrap">
						<div class="wow-admin-col-12">
							Show for users: <br/>
							<input type="radio" checked="checked"> All users <br />
							<input type="radio" disabled="disabled"> If a user logged in <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a><br />
							<input type="radio" disabled="disabled"> If user not logged <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> 
						</div>					
						<div class="wow-admin-col-12">
							<input type="checkbox" disabled="disabled"> Depending on the language <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
						</div>
						<div class="wow-admin-col-12">
							Show menu  <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
							<select disabled="disabled">
								<option value="shortecode">All posts and pages</option>
							</select>							
						</div>						
					</div>
				</div>
			</div>
			<div class="wowbox">
				<h3><i class="fa fa-star" style="color:#ffd400"></i> Review </h3>
				<div class="wow-admin wow-plugins">
					<p><a href="https://wordpress.org/plugins/side-menu/" target="_blank">Leave your rating and review</a> on the work of the plugin and get a 30% discount on all plugins on site https://wow-estore.com. <a href="admin.php?page=<?php echo $pluginname;?>&tool=pro" target="_blank">More information</a> <br/><br/>
						<em>Best Regards,<br/>
							<a href="https://wow-estore.com/author/admin/?author_downloads=true" target="_blank">Wow-Company Team</a><br/>
						<a href="mailto:support@wow-company.com">support@wow-company.com</a></em>
					</p>					
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="add" value="<?php echo $hidval; ?>" />    
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="data" value="<?php echo $data; ?>" />
	<input type="hidden" name="page" value="<?php echo $pluginname;?>" />	
	<input type="hidden" name="plugdir" value="<?php echo $pluginname;?>" />		
	<?php wp_nonce_field('wow_plugin_action','wow_plugin_nonce_field'); ?>	
</form> 