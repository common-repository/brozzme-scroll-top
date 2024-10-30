<?php
/*
Plugin Name: Brozzme Scroll Top
Plugin URI: https://brozzme.com/scroll-top/
Description: Simply add scroll to top button on every pages.
Version: 1.8.5
Author: Benoti
Author URI: https://brozzme.com
Text Domain: brozzme-scroll-top

*/

/**
 * Class brozzme_scroll_top
 */
class brozzme_scroll_top{

    public function __construct(){

        // Define plugin constants
        $this->basename			 = plugin_basename( __FILE__ );
        $this->directory_path    = plugin_dir_path( __FILE__ );
        $this->directory_url	 = plugins_url( dirname( $this->basename ) );

        // group menu ID
        $this->plugin_dev_group = 'Brozzme';
        $this->plugin_dev_group_id = 'brozzme-plugins';

        // plugin info
        $this->plugin_name = 'Brozzme Scroll Top';
        $this->settings_page_slug = 'brozzme-scroll-top';
        $this->plugin_slug = 'brozzme-scroll-top';
        $this->plugin_version = '1.7.0';
        $this->plugin_txt_domain = 'brozzme-scroll-top';

        $this->_define_constants();

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'desactivate'));
        register_uninstall_hook(__FILE__, array('brozzme_scroll_top', 'uninstall'));


        if(is_admin()){
            $this->plugin_settings();
        }


        add_action( 'plugins_loaded', array($this, 'bst_load_textdomain') );

        add_filter( 'plugin_action_links_' . $this->basename, array( $this, 'add_action_links' ) );
        add_action( 'admin_enqueue_scripts', array( $this, '_add_color_picker') );
        
        $this->setting_options = get_option('bst_general_settings');
        if(!is_admin() && $this->setting_options['bst_enable'] === 'true'){
              add_action('wp_footer', array($this, '_add_head_style'));
              add_action('wp_footer', array($this, '_add_inline_script'));
        }
        if($this->setting_options['bst_enable'] === 'true' && $this->setting_options['bst_enable_admin'] === 'true'){
            add_action('admin_footer', array($this, '_add_head_style'));
            add_action('wp_enqueue_scripts', array($this, 'localize_scroll_top'));
            //add_action('admin_footer', array($this, '_add_inline_script'));
        }

    }

    public function _define_constants(){

        defined('BFSL_PLUGINS_DEV_GROUPE')    or define('BFSL_PLUGINS_DEV_GROUPE', $this->plugin_dev_group);
        defined('BFSL_PLUGINS_DEV_GROUPE_ID') or define('BFSL_PLUGINS_DEV_GROUPE_ID', $this->plugin_dev_group_id);
        defined('BFSL_PLUGINS_URL') or define('BFSL_PLUGINS_URL', $this->directory_url);
        defined('BFSL_PLUGINS_SLUG') or define('BFSL_PLUGINS_SLUG', $this->plugin_slug);

        defined('BSCROLLTOP')    or define('BSCROLLTOP', $this->plugin_name);
        defined('BSCROLLTOP_BASENAME')    or define('BSCROLLTOP_BASENAME', $this->basename);
        defined('BSCROLLTOP_DIR')    or define('BSCROLLTOP_DIR', $this->directory_path);
        defined('BSCROLLTOP_DIR_URL')    or define('BSCROLLTOP_DIR_URL', $this->directory_url);
        defined('BSCROLLTOP_SETTINGS_SLUG')  or define('BSCROLLTOP_SETTINGS_SLUG', $this->settings_page_slug);
        defined('BSCROLLTOP_VERSION')        or define('BSCROLLTOP_VERSION', $this->plugin_version);
        defined('BSCROLLTOP_TEXT_DOMAIN')    or define('BSCROLLTOP_TEXT_DOMAIN', $this->plugin_txt_domain);

    }

    /**
     * Add plugin setting link to plugins page
     *
     * @param $links
     * @return array
     */
    public function add_action_links ($links ) {
        $mylinks = array(
            '<a href="' . admin_url('admin.php?page='.$this->settings_page_slug ) . '">' . __( 'Settings', $this->plugin_txt_domain ) . '</a>',
        );
        return array_merge( $links, $mylinks );
    }

    /**
     * include files for settings page & brozzme panel
     */
    public function plugin_settings(){
        if (!class_exists('brozzme_plugins_page')){
           include_once ($this->directory_path . 'includes/brozzme_plugins_page.php');
        }
        include_once $this->directory_path . 'includes/brozzme_scrolltop_settings.php';
        include_once $this->directory_path . 'includes/brozzme_divi_override.php';
        include_once $this->directory_path . 'includes/brozzme_oceanwp_override.php';
    }


    /**
     * load text domain
     */
    public function bst_load_textdomain() {
        load_plugin_textdomain( $this->plugin_txt_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Addo color picker on settings page
     *
     * @param $hook
     */
    public function _add_color_picker($hook ) {

        if( is_admin() ) {
            if($hook == 'toplevel_page_' . $this->plugin_dev_group_id || $hook == 'brozzme_page_brozzme-scroll-top'){
                wp_enqueue_style( $this->plugin_txt_domain, plugin_dir_url( __FILE__ ) . 'css/brozzme-admin-css.css');
            }
            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_script( 'color-picker-custom',
                plugins_url( 'js/color-picker-custom.js', __FILE__ ),
                array( 'wp-color-picker' ),
                false,
                true );

            wp_enqueue_style( 'brozzme-essentials', plugin_dir_url( __FILE__ ) . 'css/brozzme-admin-css.css');
        }
    }


    /**
     * print css in header in accordance with the selected options
     */
    public function _add_head_style(){

        if($this->setting_options['bst_enable'] !== 'true'){
            return;
        }

        if ( false === ( $_scroll_top_style = get_transient( 'b7e_scroll_top' ) ) ) {
            if($this->setting_options['bst_bg_box']!==''){

                $asset_url = apply_filters('brozzme_scroll_top_icon_override', $this->directory_url . '/css/assets/brozzme-scrolltop-arrow.svg');

                $rgba_color = $this->_hex2rgba($this->setting_options['bst_bg_box'],'0.8');
                if($this->setting_options['bst_bg_box_hover']!=''){
                    $rgba_color_hover =  $this->_hex2rgba($this->setting_options['bst_bg_box_hover'],'1');
                }
                else{
                    $rgba_color_hover = $rgba_color;
                }

                $float = (empty($this->setting_options['bst_float']))? 'right': $this->setting_options['bst_float'];
                $raw_float_padding = $this->_sanitize_value($this->setting_options['bst_float_padding']);
                $float_padding = (empty($this->setting_options['bst_float_padding']))? '10': $raw_float_padding;

                $float_vertical = (empty($this->setting_options['bst_float_vertical']))? 'bottom': $this->setting_options['bst_float_vertical'];
                $raw_float_vertical_padding = $this->_sanitize_value($this->setting_options['bst_float_vertical_padding']);
                $float_vertical_padding = (empty($this->setting_options['bst_float_vertical_padding']))? '40': $raw_float_vertical_padding;

                $bst_height = (empty($this->setting_options['bst_height_size']))? '40': $this->_sanitize_value($this->setting_options['bst_height_size']);
                $bst_width = (empty($this->setting_options['bst_width_size']))? '40': $this->_sanitize_value($this->setting_options['bst_width_size']);

                $bst_border_radius = (empty($this->setting_options['bst_border_radius']))? '0': $this->_sanitize_value($this->setting_options['bst_border_radius']);

                $padding = (is_admin()) ? '' : 'padding:10px;';
                $add_css = '';
                if(is_admin()){
                    $add_css = '.scrollTop:hover, .scrollTop:focus{box-shadow:unset;}';
                }

                ob_start();

                apply_filters('b7e_sroll_top_add_css', $add_css);
                ?>
                <!-- Brozzme Scroll Top style-->
                <style media="all">
                    .scrollTop{position:fixed;z-index:999999;text-align:center;text-decoration:none;text-indent:100%;<?php echo $padding;?>height:<?php echo $bst_height;?>px;width:<?php echo $bst_width;?>px;border-radius:<?php echo $bst_border_radius;?>px;<?php echo $float_vertical;?>:<?php echo $float_vertical_padding; ?>px;<?php echo $float;?>:<?php echo $float_padding; ?>px;background:<?php echo $rgba_color;?>url(<?php echo $asset_url;?>) no-repeat center 50%;}.scrollTop:hover{background:<?php echo $rgba_color_hover;?> url(<?php echo $asset_url;?>) no-repeat center 50%;}<?php echo $add_css;?>
                </style>
                <?php
                $_scroll_top_style = ob_get_clean();
            }
            set_transient( 'b7e_scroll_top', $_scroll_top_style, 30 * DAY_IN_SECONDS );
        }

        echo $_scroll_top_style;
    }
    
    /**
     * print scripts to make it work on front-end
     */
    public function _add_inline_script(){
        if($this->setting_options['bst_enable'] === 'true' || !current_user_can('manage_options')){
            $speed = (empty($this->setting_options['bst_animation_speed'])) ? '3000' : $this->setting_options['bst_animation_speed'];
            $type = (empty($this->setting_options['bst_animation_type'])) ? 'linear' : $this->setting_options['bst_animation_type'];
            ?>
            <script defer="defer">jQuery(document).ready(function(o){o("body").append('<a href="#" class="scrollTop" style="display:none;"></a>'),o(window).scroll(function(){o(this).scrollTop()>100?o(".scrollTop").fadeIn():o(".scrollTop").fadeOut()}),o(".scrollTop").click(function(){var l="<?php echo $speed;?>",c="<?php echo $type;?>";return o("html,body").animate({scrollTop:0},l,c),!1})});</script>
            <?php
        }
    }

    public function localize_scroll_top(){
        // Register the script
        wp_register_script( 'b7e_scroll_top', plugin_dir_url( __FILE__ ) . 'js/jquery.brozzme.scrolltop.js', array('jquery') );

        if($this->setting_options['bst_enable'] === 'true' || !current_user_can('manage_options')){
            $speed = (empty($this->setting_options['bst_animation_speed'])) ? '3000' : $this->setting_options['bst_animation_speed'];
            $type = (empty($this->setting_options['bst_animation_type'])) ? 'linear' : $this->setting_options['bst_animation_type'];
        }

        // Localize the script with new data
        $options_array = array(
            'speed' => $speed,
            'type' => $type
        );
        wp_localize_script( 'b7e_scroll_top', 'scrolltopEffect', $options_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'b7e_scroll_top' );

    }

    /**
     * activation sequence
     */
    public function activate(){
        if(!get_option('bst_general_settings')){

            $options = array(
                'bst_enable' => 'true',
                'bst_enable_admin'=> 'true',
                'bst_bg_box'=>'#E86256',
                'bst_bg_box_hover'=>'#E85555',
                'bst_float'=>'right',
                'bst_float_padding'=>'10',
                'bst_float_vertical'=>'bottom',
                'bst_float_vertical_padding'=>'40',
                'bst_height_size'=>'40',
                'bst_width_size'=>'40',
                'bst_animation_speed'=>'800',
                'bst_animation_type'=>'swing',
                'bst_border_radius'=> 0
            );
            
            add_option('bst_general_settings', $options);
        }
    }
    /**
     * desactivation sequence
     */
    public function desactivate(){
        delete_transient('b7e_scroll_top');
        // delete_option('bst_general_settings');
    }
    /**
     * uninstall sequence
     */
    function uninstall(){
        delete_option('bst_general_settings');
    }
    
    /**
     * sanitize value to insure that there is no extra unit
     *
     * @param $value
     * @return array|bool|int|string
     */
    public function _sanitize_value($value){

        if(!empty($value)){
            $data = strpos($value, 'px');
            if($data !== false ){
                // px has been found
                $data = explode('px', $value);
                $data = trim($data[0]);
            }
            else{
                $data = trim($value);
            }
        }
        return $data;
    }


    /**
     * convert hexadecimal color to rgba
     *
     * @param $color
     * @param bool $opacity
     * @return string
     */
    public function _hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        return $output;
    }
}

if(class_exists('brozzme_scroll_top')){
    new brozzme_scroll_top();
}
