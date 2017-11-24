<?php
/*
Plugin Name: TheGem Import
Plugin URI: http://codex-themes.com/thegem/
Author: Codex Themes
Version: 3.0.3.1
Author URI: http://codex-themes.com/thegem/
*/


include_once ('inc/parse_content.php');
include_once ('inc/import-widgets.php');
include_once ('inc/easy-mailchimp-import.php');

function thegem_import_get_purchase() {
	if(!defined('ENVATO_HOSTED_SITE')) {
		$theme_options = get_option('thegem_theme_options');
		if($theme_options && isset($theme_options['purchase_code'])) {
			return $theme_options['purchase_code'];
		}
	} else {
		return 'envato_hosted:'.(defined('SUBSCRIPTION_CODE') ? SUBSCRIPTION_CODE : '');
	}
	return false;
}

if(!function_exists('thegem_is_plugin_active')) {
	function thegem_is_plugin_active($plugin) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		return is_plugin_active($plugin);
	}
}

add_action('admin_menu', 'thegem_import_submenu_page');
function thegem_import_submenu_page() {
	add_menu_page( 'TheGem Import', 'TheGem Import', 'manage_options', 'thegem-import-submenu-page', 'thegem_import_page', '', 81 );
}

function thegem_import_page() {
	$packs = array(
		'agencies' => array(
			'title' => 'Agencies',
			'pics' => array(1, 2, 3, 4, 5, 6, 7)
		),
		'apps' => array(
			'title' => 'Apps',
			'pics' => array(1, 2)
		),
		'beauty' => array(
			'title' => 'Beauty',
			'pics' => array(1, 2)
		),
		'blog' => array(
			'title' => 'Blogs',
			'pics' => array(1, 2, 3, 4)
		),
		'business' => array(
			'title' => 'Business',
			'pics' => array(1, 2, 3, 4, 5, 6)
		),
		'church' => array(
			'title' => 'Church',
			'pics' => array(1, 2)
		),
		'coming-soon' => array(
			'title' => 'Coming Soon',
			'pics' => array(1, 2, 3, 4, 5, 6)
		),
		'architecture' => array(
			'title' => 'Construction, Architecture, Real Estate',
			'pics' => array(1,2,3)
		),
		'creative' => array(
			'title' => 'Creative',
			'pics' => array(1, 2, 3)
		),
		'events' => array(
			'title' => 'Events',
			'pics' => array(1, 2)
		),
		'gym' => array(
			'title' => 'Gym',
			'pics' => array(1, 2)
		),
		'hotels' => array(
			'title' => 'Hotels',
			'pics' => array(1, 2)
		),
		'landings' => array(
			'title' => 'Landing Pages',
			'pics' => array(1, 2, 3, 4, 5)
		),
		'lawyer' => array(
			'title' => 'Lawyers',
			'pics' => array(1, 2)
		),
		'logistics' => array(
			'title' => 'Logistics & Transportation',
			'pics' => array(1, 2)
		),
		'medical' => array(
			'title' => 'Medical',
			'pics' => array(1, 2)
		),
		'photography' => array(
			'title' => 'Photography',
			'pics' => array(1, 2)
		),
		'portfolios' => array(
			'title' => 'Portfolios',
			'pics' => array(1, 2, 3, 4)
		),
		'restaurant' => array(
			'title' => 'Restaurant',
			'pics' => array(1, 2)
		),
		'shopdemos' => array(
			'title' => 'Shop (Fashion)',
			'message' => 'Please be sure you have already installed <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=woocommerce').'" target="_blank">WooCommerce plugin</a> before importing this shop demo concepts.',
			'pics' => array(1, 2, 3, 4, 5, 6, 7, 8)
		),
		'shop_grids' => array(
			'title' => 'Shops (Creative Grids)',
			'message' => 'Please be sure you have already installed <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=woocommerce').'" target="_blank">WooCommerce plugin</a> before importing this shop demo concepts.',
			'multi' => array(
				'creative-shop' => array(
					'pics' => array(1)
				),
				'shop-metro' => array(
					'pics' => array(1)
				),
				'nature-shop' => array(
					'pics' => array(1)
				),
				'shop-landing' => array(
					'pics' => array(1)
				),
				'shop-masonry' => array(
					'pics' => array(1)
				),
				'shop-justified' => array(
					'pics' => array(1)
				),
			)
		),
	);
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2>TheGem Import</h2>
	<?php if(thegem_is_plugin_active('wordpress-importer/wordpress-importer.php')) : ?>
		<p><?php printf(__('It seems that Wordpress Import Plugin is active. Please deactivate Wordpress Import Plugin on <a href="%s">plugins page</a> to proceed with import of TheGem\'s main demo content.'), admin_url('plugins.php')); ?></p>
	<?php elseif(get_template() != 'thegem') : ?>
		<p><?php _e('Your current activated theme in not TheGem main parent theme. Please note, that this import works only with TheGem main parent theme. Please activate TheGem main parent theme before proceeding with import.'); ?></p>
	<?php elseif(!thegem_is_plugin_active('thegem-elements/thegem-elements.php')) : ?>
		<p><?php _e('Plugin "TheGem Theme Elements" is not active.'); ?></p>
	<?php elseif(!thegem_import_get_purchase()) : ?>
		<?php if(!defined('ENVATO_HOSTED_SITE')) : ?>
			<p><?php printf(__('Please enter purchase code in <a href="%s">Theme options</a>'), admin_url('themes.php?page=options-framework#activation')); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<div class="thegem-import-prevent-message"><?php printf(__('The import of demo media works best on a new installation of WordPress. If you have already an existing WordPress installation, we recommend you to use "<a href="%s" target="_blank">Reset WP</a>" plugin to reset your media database and content.'), esc_url('https://wordpress.org/plugins/reset-wp/')); ?></div>
		<div class="thegem-import-output ui-no-theme">
			<div class="import-variants">

				<div id="pack-import" class="import-variant">
					<h3><?php _e('Import of selected demo concepts'); ?></h3>
					<div class="inside"><div>
							<p><?php _e('Lightweighted import option. Here you can select one purpose topic for demo import. It is useful if you are interested only in one special puprose (for example only business homepages) and don\'t need any other demos. This import will install only homepages of the selected topic as well as demo pages from "Pages" category of our demo website (About Us, Services etc.) and demo pages from "Elements" category (to give you all examples of how to use different shortcodes and elements). Please note: this import works best on a new intall of WordPress.'); ?></p>
							<div class="import-tabs">
								<ul class="clearfix">
									<?php foreach($packs as $pack => $content) : ?>
										<li><a href="#import-tab-<?php echo $pack; ?>"><?php echo $content['title']; ?></a></li>
									<?php endforeach; ?>
								</ul>
								<div class="import-tabs-content">
									<?php foreach($packs as $pack => $content) : ?>
										<div class="import-tab" id="import-tab-<?php echo $pack; ?>">
											<?php if(!isset($content['multi'])) : ?>
											<?php if(isset($content['message'])) : ?>
												<div class="import-pack-message"><?php echo $content['message']; ?></div>
											<?php endif; ?>
												<div class="import-pack-pics">
													<?php foreach($content['pics'] as $pic) : ?>
														<div class="import-pack-pic"><img src="<?php echo plugins_url( '/images/previews/'.$pack.'/'.$pic.'.jpg' , __FILE__ ) ?>" alt="#" /></div>
													<?php endforeach; ?>
												</div>
												<button class="import-button button-primary" data-import-part="full" data-import-pack="<?php echo $pack; ?>"><?php _e('Import'); ?></button>
											<?php else : ?>
											<?php if(isset($content['message'])) : ?>
												<div class="import-pack-message"><?php echo $content['message']; ?></div>
											<?php endif; ?>
												<div class="import-pack-pics multi-pack">
													<?php foreach($content['multi'] as $mpack => $pic) : ?>
														<div class="import-pack-pic">
															<img src="<?php echo plugins_url( '/images/previews/'.$mpack.'/'.$pic['pics'][0].'.jpg' , __FILE__ ) ?>" alt="#" /><br />
															<button class="import-button button-primary" data-import-part="full" data-import-pack="<?php echo $mpack; ?>"><?php _e('Import'); ?></button>
														</div>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
					</div></div>
				</div>

				<div id="singles-import" class="import-variant">
					<h3><?php _e('Import of single pages, posts etc.'); ?></h3>
					<div class="inside">
						<div>
                            <?php

                            $file = plugins_url('/demo-files/content_singles.json', __FILE__);
                            if($file_content = file_get_contents($file)) {
                                $singles_content = json_decode($file_content, true);

                                $homepage_array = sort_array_for_single_item($singles_content['homepages']);
                                $page_array = sort_array_for_single_item($singles_content['pages']);
                                $post_array = sort_array_for_single_item($singles_content['posts']);
                                $portfolio_array = sort_array_for_single_item($singles_content['portfolios']);
                                $product_array = sort_array_for_single_item($singles_content['products']);

                                $is_woocommerce = class_exists( 'WooCommerce' );

                                function get_single_item_elem($array, $name) {
                                    echo '<ul>';
                                    foreach ($array as $item) {
                                        if(!empty($item['children'])) {
                                            echo '<li><div class="import-single-item-parent">'.$item['parent'].'</div>';
                                            get_single_item_elem($item['children'], $name);
                                            echo '</li>';
                                        } else {
                                            echo '<li><div class="import-single-item-elem">';
                                            echo '<input name="'.$name.'[]" value="'.implode(',', $item['ids']).'" data-id="'.$item['id'].'" data-src="'.plugins_url('/images/thumbs/'.$name.'/'.$item['id'].'.jpg' , __FILE__ ).'" type="checkbox">';
                                            $title = $name=='homepage' ? str_replace('Homepage:','',$item['title']) : $item['title'];
                                            echo '<div class="import-single-item-elem-title">'.trim($title).'</div>';
                                            echo '</div></li>';
                                        }
                                    }
                                    echo '</ul>';
                                }

                                ?>

                                <form class="single-import-form import-singles">
                                    <div class="import-singles-box">
                                        <div class="import-single-col">
                                            <div class="import-single-col-title">Homepages</div>
                                            <div class="import-single-col-body homepages">
                                                <?php get_single_item_elem($homepage_array, 'homepage'); ?>
                                            </div>
                                        </div>
                                        <div class="import-single-col">
                                            <div class="import-single-col-title">Pages</div>
                                            <div class="import-single-col-body pages">
                                                <?php get_single_item_elem($page_array, 'page'); ?>
                                            </div>
                                        </div>
                                        <div class="import-single-col">
                                            <div class="import-single-col-title">Posts</div>
                                            <div class="import-single-col-body posts">
                                                <?php get_single_item_elem($post_array, 'post'); ?>
                                            </div>
                                        </div>
                                        <div class="import-single-col">
                                            <div class="import-single-col-title">Portfolio</div>
                                            <div class="import-single-col-body portfolios">
                                                <?php get_single_item_elem($portfolio_array, 'portfolio'); ?>
                                            </div>
                                        </div>
                                        <?php if($is_woocommerce) { ?>
                                            <div class="import-single-col">
                                                <div class="import-single-col-title">Products</div>
                                                <div class="import-single-col-body products">
                                                    <?php get_single_item_elem($product_array, 'product'); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="import-singles-preview-box"></div>

                                    <div class="import-singles-btn-box">
                                        <input class="import-singles-button button-primary" type="submit" value="Import" />
                                    </div>

                                </form>

                            <?php } ?>
						</div>
					</div>
				</div>

                <div id="full-import" class="import-variant">
                    <h3><?php _e('Full demo import'); ?></h3>
                    <div class="inside">
                        <div>
                            <p><?php _e('With this option you can import all demo content with one click, including import of media content (selected and optimized images & videos used on our demo website). Please note: full import & generating of image thumbnails may take from 30 min to 1 hour, depending on your server/hosting configuration.'); ?></p>
                            <button class="import-button button-primary" data-import-part="full" data-import-pack="main"><?php _e('Start full demo import'); ?></button>
                        </div>
                    </div>
                </div>

                <div id="partical-import" class="import-variant">
                    <h3><?php _e('Partial demo import'); ?></h3>
                    <div class="inside">
                        <div>
                            <p><?php _e('With this option you can import all posts/pages and media content separately. For example, if you don\'t want to import our demo images & videos, you can click on "Import content without media". This import runs very fast and is done in minutes. In case you wish to import only media demo content (or add our demo images & videos after you\'ve made "Import content without media") you can click on "Import only media demo content".'); ?></p>
                            <button class="import-button button-primary" data-import-part="posts" data-import-pack="main"><?php _e('Import content without media'); ?></button>
                            <button class="import-button button-primary" data-import-part="media" data-import-pack="main"><?php _e('Import only media demo content'); ?></button>
                        </div>
                    </div>
                </div>

                <div id="remove-demo-content" class="import-variant">
                    <h3><?php _e('Remove demo content'); ?></h3>
                    <div class="inside">
                        <div>
                            <p><?php _e('With this option you can remove all demo content, which you have imported and which you have not modified.'); ?></p>
                            <p><?php _e('IMPORTANT: In case you have edited (and saved) some posts, pages etc., this content will not be removed. This concerns media content as well. If you wish to keep some demo media on your website, you need to remove the word „(Demo)“ from the media description of the corresponding media file (image, video etc), otherwise this file will be removed as well.'); ?></p>
                            <button id="btn-remove-demo-content" type="button" class="button-primary"><?php _e('Remove demo content'); ?></button>
                        </div>
                    </div>
                </div>

                <div id="widgets-import" class="import-variant">
                    <h3><?php _e('Import demo widgets'); ?></h3>
                    <div class="inside">
                        <div>
                            <p><?php _e('Here you can import all demo widgets we have used on our demo website. '); ?></p>
                            <button id="btn-import-widgets" type="button" class="button-primary"><?php _e('Import demo widgets'); ?></button>
                        </div>
                    </div>
                </div>

                <?php $is_easy_mailChimp = class_exists('Yikes_Inc_Easy_MailChimp_Import_Class');
                    if($is_easy_mailChimp) { ?>
                    <div id="mailchimp-forms-import" class="import-variant">
                        <h3><?php _e('Import newsletter forms'); ?></h3>
                        <div class="inside">
                            <div>
                                <p><?php _e('Here you can import all newsletter subscription forms used on our demo website.'); ?></p>
                                <button id="btn-import-mailchimp-forms" type="button" class="button-primary"><?php _e('Import newsletter forms'); ?></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>

			</div>
		</div>
	<?php endif; ?>
</div>
<?php
}

function thegem_import_enqueue($hook) {
	if($hook == 'toplevel_page_thegem-import-submenu-page') {
		wp_enqueue_script('thegem-import-scripts', plugins_url( '/js/ti-scripts.js' , __FILE__ ), array('jquery', 'jquery-ui-accordion', 'jquery-ui-tabs'), false, true);
		wp_localize_script('thegem-import-scripts', 'thegem_import_data', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));
		wp_enqueue_style('thegem-import-css', plugins_url( '/css/ti-styles.css' , __FILE__ ));
	}
}
add_action('admin_enqueue_scripts', 'thegem_import_enqueue');

add_action('wp_ajax_thegem_import_files_list', 'thegem_import_files_list');
function thegem_import_files_list () {
	$response_p = wp_remote_get(add_query_arg(array('code' => thegem_import_get_purchase(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
	if(!is_wp_error($response_p)) {
		$rp_data = json_decode($response_p['body'], true);
		if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
			if(isset($_REQUEST['import_pack']) && isset($_REQUEST['import_part'])) {
				$response = wp_remote_get(add_query_arg(array('import_pack' => $_REQUEST['import_pack'], 'import_part' => $_REQUEST['import_part']), 'http://democontent.codex-themes.com/democontent-packs-new/index.php'), array('timeout' => 20));
				if(!is_wp_error($response)) {
					echo $response['body'];
					if($_REQUEST['import_part']=='posts') {
						update_option('import_attachment_json_data', thegem_get_attachment_json_data());
					}
				} else {
					echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
				}
			} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Sending data error.'));
			}
		} else {
			if(!defined('ENVATO_HOSTED_SITE')) {
			    echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Purchase code verification failed. <a href="'.esc_url(admin_url('themes.php?page=options-framework#activation')).'">Activate TheGem</a>'));
			} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Verification failed.'));
			}
		}
	} else {
		echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
	}
	die(-1);
}

add_action('wp_ajax_thegem_import_file', 'thegem_import_file');
function thegem_import_file () {
	$filedir = '/packs/thegem/';
	if(isset($_REQUEST['import_pack']) && ($_REQUEST['import_pack'] !== 'main')) {
		$filedir = '/packs/'.$_REQUEST['import_pack'].'/';
	}

	if(!empty($_REQUEST['filename'])) {
		ob_start();
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		$tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new' .$filedir.$_REQUEST['filename']);
		if( is_wp_error( $tmp ) ) {
			print_r($tmp->get_error_messages());
		} else {
			if (! defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);
			require_once(plugin_dir_path( __FILE__ ) . '/inc/wordpress-importer.php');
			$wp_import = new WP_Import();
			$wp_import->fetch_attachments = true;
			$wp_import->import($tmp);
		}
		@unlink( $tmp );
		$messages = ob_get_clean();
		echo json_encode(array('status' => 1, 'message' => 'Done. <!-- '.$messages.' -->'));
	}
	die(-1);
}

function thegem_import_replace_array($dir = 1) {
	$packs = array(
		'agencies',
		'apps',
		'beauty',
		'blog',
		'business',
		'church',
		'coming-soon',
		'architecture',
		'creative',
		'events',
		'gym',
		'hotels',
		'landings',
		'lawyer',
		'logistics',
		'medical',
		'photography',
		'portfolios',
		'restaurant',
		'shopdemos',
		'creative-shop',
		'shop-metro',
		'nature-shop',
		'shop-landing',
		'shop-masonry',
		'shop-justified',
	);
	if($dir === 1) {
		$replace_array = array('http://democontent.codex-themes.com/thegem/wp-content/uploads');
	} else {
		$replace_array = array('http://democontent.codex-themes.com/thegem/wp-content/themes/TheGem');
	}
	foreach($packs as $pack) {
		if($dir === 1) {
			$replace_array[] = 'http://democontent.codex-themes.com/thegem-'.$pack.'/wp-content/uploads';
		} else {
			$replace_array[] = 'http://democontent.codex-themes.com/thegem-'.$pack.'/wp-content/themes/TheGem';
		}
	}
	return $replace_array;
}

add_filter('wp_import_post_data_raw', 'thegem_import_wp_import_post_data_raw');
function thegem_import_wp_import_post_data_raw($post) {
	$upload_dir = wp_upload_dir();
	$post['post_content'] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $post['post_content']);
	$post['post_content'] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $post['post_content']);

	if($post['post_type'] != 'nav_menu_item') {
        $post['post_title'] = $post['post_title'].' (Demo)';
    }

	if(!empty($post['post_content'])) {
		$attachments_data = get_option('import_attachment_json_data');
		if(!empty($attachments_data)) {
			$post['post_content'] = thegem_replace_attachments_content($post['post_content'], $attachments_data);
		}
	}

	return $post;
}

add_filter('wp_import_post_terms', 'thegem_wp_import_terms');
function thegem_wp_import_terms($terms) {
    if(!empty($terms)) {
        foreach ($terms as $key => $value) {
            $terms[$key]['name']=$terms[$key]['name'].' (Demo)';
        }
    }
    return $terms;
}

add_action( 'wp_import_insert_post', 'thegem_wp_import_insert_post', 10, 4 );
function thegem_wp_import_insert_post($post_id, $original_post_ID, $postdata, $post) {

    if(get_option('thegem_is_import_singles')) {
        $thegem_import_singles_ids = get_option('thegem_import_singles_ids');
        if(!empty($thegem_import_singles_ids)) {
            $thegem_import_singles_ids[] = $post_id;
            update_option('thegem_import_singles_ids', $thegem_import_singles_ids);
        } else {
            update_option('thegem_import_singles_ids', array($post_id));
        }

    }
}


add_filter('import_post_meta', 'thegem_import_post_meta', 11, 3);
function thegem_import_post_meta($post_id, $key, $value) {
	$upload_dir = wp_upload_dir();
	if(is_array($value)) {
		foreach($value as $k => $v) {
			if(is_array($v)) {
				foreach($v as $a => $b) {
					$value[$k][$a] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value[$k][$a]);
					$value[$k][$a] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value[$k][$a]);
				}
			} else {
				$value[$k] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value[$k]);
				$value[$k] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value[$k]);
			}
		}
	} else {
		$value = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value);
		$value = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value);
	}
	update_post_meta($post_id, $key, $value);
}


add_action('wp_ajax_thegem_delete_attachment_json_data', 'thegem_delete_attachment_json_data');
function thegem_delete_attachment_json_data() {
	delete_option('import_attachment_json_data');
}

add_action('wp_ajax_thegem_import_singles', 'thegem_import_singles');
function thegem_import_singles() {
    $response_p = wp_remote_get(add_query_arg(array('code' => thegem_import_get_purchase(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
    if(!is_wp_error($response_p)) {
        $rp_data = json_decode($response_p['body'], true);
        if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
            if(isset($_REQUEST['ids']) && !empty($_REQUEST['ids'])) {
                $ids = thegem_parse_ids_request($_REQUEST['ids']);
                file_put_contents(ABSPATH.'ids.txt', print_r($ids, 1));
                $file_content = 'http://democontent.codex-themes.com/democontent-packs-new/packs/thegem-singles/content.xml';
                ob_start();
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $tmp = download_url($file_content);
                if( is_wp_error( $tmp ) ) {
                    print_r($tmp->get_error_messages());
                } else {
                    if (! defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);
                    require_once(plugin_dir_path( __FILE__ ) . '/inc/wordpress-importer.php');
                    $wp_import_single = new WP_Import();
                    $wp_import_single->fetch_attachments = true;
                    update_option('thegem_is_import_singles', true);
                    $wp_import_single->import($tmp, $ids);
                }
                @unlink( $tmp );
                $message = ob_get_clean();

                $import_ids = get_option('thegem_import_singles_ids');
                delete_option('thegem_is_import_singles');
                delete_option('thegem_import_singles_ids');

                echo json_encode(array('status' => 1, 'data'=>$import_ids, 'message'=>$message));
            } else {
                echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Error on import: empty ids value.'));
            }
        } else {
            if(!defined('ENVATO_HOSTED_SITE')) {
                echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Purchase code verification failed. <a href="'.esc_url(admin_url('themes.php?page=options-framework#activation')).'">Activate TheGem</a>'));
            } else {
                echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Verification failed.'));
            }
        }
    } else {
        echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
    }
    die(-1);
}


add_action('wp_ajax_thegem_import_singles_update_content', 'thegem_import_singles_update_content');
function thegem_import_singles_update_content() {
	if(isset($_REQUEST['ids']) && !empty($_REQUEST['ids'])) {
		$ids = $_REQUEST['ids'];
		$attachments_data = thegem_get_attachment_json_data('singles');
		$product_categories_data = thegem_get_categories_product_json_data();

		$query = new WP_Query( array('post_status' => 'any', 'post_type' => 'any', 'post__in' => $ids, 'posts_per_page'=>-1) );
		while ( $query->have_posts() ) {
			$query->the_post();
			$p = get_post();
            if(get_post_type() != 'attachment' && !empty($p->post_content)) {
                $p->post_content = thegem_replace_attachments_content($p->post_content, $attachments_data);
                $p->post_content = thegem_replace_product_categories_content($p->post_content, $product_categories_data);
                wp_update_post($p);
            }
		}
		echo json_encode(array('status' => 1));
	}

	die(-1);
}

function thegem_get_attachment_json_data($type='') {
    if($type=='singles') {
        $filedir = '/packs/thegem-singles/';
    } else {
        $filedir = '/packs/thegem/';
        if(isset($_REQUEST['import_pack']) && ($_REQUEST['import_pack'] !== 'main')) {
            $filedir = '/packs/'.$_REQUEST['import_pack'].'/';
        }
    }

    $filename = 'content_attachments.json';
    $data = array();
    $tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new'.$filedir.$filename);

    if( is_wp_error( $tmp ) ) {
        print_r($tmp->get_error_messages());
    } else {
        $file_content = file_get_contents($tmp);
        $file_data = json_decode($file_content, true);
        if(!empty($file_data)) {
            $data = thegem_get_ids_attachment($file_data);
        }
        unlink($tmp);
    }

    return $data;
}


function thegem_get_categories_product_json_data() {
    $data = array();
    $tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new/packs/thegem-singles/content_product_categories.json');

    if( is_wp_error( $tmp ) ) {
        print_r($tmp->get_error_messages());
    } else {
        $file_content = file_get_contents($tmp);
        $file_data = json_decode($file_content, true);
        if(!empty($file_data)) {
            $data = thegem_get_product_categories_ids($file_data);
        }
        unlink($tmp);
    }

    return $data;
}

add_action('wp_ajax_thegem_remove_import_demo_content', 'thegem_remove_import_demo_content');
function thegem_remove_import_demo_content() {
    global $wpdb;
    $demo_prefix = $wpdb->esc_like('(Demo)');
    $demo_prefix = '%'.$demo_prefix.'%';

    //deleted attachments
    $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_type = %s", $demo_prefix, 'attachment');
    $attachments = $wpdb->get_results($sql, OBJECT);
    foreach ($attachments as $key => $value) {
        wp_delete_attachment($value->ID, true);
    }

    //deleted posts
    $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s", $demo_prefix);
    $posts = $wpdb->get_results($sql, OBJECT);
    foreach ($posts as $key => $value) {
        wp_delete_post($value->ID, true);
    }

    //deleted terms
    $sql = $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE name LIKE %s", $demo_prefix);
    $terms = $wpdb->get_results($sql, OBJECT);
    foreach ($terms as $key => $value) {
        $term = get_term($value->term_id);
        wp_delete_term($term->term_id, $term->taxonomy);
    }

    die(-1);
}


add_action('wp_ajax_thegem_import_widgets', 'thegem_import_widgets');
function thegem_import_widgets() {
    $file = plugins_url('/demo-files/widgets.json', __FILE__);
    $response = file_get_contents($file);
    if($response) {
        $file_data = json_decode($response);
        $result = thegem_widgets_import_data($file_data);
        if($result['status']) {
            echo json_encode(array('status' => 1, 'status_text' => 'Import success.'));
        } else {
            echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => $result['message']));
        }
    } else {
        echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'There was a problem opening the file.'));
    }

    die(-1);
}


add_action('wp_ajax_thegem_import_mailchimp_forms', 'thegem_import_mailchimp_forms');
function thegem_import_mailchimp_forms() {
    $file = plugins_url('/demo-files/mailchimp-forms.csv', __FILE__);
    $result = thegem_mailchimp_import_forms($file);
    if($result['status']) {
        echo json_encode(array('status' => 1, 'status_text' => 'Import success.'));
    } else {
        echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => $result['message']));
    }
    die(-1);
}