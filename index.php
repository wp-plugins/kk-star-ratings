<?php

/*
Plugin Name: kk Star Ratings
Plugin URI: http://wakeusup.com/2011/05/kk-star-ratings/
Description: Renewed from the ground up(as of v2.0), clean, animated and light weight ratings feature for your blog. With kk Star Ratings, you can <strong>allow your blog posts to be rated by your blog visitors</strong>. It also includes a <strong>widget</strong> which you can add to your sidebar to show the top rated post. Wait! There is more to it. Enjoy the extensive options you can set to customize this plugin.
Version: 2.0
Author: Kamal Khan
Author URI: http://bhittani.com
License: GPLv2 or later
*/

if(!defined('BHITTANI_PLUGIN_KKSTARRATINGS_ID'))
    define('BHITTANI_PLUGIN_KKSTARRATINGS_ID', 'bhittani_plugin_kksr');
if(!defined('BHITTANI_PLUGIN_KKSTARRATINGS_NICK'))
    define('BHITTANI_PLUGIN_KKSTARRATINGS_NICK', 'kk Star Ratings');
if(!defined('BHITTANI_PLUGIN_KKSTARRATINGS_VER'))
    define('BHITTANI_PLUGIN_KKSTARRATINGS_VER', '2.0');

require_once 'plugin.php';
require_once 'bhittani-framework/admin/markup.class.php';
require_once 'bhittani-framework/admin/admin.class.php';

if(!class_exists('BhittaniPlugin_kkStarRatings')) :

	class BhittaniPlugin_kkStarRatings extends BhittaniPlugin
	{
		public function __construct($id, $nick, $ver)
		{
			parent::__construct($id, $nick, $ver);
		}
		/** function/method
		* Usage: hook js frontend
		* Arg(0): null
		* Return: void
		*/
		public function js()
		{
			$nonce = wp_create_nonce($this->id);
			$Params = array();
			$Params['nonce'] = $nonce; //for security
			$Params['ajaxurl'] = admin_url('admin-ajax.php');
			$Params['func'] = 'kksr_ajax';
			$Params['msg'] = parent::get_options('kksr_init_msg');
			$Params['fueldspeed'] = parent::get_options('kksr_js_fuelspeed');
			$Params['thankyou'] = parent::get_options('kksr_js_thankyou');
			$Params['error_msg'] = parent::get_options('kksr_js_error');
			$Params['tooltip'] = parent::get_options('kksr_tooltip');
			$Tooltips = unserialize(base64_decode(parent::get_options('kksr_tooltips')));
			$Params['tooltips'] = $Tooltips;
			$this->enqueue_js('js', parent::file_uri('js.js'), $this->ver, array('jquery'), $Params, false, true);
		}
		/** function/method
		* Usage: hook js admin
		* Arg(0): null
		* Return: void
		*/
		public function js_admin()
		{
			$nonce = wp_create_nonce($this->id);
			$Params = array();
			$Params['nonce'] = $nonce; //for security
			$Params['ajaxurl'] = admin_url('admin-ajax.php');
			$Params['func'] = 'kksr_admin_ajax';
			$Params['func_reset'] = 'kksr_admin_reset_ajax';
			$this->enqueue_js('js_admin', parent::file_uri('js_admin.js'), $this->ver, array('jquery'), $Params);
		}
		/** function/method
		* Usage: hook css
		* Arg(0): null
		* Return: void
		*/
		public function css()
		{
			$this->enqueue_css('', parent::file_uri('css.css'));
		}
		/** function/method
		* Usage: hook custom css
		* Arg(0): null
		* Return: void
		*/
		public function css_custom()
		{
			$stars = parent::get_options('kksr_stars') ? parent::get_options('kksr_stars') : 5;

			$star_w = parent::get_options('kksr_stars_w') ? parent::get_options('kksr_stars_w') : 24;
			$star_h = parent::get_options('kksr_stars_h') ? parent::get_options('kksr_stars_h') : 24;

			$star_gray = parent::get_options('kksr_stars_gray');
			$star_yellow = parent::get_options('kksr_stars_yellow');
			$star_orange = parent::get_options('kksr_stars_orange');

			echo '<style>';

			echo '.kk-star-ratings { width:'.($star_w*$stars).'px; }';
			echo '.kk-star-ratings .kksr-stars a { width:'.($star_w).'px; }';
			echo '.kk-star-ratings .kksr-stars, .kk-star-ratings .kksr-stars .kksr-fuel, .kk-star-ratings .kksr-stars a { height:'.($star_h).'px; }';

			echo $star_gray ? '.kk-star-ratings .kksr-star.gray { background-image: url('.$star_gray.'); }' : '';
			echo $star_yellow ? '.kk-star-ratings .kksr-star.yellow { background-image: url('.$star_yellow.'); }' : '';
			echo $star_orange ? '.kk-star-ratings .kksr-star.orange { background-image: url('.$star_orange.'); }' : '';
			
			echo '</style>';
		}
		/** function/method
		* Usage: Setting defaults and backwards compatibility
		* Arg(0): null
		* Return: void
		*/
		public function activate()
		{
			$ver_current = $this->ver;
			$ver_previous = parent::get_options('kksr_ver') ? parent::get_options('kksr_ver') : false;
			$Old_plugin = parent::get_options('kk-ratings');

			$opt_enable = 1; // 1|0
			$opt_clear = 0; // 1|0
			$opt_show_in_home = 0; // 1|0
			$opt_show_in_archives = 0; // 1|0
			$opt_show_in_posts = 1; // 1|0
			$opt_show_in_pages = 0; // 1|0
			$opt_unique = 0; // 1|0
			$opt_position = 'top-left'; // 'top-left', 'top-right', 'bottom-left', 'bottom-right'
			$opt_legend = '[avg] ([per]) [total] vote[s]'; // [total]=total ratings, [avg]=average, [per]=percentage [s]=singular/plural
			$opt_init_msg = 'Rate this post'; // string
			$opt_column = 1; // 1|0

			$Options = array();
			$Options['kksr_enable'] = isset($Old_plugin['enable']) ? $Old_plugin['enable'] : $opt_enable;
			$Options['kksr_clear'] = isset($Old_plugin['clear']) ? $Old_plugin['clear'] : $opt_clear;
			$Options['kksr_show_in_home'] = isset($Old_plugin['show_in_home']) ? $Old_plugin['show_in_home'] : $opt_show_in_home;
			$Options['kksr_show_in_archives'] = isset($Old_plugin['show_in_archives']) ? $Old_plugin['show_in_archives'] : $opt_show_in_archives;
			$Options['kksr_show_in_posts'] = isset($Old_plugin['show_in_posts']) ? $Old_plugin['show_in_posts'] : $opt_show_in_posts;
			$Options['kksr_show_in_pages'] = isset($Old_plugin['show_in_pages']) ? $Old_plugin['show_in_pages'] : $opt_show_in_pages;
			$Options['kksr_unique'] = isset($Old_plugin['unique']) ? $Old_plugin['unique'] : $opt_unique;
			$Options['kksr_position'] = isset($Old_plugin['position']) ? $Old_plugin['position'] : $opt_position;
			$Options['kksr_legend'] = isset($Old_plugin['legend']) ? $Old_plugin['legend'] : $opt_legend;
			$Options['kksr_init_msg'] = isset($Old_plugin['init_msg']) ? $Old_plugin['init_msg'] : $opt_init_msg;
			$Options['kksr_column'] = isset($Old_plugin['column']) ? $Old_plugin['column'] : $opt_column;
			
			// Upgrade from old plugin(<2.0) to renewed plugin(2.0)
			if($Old_plugin)
			{
				// Delete old options
				parent::delete_options('kk-ratings');

				// Update previous ratings
				global $wpdb;
				$table = $wpdb->prefix . 'postmeta';
				$Posts = $wpdb->get_results("SELECT a.ID, b.meta_key, b.meta_value 
											 FROM " . $wpdb->posts . " a, $table b 
											 WHERE a.ID=b.post_id AND 
											 (
											 	b.meta_key='_kk_ratings_ratings' OR 
											 	b.meta_key='_kk_ratings_casts' OR 
											 	b.meta_key='_kk_ratings_ips'
											 ) ORDER BY a.ID ASC");
				$Wrap = array();
				foreach ($Posts as $post)
				{
					$Wrap[$post->ID]['id'] = $post->ID;
					$Wrap[$post->ID][$post->meta_key] = $post->meta_value;
				}
				foreach($Wrap as $p)
				{
					update_post_meta($p['id'], '_kksr_ratings', $p['_kk_ratings_ratings']);
					update_post_meta($p['id'], '_kksr_casts', $p['_kk_ratings_casts']);
					$Ips = array();
					$Ips = explode('|', $p['_kk_ratings_ips']);
					$ip = base64_encode(serialize($Ips));
					update_post_meta($p['id'], '_kksr_ips', $ip);
					update_post_meta($p['id'], '_kksr_avg', round($p['_kk_ratings_ratings']/$p['_kk_ratings_casts'],1));
				}
			}
			if(!parent::get_options('kksr_ver'))
			{
				$Options['kksr_ver'] = $ver_current;
				$Options['kksr_stars'] = 5;
				$Options['kksr_stars_w'] = 24;
				$Options['kksr_stars_h'] = 24;
				$Options['kksr_stars_gray'] = 0;
				$Options['kksr_stars_yellow'] = 0;
				$Options['kksr_stars_orange'] = 0;
				$Options['kksr_js_fuelspeed'] = 400;
				$Options['kksr_js_thankyou'] = 'Thank you for your vote';
				$Options['kksr_js_error'] = 'An error occurred';
				$Options['kksr_tooltip'] = 1;
				$Opt_tooltips = array();
				$Opt_tooltips[0]['color'] = 'red';
				$Opt_tooltips[0]['tip'] = 'Poor';
				$Opt_tooltips[1]['color'] = 'brown';
				$Opt_tooltips[1]['tip'] = 'Fair';
				$Opt_tooltips[2]['color'] = 'orange';
				$Opt_tooltips[2]['tip'] = 'Average';
				$Opt_tooltips[3]['color'] = 'blue';
				$Opt_tooltips[3]['tip'] = 'Good';
				$Opt_tooltips[4]['color'] = 'green';
				$Opt_tooltips[4]['tip'] = 'Excellent';
				$Options['kksr_tooltips'] = base64_encode(serialize($Opt_tooltips));
				parent::update_options($Options);
			}
		}
		/** function/method
		* Usage: helper for hooking (registering) the menu
		* Arg(0): null
		* Return: void
		*/
		public function menu()
		{
			// Create main menu tab
			add_menu_page(
				$this->nick.' - Settings', 
				$this->nick, 
	            'manage_options', 
				$this->id.'_settings', 
				array(&$this, 'options_general'),
				parent::file_uri('icon.png')
			);
			// Create images menu tab
			add_submenu_page(
				$this->id.'_settings', 
				$this->nick.' - Stars', 
				'Stars', 
				'manage_options', 
				$this->id.'_settings_stars', 
				array(&$this, 'options_stars')
			);
			// Create tooltips menu tab
			add_submenu_page(
				$this->id.'_settings', 
				$this->nick.' - Tooltips', 
				'Tooltips', 
				'manage_options', 
				$this->id.'_settings_tooltips', 
				array(&$this, 'options_tooltips')
			);
			// Create reset menu tab
			add_submenu_page(
				$this->id.'_settings', 
				$this->nick.' - Reset', 
				'Reset', 
				'manage_options', 
				$this->id.'_settings_reset', 
				array(&$this, 'options_reset')
			);
			// Create info menu tab
			add_submenu_page(
				$this->id.'_settings', 
				$this->nick.' - Info', 
				'Info', 
				'manage_options', 
				$this->id.'_settings_info', 
				array(&$this, 'options_info')
			);
		}
		/** function/method
		* Usage: show options/settings form page
		* Arg(0): null
		* Return: void
		*/
		public function options_page($opt)
		{ 
			if (!current_user_can('manage_options')) 
			{
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			$h3 = 'kk Star Ratings';
			$url_docs = 'http://wp.bhittani.com/plugins/kk-star-ratings/docs';
			$url_changelog = 'http://wp.bhittani.com/plugins/kk-star-ratings/changelog';
			include parent::file_path('admin.php');
		}
		/** function/method
		* Usage: show general options
		* Arg(0): null
		* Return: void
		*/
		public function options_general()
		{ 
			$this->options_page('general');
		}
		/** function/method
		* Usage: show images options
		* Arg(0): null
		* Return: void
		*/
		public function options_stars()
		{ 
			$this->options_page('stars');
		}
		/** function/method
		* Usage: show tooltips options
		* Arg(0): null
		* Return: void
		*/
		public function options_tooltips()
		{ 
			$this->options_page('tooltips');
		}
		/** function/method
		* Usage: show reset options
		* Arg(0): null
		* Return: void
		*/
		public function options_reset()
		{ 
			$this->options_page('reset');
		}
		/** function/method
		* Usage: show info options
		* Arg(0): null
		* Return: void
		*/
		public function options_info()
		{ 
			$this->options_page('info');
		}
		public function kksr_admin_ajax()
		{
			header('content-type: application/json; charset=utf-8');
			check_ajax_referer($this->id);

			$Options = $_POST;
			$Options['kksr_tooltips'] = base64_encode(serialize($_POST['kksr_tooltips']));
			
			unset($Options['_wpnonce']);
			unset($Options['action']);

			parent::update_options($Options);
			
			$Response = array();
			$Response['success'] = 'true';
			echo json_encode($Response);
			die();
		}
		public function kksr_admin_reset_ajax()
		{
			header('content-type: application/json; charset=utf-8');
			check_ajax_referer($this->id);

			$Reset = $_POST['kksr_reset'];
			if(is_array($Reset))
			{
				foreach($Reset as $id => $val)
				{
					if($val=='1')
					{
						delete_post_meta($id, '_kksr_ratings');
						delete_post_meta($id, '_kksr_casts');
						delete_post_meta($id, '_kksr_ips');
						delete_post_meta($id, '_kksr_avg');
					}
				}
			}
			
			$Response = array();
			$Response['success'] = 'true';
			echo json_encode($Response);
			die();
		}
		public function kksr_ajax()
		{
			header('Content-type: application/json; charset=utf-8');
			check_ajax_referer($this->id);

			$Response = array();

			$total_stars = is_numeric(parent::get_options('kksr_stars')) ? parent::get_options('kksr_stars') : 5;

			$pid = $_POST['id'];
			$stars = $_POST['stars'];
			$ip = $_SERVER['REMOTE_ADDR'];

			$ratings = get_post_meta($pid, '_kksr_ratings', true) ? get_post_meta($pid, '_kksr_ratings', true) : 0;
			$casts = get_post_meta($pid, '_kksr_casts', true) ? get_post_meta($pid, '_kksr_casts', true) : 0;

			if($stars==0 && $ratings==0)
			{
				$Response['legend'] = parent::get_options('kksr_init_msg');
				$Response['disable'] = 'false';
				$Response['fuel'] = '0';
			}
			else
			{
				$nratings = $ratings + ($stars/($total_stars/5));
				$ncasts = $casts + ($stars>0);
				$avg = $nratings ? round($nratings/$ncasts,1) : 0;
				$per = $nratings ? round((($nratings/$ncasts)/5)*100) : 0;
				$Response['disable'] = 'false';
				if($stars)
				{
					$Ips = get_post_meta($pid, '_kksr_ips', true) ? unserialize(base64_decode(get_post_meta($pid, '_kksr_ips', true))) : array();
					if(!in_array($ip, $Ips))
					{
						$Ips[] = $ip;
					}
					$ips = base64_encode(serialize($Ips));
					update_post_meta($pid, '_kksr_ratings', $nratings);
					update_post_meta($pid, '_kksr_casts', $ncasts);
					update_post_meta($pid, '_kksr_ips', $ips);
					update_post_meta($pid, '_kksr_avg', $avg);
					$Response['disable'] = parent::get_options('kksr_unique') ? 'true' : 'false';
				}
				$legend = parent::get_options('kksr_legend');
				$legend = str_replace('[total]', $ncasts, $legend);
				$legend = str_replace('[avg]', ($avg*($total_stars/5)).'/'.$total_stars, $legend);
				$legend = str_replace('[s]', $ncasts==1?'':'s', $legend);
				$Response['legend'] = str_replace('[per]',$per.'%', $legend);
				$Response['fuel'] = $per;
			}
			$Response['success'] = 'true';
			echo json_encode($Response);
			die();
		}
		public function markup($id=false)
		{
			$id = !$id ? get_the_ID() : $id;
			$disabled = false;
			if(get_post_meta($id, '_kksr_ips', true))
			{
				$Ips = unserialize(base64_decode(get_post_meta($id, '_kksr_ips', true)));
				$ip = $_SERVER['REMOTE_ADDR'];
				if(in_array($ip, $Ips))
				{
					$disabled = parent::get_options('kksr_unique') ? true : false;
				}
			}
			$pos = parent::get_options('kksr_position');
			$markup = '
			<div class="kk-star-ratings '.($disabled ? 'disabled ' : ' ').$pos.($pos=='top-right'||$pos=='bottom-right' ? ' rgt' : ' lft').'" data-id="'.$id.'">
			    <div class="kksr-stars kksr-star gray">
			        <div class="kksr-fuel kksr-star '.($disabled ? 'orange' : 'yellow').'" style="width:0%;"></div>
			        <!-- kksr-fuel -->';
			$total_stars = parent::get_options('kksr_stars');
			for($ts = 1; $ts <= $total_stars; $ts++)
			{
				$markup .= '<a href="#'.$ts.'"></a>';
			}
			$markup .='
			    </div>
			    <!-- kksr-stars -->
			    <div class="kksr-legend"></div>
			    <!-- kksr-legend -->
			</div>
			<!-- kk-star-ratings -->
			';
			if(is_single())
			{
				$votes = get_post_meta($id, '_kksr_casts', true);
				if($votes)
				{
					$title = get_the_title();
					$avg = get_post_meta($id, '_kksr_avg', true);
					$avg = $avg*($total_stars/5);
					$best = $total_stars;
					$markup.= '<span style="display:none;">
					<div xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
					   <span property="v:itemreviewed">'.$title.'</span>
					   <span rel="v:rating">
					      <span typeof="v:Rating">
					         <span property="v:average">'.$avg.'</span>
					         out of 
					         <span property="v:best">'.$best.'</span>
					      </span>
					   </span>
					   based on 
					   <span property="v:votes">'.$votes.'</span> ratings. 
					</div></span>';
				}
			}
			$markup .= parent::get_options('kksr_clear') ? '<br clear="both" />' : '';
			return $markup;
		}
		public function manual()
		{
		    if(!is_admin() && parent::get_options('kksr_enable'))
			{
			    if(
					((parent::get_options('kksr_show_in_home')) && (is_front_page() || is_home()))
					|| ((parent::get_options('kksr_show_in_archives')) && (is_archive()))
				  )
				    return $this->markup();
				else if(is_single() || is_page())
				    return $this->markup();
			}
			else
			{
				remove_shortcode('kkratings');
				remove_shortcode('kkstarratings');
			}
			return '';
		}
		public function filter($content)
		{
			if(parent::get_options('kksr_enable')) : 
			if(
			    ((parent::get_options('kksr_show_in_home')) && (is_front_page() || is_home()))
				|| ((parent::get_options('kksr_show_in_archives')) && (is_archive()))
				|| ((parent::get_options('kksr_show_in_posts')) && (is_single()))
				|| ((parent::get_options('kksr_show_in_pages')) && (is_page()))
			  ) : 
			    remove_shortcode('kkratings');
				remove_shortcode('kkstarratings');
				$content = str_replace('[kkratings]', '', $content);
				$content = str_replace('[kkstarratings]', '', $content);
				$markup = $this->markup();
				switch(parent::get_options('kksr_position'))
				{
					case 'bottom-left' :
					case 'bottom-right' : return $content . $markup;
					default : return $markup . $content;
				}
			endif;
			endif;
			return $content;
		}
		public function kk_star_rating($pid=false)
		{
		    if(parent::get_options('kksr_enable'))
				return $this->markup($pid);
			return '';
		}
		public function kk_star_ratings_get($total=5, $cat=false)
		{
			global $wpdb;
			$table = $wpdb->prefix . 'postmeta';
			if(!$cat)
			    $rated_posts = $wpdb->get_results("SELECT a.ID, a.post_title, b.meta_value AS 'ratings' FROM " . $wpdb->posts . " a, $table b, $table c WHERE a.post_status='publish' AND a.ID=b.post_id AND a.ID=c.post_id AND b.meta_key='_kksr_avg' AND c.meta_key='_kksr_casts' ORDER BY b.meta_value DESC, c.meta_value DESC LIMIT $total");
			else
			{
			    $table2 = $wpdb->prefix . 'term_taxonomy';
			    $table3 = $wpdb->prefix . 'term_relationships';
			    $rated_posts = $wpdb->get_results("SELECT a.ID, a.post_title, b.meta_value AS 'ratings' FROM " . $wpdb->posts . " a, $table b, $table2 c, $table3 d, $table e WHERE c.term_taxonomy_id=d.term_taxonomy_id AND c.term_id=$cat AND d.object_id=a.ID AND a.post_status='publish' AND a.ID=b.post_id AND a.ID=e.post_id AND b.meta_key='_kksr_avg' AND e.meta_key='_kksr_casts' ORDER BY b.meta_value DESC, e.meta_value DESC LIMIT $total");
			}
			
			return $rated_posts;
		}
		public function add_column($Columns)
		{
			if(parent::get_options('kksr_column'))
			    $Columns['kk_star_ratings'] = 'Ratings';
			return $Columns;
		}
		function add_row($Columns, $id)
		{
			$total_stars = parent::get_options('kksr_stars');
			if(parent::get_options('kksr_column'))
			{
				$row = 'No ratings';
				$raw = (get_post_meta($id, '_kksr_ratings', true)?get_post_meta($id, '_kksr_ratings', true):0);
				if($raw)
				{
					$avg = '<strong>'.(get_post_meta($id, '_kksr_avg', true)?(get_post_meta($id, '_kksr_avg', true)*($total_stars/5)).'/'.$total_stars:'0').'</strong>';
					$cast = (get_post_meta($id, '_kksr_casts', true)?get_post_meta($id, '_kksr_casts', true):'0').' votes';
					$per = ($raw>0?ceil((($raw/$cast)/5)*100):0).'%';
					$row = $avg . ' (' . $per . ') ' . $cast;
				}
				switch($Columns)
				{
					case 'kk_star_ratings' : echo $row; break;
				}
			}
		}
	}

	require_once 'shortcode/shortcode.php';

	$kkStarRatings_obj = new BhittaniPlugin_kkStarRatings(
								BHITTANI_PLUGIN_KKSTARRATINGS_ID, 
								BHITTANI_PLUGIN_KKSTARRATINGS_NICK, 
								BHITTANI_PLUGIN_KKSTARRATINGS_VER
							);
    
    register_activation_hook(__FILE__, array($kkStarRatings_obj, 'activate'));
	add_action('wp_enqueue_scripts', array($kkStarRatings_obj, 'js'));
	add_action('wp_enqueue_scripts', array($kkStarRatings_obj, 'css'));
	add_action('wp_head', array($kkStarRatings_obj, 'css_custom'));
	add_action('admin_enqueue_scripts', array($kkStarRatings_obj, 'js_admin'));
	add_action('admin_menu', array($kkStarRatings_obj, 'menu'));

	add_action('wp_ajax_kksr_admin_ajax', array($kkStarRatings_obj, 'kksr_admin_ajax'));
	add_action('wp_ajax_kksr_admin_reset_ajax', array($kkStarRatings_obj, 'kksr_admin_reset_ajax'));
	add_action('wp_ajax_kksr_ajax', array($kkStarRatings_obj, 'kksr_ajax'));
	add_action('wp_ajax_nopriv_kksr_ajax', array($kkStarRatings_obj, 'kksr_ajax'));

	add_filter('the_content', array($kkStarRatings_obj, 'filter'));
	add_shortcode('kkratings', array($kkStarRatings_obj, 'manual'));
	add_shortcode('kkstarratings', array($kkStarRatings_obj, 'manual'));

	add_filter( 'manage_posts_columns', array($kkStarRatings_obj, 'add_column') );
	add_filter( 'manage_pages_columns', array($kkStarRatings_obj, 'add_column') );
	add_filter( 'manage_posts_custom_column', array($kkStarRatings_obj, 'add_row'), 10, 2 );
	add_filter( 'manage_pages_custom_column', array($kkStarRatings_obj, 'add_row'), 10, 2 );

	if(!function_exists('kk_star_ratings'))
	{
		function kk_star_ratings($pid=false)
		{
			global $kkStarRatings_obj;
			return $kkStarRatings_obj->kk_star_rating($pid);
		}
	}
	if(!function_exists('kk_star_ratings_get'))
	{
		function kk_star_ratings_get($lim=5, $cat=false)
		{
			global $kkStarRatings_obj;
			return $kkStarRatings_obj->kk_star_ratings_get($lim, $cat);
		}
	}

	require_once 'widget.php';

endif;

?>