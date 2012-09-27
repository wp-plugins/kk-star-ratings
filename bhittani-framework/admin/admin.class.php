<?php

/* -------------------------------------------------------------
----------------------------------------------------------------
|                                                              |
|  File name : admin.class.php                                 |
|  Usage     : Hooks the options/settings into wordpress       |
|  Class     : BhittaniPlugin_Admin                            |
|  Version   : 0.1                                             |
|  Author    : Kamal Khan                                      |
|  URI       : http://wp.bhittani.com/framework                |
|                                                              |
|  Description :                                               |
|  Creates the settings page and includes all the neccessary   |
|  HTML to generate the page, including scripts                |
|                                                              |
|  -CHANGELOG-                                                 |
|  ----------------------------------------------------------  |
|  0.1 - First release                                         |
|                                                              |
----------------------------------------------------------------
------------------------------------------------------------- */

if(!defined('BhittaniPlugin_Admin')) :

    // Declare and define the class.
	class BhittaniPlugin_Admin extends BhittaniPlugin
	{
		public function __construct($id, $nick, $ver)
		{
			parent::__construct($id, $nick, $ver);
		}
		/** function/method
		* Usage: hook js
		* Arg(0): null
		* Return: void
		*/
		public function js()
		{
			wp_enqueue_script('media-upload');
			$this->enqueue_js('colorpicker', parent::file_uri('bhittani-framework/admin/js/colorpicker/js/colorpicker.js'));
			$this->enqueue_js('jqui', parent::file_uri('bhittani-framework/admin/js/jquery-ui-1.8.14.custom.min.js'));
			$this->enqueue_js('lightbox', parent::file_uri('bhittani-framework/admin/js/lightbox.js'));
			$this->enqueue_js('admin', parent::file_uri('bhittani-framework/admin/js/admin.js'));
		}
		/** function/method
		* Usage: hook css
		* Arg(0): null
		* Return: void
		*/
		public function css()
		{
			$this->enqueue_css('admin', parent::file_uri('bhittani-framework/admin/css/admin.css'));
			$this->enqueue_css('colorpicker', parent::file_uri('bhittani-framework/admin/js/colorpicker/css/colorpicker.css'));
			wp_enqueue_style('thickbox');
		}
		
		public function scripts()
		{
			$this->js();
			$this->css();
		}
		public function lightbox_html($footer)
		{
		    ?>
            <div class="bhittani-lightbox">
                <div class="kkpopup-bg"></div>
                <div class="kkpopup-exit"><a href="#"><img src="<?php echo parent::file_uri('bhittani-framework/admin/images/error.png'); ?>" width="16" height="16" alt="Close" /></a></div>
                <div class="kkpopup"></div>
                <span class="kkpopup__processing"><img src="<?php echo parent::file_uri('bhittani-framework/admin/images/loading.gif'); ?>" width="16" height="16" alt="Proccessing!" class="kkpopup-process" /></span>
            </div>
            <div class="kkpopup-lightbox bhittani-lightbox"></div>
            <?php	
		}
	}
	
	$BhittaniPlugin_Admin_obj = new BhittaniPlugin_Admin(BHITTANI_PLUGIN_KKSTARRATINGS_ID, BHITTANI_PLUGIN_KKSTARRATINGS_NICK, BHITTANI_PLUGIN_KKSTARRATINGS_VER);
	add_action('admin_enqueue_scripts', array($BhittaniPlugin_Admin_obj, 'scripts'));
	add_filter('admin_footer', array($BhittaniPlugin_Admin_obj, 'lightbox_html'));
	
endif;
?>