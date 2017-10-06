<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php include ('include/data.php'); ?>
<form action="admin.php?page=<?php echo $pluginname;?>" method="post" id="addtag">
	<div class="wowcolom">
		<div id="wow-leftcol">
			<input  placeholder="Name is used only for admin purposes" type='text' name='title' value="<?php echo $title; ?>" class="wow-title"/>
			<div class="tab-box wow-admin">
				<ul class="tab-nav">
					<li><a href="#t1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Content</a></li>
					<li><a href="#t2"><i class="fa fa-superscript" aria-hidden="true"></i> Variables</a></li>  
					<li><a href="#t3"><i class="fa fa-css3" aria-hidden="true"></i> Style</a></li>
					<li><a href="#t4"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
				</ul>
				<div class="tab-panels">
					<div id="t1">
						<div class="wow-admin-col wow-wrap">
							<div class="wow-admin-col-12">
								Title:<br/>
								<input type='text'  placeholder="New Order" name='herd_title' value="<?php echo $herd_title; ?>"/>	
							</div>
							<div class="wow-admin-col-12">
								Icon: custom <input name="herd_custom" id="herd_custom" type="checkbox" value="1" <?php if($herd_custom=='1') { echo 'checked="checked"'; } ?> onclick="itemcustom();"><br/>
								<div id="iconstandart">
									<select id="font_icon" name="menu_icon">
										<?php
											include_once ('icon.php');										
										?> </select>
										<input type="hidden" value="<?php echo $menu_icon; ?>" id="menu_icon">	
								</div>
								<div id="herd_custom_block">
									<input  placeholder="image link" type='text' name='herd_custom_link' value="<?php echo $herd_custom_link; ?>" />									
								</div>
							</div>
							<div class="wow-admin-col-12">
								Text:<br/>
								<textarea name='herd_text'><?php echo $herd_text; ?></textarea><br/>
								Use [variables] to construct your herd effect message
							</div>	
						</div>
					</div>
					<div id="t2">
						<div class="wow-admin-col wow-wrap">
							<div class="wow-admin-col-12">
								Names:<br/>
								<textarea name='herd_name'><?php echo $herd_name; ?></textarea>	<br/>
								Enter Names, separated by comma
							</div>
							<div class="wow-admin-col-12">
								Cities:<br/>
								<textarea name='herd_city'><?php echo $herd_city; ?></textarea>	<br/>
								Enter Cities, separated by comma
							</div>   
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-6">
								Amount min:<br/>
								<input type='text' name='amount_min' value="<?php echo $amount_min;?>"/>
							</div>
							<div class="wow-admin-col-6">
								Amount max:<br/>
								<input type='text' name='amount_max' value="<?php echo $amount_max;?>"/>
							</div>
						</div>
					</div>
					<div id="t3">
						<div class="wow-admin-col">	
							<div class="wow-admin-col-4">
								Border radius (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="0" disabled="disabled"/> 
							</div>
							<div class="wow-admin-col-4">
								Border width (px) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="0" disabled="disabled"/> 
							</div>
							<div class="wow-admin-col-4">
								Top position (%) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>: <input type="checkbox" value="1" disabled="disabled" checked="checked"><br/>
								<input type='text'  placeholder="10" disabled="disabled"/> 
							</div>
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-4">
								Bottom position (%): <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>: <input type="checkbox" value="1" disabled="disabled"><br/>
								<input type='text'  placeholder="0" disabled="disabled"/>
							</div>
							<div class="wow-admin-col-4">
								Left position (%): <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>: <input type="checkbox" value="1" disabled="disabled"><br/>
								<input type='text'  placeholder="0" disabled="disabled"/>
							</div>
							<div class="wow-admin-col-4">
								Right position (%): <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>: <input type="checkbox" value="1" disabled="disabled" checked="checked"><br/>
								<input type='text'  placeholder="3" disabled="disabled" />
							</div>	 
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-4">
								Background color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/background.jpg">
								
							</div>	 
							<div class="wow-admin-col-4">
								Border color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/> 
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/border.jpg">
								
							</div>
							<div class="wow-admin-col-4">
								Text color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/> 
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/white.jpg">
							</div>
						</div>	
						<div class="wow-admin-col">
							<div class="wow-admin-col-4">
								Icon color <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>	
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/white.jpg">
							</div>	 
							<div class="wow-admin-col-4"></div>
							<div class="wow-admin-col-4"></div>
						</div>
					</div>	
					<div id="t4">
						<div class="wow-admin-col">
							<div class="wow-admin-col-4">
								Appearance mode:<br/>
								<select disabled="disabled">
									<option>stable</option>		
								</select>
							</div>
							<div class="wow-admin-col-4 stable_step">
								Show every (sec):<br/>
								<input type='text' name='speed' value="<?php echo $speed;?>"/>
							</div>
							<div class="wow-admin-col-4"></div>
						</div>
						<div class="wow-admin-col"> 								
							<div class="wow-admin-col-4">
								Auto-close (sec) <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="7" disabled="disabled"/> 
							</div>
							<div class="wow-admin-col-4">
								Number of notifications <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="" disabled="disabled"/> 
							</div>
							<div class="wow-admin-col-4"></div>
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-6">
								Animate In <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<select disabled="disabled">
									<option>bounceIn</option>		
								</select>
							</div>
							<div class="wow-admin-col-6">
								Animate Out <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<select disabled="disabled">
									<option>bounceOut</option>		
								</select>
							</div>
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-12">
								Notification link <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>: <input type="checkbox" disabled="disabled">
							</div>
						</div>
						<div class="wow-admin-col">
							<div class="wow-admin-col-6">
								Time from <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="0" disabled="disabled"/>
							</div>
							<div class="wow-admin-col-6">
								Time to <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
								<input type='text'  placeholder="23" disabled="disabled"/>
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
							<input name="mobil_show" type="checkbox" value="1" <?php if(!empty($mobil_show)) { echo 'checked="checked"'; }; ?> onclick="wpscreen();"> Don’t show on screens less than (px)<br/>
							<div style="display:none;" id="screen">								
								<input type="text" name="screen" value="<?php echo $screen; ?>" /> 
							</div>
						</div>
						<div class="wow-admin-col-12">
							<input type="checkbox" disabled="disabled"> Depending on the language <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>
						</div>
						<div class="wow-admin-col-12">
							Show herd effect <a href='admin.php?page=<?php echo $pluginname;?>&tool=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a>:<br/>
							<select disabled="disabled">
								<option value="shortecode">Where shortcode is inserted</option>
							</select>							
						</div>
						<div class="wow-admin-col-12">
							<b>[Wow-Herd-Effects  id=<?php echo $id; ?>]</b>
						</div>
					</div>
				</div>
			</div>
			<div class="wowbox">
				<h3><i class="fa fa-star" style="color:#ffd400"></i> Review </h3>
				<div class="wow-admin wow-plugins">
					<p><a href="https://wordpress.org/plugins/float-menu/" target="_blank">Leave your rating and review</a> on the work of the plugin and get a 30% discount on all plugins on site https://wow-estore.com. <a href="admin.php?page=<?php echo $pluginname;?>&tool=pro" target="_blank">More information</a> <br/><br/>
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