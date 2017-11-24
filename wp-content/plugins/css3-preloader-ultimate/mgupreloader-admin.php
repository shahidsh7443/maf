<?php
/**
 * Admin setting class
 *
 * @author  MagniumThemes
 * @package magniumthemes.com
 */

if ( ! class_exists( 'mgupreloaderadmin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class mgupreloaderadmin {

		

		public function __construct() {

			//Actions
			add_action( 'admin_init', array( $this, 'init' ) );
			add_action( 'admin_menu', array( $this, 'admin_page_setup' ));

			// Updates
			add_action( 'admin_notices', array( $this, 'mgtup_show_admin_notice_update') );

			add_action( 'admin_notices', array( $this, 'show_admin_notice') );

			register_uninstall_hook( plugin_basename( __FILE__ ), array( 'mgupreloaderadmin', 'mgupreloader_uninstall' ) );

		}

		public function show_admin_notice() {
		    ?>
		    <div class="uwp-message error notice is-dismissible" style="display:none;">
		        <p><?php _e( '<strong>You are using FREE Version of Ultimate WordPress Preloader plugin without this additional features:</strong>', 'mgupreloader' ); ?></p>
		        <ul>	
		        	<li>- 53 animated CSS3 Preloader styles</li>
		        	<li>- 29 animated GIF Preloader styles</li>
		        	<li>- Preloader color changer (Unlimited colors)</li>
		        	<li>- Preloader background color changer (Unlimited colors)</li>
		        	<li>- Upload your own image to use as preloader</li>
		        	<li>- Detailed Documentation guide</li>
		        	<li>- Free Plugin updates and dedicated support</li>
		        </ul>
		    	<a style="margin:10px 0; display:block;" href="//www.bluehost.com/track/magniumthemes/uwp" target="_blank">
		        <img border="0" src="<?php echo plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR; ?>img/hosting-wp-button.png">
		        </a>
		        <a class="button-primary" style="margin-bottom: 10px;" href="http://codecanyon.net/item/ultimate-wordpress-preloader-53-css3-preloaders/12649265/?ref=dedalx" target="_blank">Update to PRO version to get premium features</a> 
		       
		    </div>

		                    
			<?php
			}

		/**
		 * Init method:
		 *  - default options
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function init() {
			
			if ( isset( $_REQUEST['page'] ) && 'mgupreloader_settings' == $_REQUEST['page'] && is_admin()) {
				/* register plugin settings */


			}
		}

		public function admin_page_setup() {

			add_options_page(__('Ultimate Preloader', 'mgupreloader'), __('Ultimate Preloader', 'mgupreloader'), 'manage_options', 'mgupreloader_settings', array( $this, 'mgupreloader_settings' ));

		}

		/**
		 * Register settings
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function mgupreloader_register_settings() {
			$mgupreloader_options_default = array(
				'enable' 		=> 0,
				'style'   		=> 'ball-8bits',
				'color'   		=> '#000000',
				'bg_color'		=> '#ffffff',
				'size'   		=> '',
				'image'   		=> 0,
				'image_url'   		=> ''
			);

			/* install the default plugin options */
            if ( ! get_option( 'mgupreloader_options' ) ){
                add_option( 'mgupreloader_options', $mgupreloader_options_default, '', 'yes' );
            }
		}

		public function mgupreloader_uninstall() {
			/* delete plugin options */
			delete_site_option( 'mgupreloader_options' );
			delete_option( 'mgupreloader_options' );
		}

		/**
		 * Print all plugin options.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function mgupreloader_settings() {

			global $mgupreloader_styles_list;

			$this->mgupreloader_register_settings();
			
			$display_add_options = $message = $error = $result = '';

			$mgupreloader_options = get_option( 'mgupreloader_options' );
	                
			if ( isset( $_POST['mgupreloader_form_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'mgupreloader_nonce_name' ) ) {	
				/* Update settings */
				$mgupreloader_options['enable'] = isset( $_POST['enable'] ) ? sanitize_text_field($_POST['enable']) : '0';
				$mgupreloader_options['style'] = isset( $_POST['style'] ) ? sanitize_text_field($_POST['style']) : 'ball-8bits';
				$mgupreloader_options['color'] = isset( $_POST['color'] ) ? sanitize_text_field($_POST['color']) : '#000000';
				$mgupreloader_options['bg_color'] = isset( $_POST['bg_color'] ) ? sanitize_text_field($_POST['bg_color']) : '#ffffff';
				
				$mgupreloader_options['size'] = isset( $_POST['size'] ) ? sanitize_text_field($_POST['size']) : 'normal';
				$mgupreloader_options['image'] = isset( $_POST['image'] ) ? sanitize_text_field($_POST['image']) : '0';
				$mgupreloader_options['image_url'] = isset( $_POST['image_url'] ) ? sanitize_text_field($_POST['image_url']) : '';

				
				/* Update settings in the database */
				if ( empty( $error ) ) {
					if($_POST['save_form'] == 1) {
						update_option( 'mgupreloader_options', $mgupreloader_options );
						$message .= __( "Settings saved.", 'mgupreloader' );	
					} else {
						$message .= __( "Preview updated. <a class='settings-form-submit-save'>Save new settings</a> or <a href=''>revert back</a>?", 'mgupreloader' );	
					}
					
				}
				else {
					$error .= " " . __( "Settings are not saved.", 'mgupreloader' );
				}
			} 

			// All available preloader sizes with names
			$mgupreloader_size_list['sm'] = 'Small';
			$mgupreloader_size_list['normal'] = 'Normal';
			$mgupreloader_size_list['2x'] = 'Large';
			$mgupreloader_size_list['3x'] = 'Extra large';

			// Build preloader preview
			$preloader_style = $mgupreloader_options['style'];
			$preloder_inline_html = $mgupreloader_styles_list[$preloader_style];

			if($mgupreloader_options['image'] == 1) {
				$preloader_html = '<div><img src="'.esc_url($mgupreloader_options['image_url']).'" alt="'.__( "Loading...", 'mgupreloader' ).'"/></div>';
			} else {
				$preloader_html = '<div style="color: '.$mgupreloader_options['color'].'" class="la-'.$mgupreloader_options['style'].' la-'.$mgupreloader_options['size'].'">'.$preloder_inline_html.'</div>';
			}

			?>
			<div class="mgupreloader-settings wrap" id="mgupreloader-settings">
				<div id="icon-options-general" class="icon32 icon32-bws"></div>
				<h2><?php _e( "Ultimate WordPress Preloader Light", 'mgupreloader' ); ?></h2>
				<br>
				<a href="http://themeforest.net/user/dedalx/portfolio/" target="blank" class="button button-secondary">Our Premium Themes</a> <a href="http://codecanyon.net/collections/5208381-premium-wordpress-plugins" target="blank" class="button button-secondary">Our other Ultimate Plugins</a> <a href="http://codecanyon.net/item/ultimate-wordpress-preloader-53-css3-preloaders/12649265/?ref=dedalx" target="blank" class="button button-primary">Purchase PRO version</a><br><br>
				<div class="updated fade" <?php if( empty( $message ) ) echo "style=\"display:none\""; ?>>
					<p><strong><?php echo $message; ?></strong></p>
				</div>
				<div class="error" <?php if ( empty( $error ) ) echo "style=\"display:none\""; ?>>
					<p><strong><?php echo $error; ?></strong></p>
				</div>
				<div id="mgupreloader-settings-notice" class="updated fade" style="display:none">
					<p><strong><?php _e( "Notice:", 'mgupreloader' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'mgupreloader' ); ?></p>
				</div>
				<div class="preloader-settings-column">
				<h3><?php _e( 'General Settings', 'mgupreloader' ); ?></h3>
				<form id="mgupreloader_settings_form" method="post" action="">	
					<input type="hidden" name="save_form" id="save_form" value="1"/>			
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( "Enable Preloader", 'mgupreloader' ); ?></th>
							<td>
								<?php if(isset($mgupreloader_options['enable']) && $mgupreloader_options['enable'] == 1) {
									$enable_preloader = ' checked';
								} else {
									$enable_preloader = '';
								}
								?>
								<label><input type="checkbox" name="enable" value="1"<?php echo esc_attr($enable_preloader); ?>/> 
								<span><?php _e( "Enable preloader on your site.", 'mgupreloader' ); ?></span></label>
						</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( "Preloader style", 'mgupreloader' ); ?></th>
							<td>
								
								<select name="style" id="mgtup_change_style">
								    <?php
								    $i = 0;
								    foreach ($mgupreloader_styles_list as $style => $inner_html) {
								    	if($style == $mgupreloader_options['style']) {
								    		$style_selected = ' selected';
								    	} else {
								    		$style_selected = '';
								    	}
								    	echo '<option data-id="'.esc_attr($i).'" value="'.$style.'"'.$style_selected.'>'.ucfirst(str_replace('-', ' ', $style)).'</option>';
								    	$i++;
								    }
								    ?>
							    </select><a class="button button-secondary link-smooth-scroll" href="#preloader-preview-styles"><?php _e( "Select from table", 'mgupreloader' ); ?></a><br/>
								<span  class="mgupreloader_info"><?php _e( "Click <a class='settings-form-submit-nosave'>Update preview</a> after you changed style here.", 'mgupreloader' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( "Preloader size", 'mgupreloader' ); ?></th>
							<td>
								
								<select name="size" id="mgtup_change_size">
								    <?php
								    foreach ($mgupreloader_size_list as $size => $size_name) {
								    	if($size == $mgupreloader_options['size']) {
								    		$size_selected = ' selected';
								    	} else {
								    		$size_selected = '';
								    	}
								    	echo '<option value="'.$size.'"'.$size_selected.'>'.$size_name.'</option>';
								    }
								    ?>
							    </select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( "Preloader color", 'mgupreloader' ); ?></th>
							<td>
								
								<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/color_black.png';?>" alt="Color picker" class="color-picker"/>
								
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( "Preloader background color", 'mgupreloader' ); ?></th>
							<td>
								
								<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/color_white.png';?>" alt="Color picker" class="color-picker"/>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e( "Use image instead of CSS3 animated preloader", 'mgupreloader' ); ?></th>
							<td>
								<?php if(isset($mgupreloader_options['image']) && $mgupreloader_options['image'] == 1) {
									$image_preloader = ' checked';
								} else {
									$image_preloader = '';
								}
								?>
								<label><input class="image-preloader-selector" type="checkbox" name="image" value="1"<?php echo esc_attr($image_preloader); ?>/>
								<span><?php _e( "Replace preloader with your image.", 'mgupreloader' ); ?></span></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( "Preloader image url", 'mgupreloader' ); ?></th>
							<td>
								<input disabled type="text" name="image_url" id="image_url" value="<?php echo esc_attr($mgupreloader_options['image_url']); ?>"/><input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
								<br/><span  class="mgupreloader_info"><?php _e( "Full path to image that will be used in preloader instead of CSS3 animation.", 'mgupreloader' ); ?></span>
							</td>
						</tr>
						
					</table>
					<p class="submit">
						<input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Save Changes', 'mgupreloader' ) ?>" />
						
						<input type="hidden" name="mgupreloader_form_submit" value="submit" />
						<?php wp_nonce_field( plugin_basename( __FILE__ ), 'mgupreloader_nonce_name' ); ?>
					</p>				
				</form>
				</div>
				<div class="preloader-preview-column">
				<h3><?php _e( 'Preloader preview', 'mgupreloader' ); ?></h3>
				<input type="submit" id="settings-form-submit-nosave" class="button-secondary" value="<?php _e( 'Update preview', 'mgupreloader' ) ?>" /> <input type="submit" id="settings-form-submit-save" class="button-primary settings-form-submit-save" value="<?php _e( 'Save Changes', 'mgupreloader' ) ?>" />
				<div class="preloader-preview-wrapper">

					<div class="mask-admin" style="background-color: <?php echo $mgupreloader_options['bg_color']; ?>">
						<div id="mgtup-preloader">
						<?php echo wp_kses_post($preloader_html); ?>
						</div>
					</div>
				</div>
				</div>
				<div class="clear"></div>
				<div id="preloader-preview-styles" class="preloader-preview-styles">
				<h3><?php _e( 'Preloader styles table - <a href="http://codecanyon.net/item/ultimate-wordpress-preloader-53-css3-preloaders/12649265/?ref=dedalx" target="_blank">Purchase PRO version</a> to unlock 53 preloader styles', 'mgupreloader' ); ?></h3>
				<p><?php _e( 'Click style that you like to select it in settings.', 'mgupreloader' ); ?></p>
				<?php 
				$i = 0;
				foreach ($mgupreloader_styles_list as $style => $inner_html) {
					echo '<div class="preview-style-wrapper" data-id="'.esc_attr($i).'"><span>'.ucfirst(str_replace('-', ' ', $style)).'</span><div class="preview-style-wrapper-inner"><div class="la-'.$style.' la-dark">'.$inner_html.'</div></div></div>';
					$i++;
				}
				?>
				<div class="clear"></div>
				</div>
				<a class="button button-primary button-hero" href="http://themes.magniumthemes.com/?theme=wpup" style="display: table; margin: 30px auto" target="blank"><?php _e( "Check all 53 available styles in PRO version", 'mgupreloader' ); ?></a>
				<br/>
				<a class="button button-secondary link-smooth-scroll" href="#mgupreloader-settings"><?php _e( "Back to settings", 'mgupreloader' ); ?></a>
			</div>
			<?php
		}

		public function mgtup_show_admin_notice_update() {
			global $current_user;
			$user_id = $current_user->ID;

			if ( ! get_user_meta($user_id, 'mgtup_hind_update_message_ignore') && ( current_user_can( 'install_plugins' ) ) ):
		    ?>
		    <div class="updated below-h2">
				<a href="<?php echo esc_url( add_query_arg( 'mgtup_update_message_dismiss', '0' ) ); ?>" style="float: right;padding-top: 9px;">(never show this message again)&nbsp;&nbsp;<b>X</b></a><p style="display: inline-block;">Hi! Would you like to receive Ultimate WordPress Preloader updates news & get premium support? Subscribe to email notifications: </p>
				<form style="display: inline-block;" action="//magniumthemes.us8.list-manage.com/subscribe/post?u=6ff051d919df7a7fc1c84e4ad&amp;id=9285b358e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				   <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Your email">
				   <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
				</form>
		    </div>
		    <?php
			endif;
		}
		

		public function mgtup_update_message_dismiss() {
			global $current_user;
		    $user_id = $current_user->ID;
		    /* If user clicks to ignore the notice, add that to their user meta */
		    if ( isset($_GET['mgtup_update_message_dismiss']) && '0' == $_GET['mgtup_update_message_dismiss'] ) {
			    add_user_meta($user_id, 'mgtup_hind_update_message_ignore', 'true', true);
			}
		}

	}
}
