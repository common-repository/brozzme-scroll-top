<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22/08/2016
 * Time: 17:12
 */


class brozzme_scrolltop_settings
{
    public function __construct(){

        $this->options = get_option('bst_general_settings');
        $this->settings_page_slug = BSCROLLTOP_SETTINGS_SLUG;
        $this->plugin_text_domain = BSCROLLTOP_TEXT_DOMAIN;
        $this->plugin_name = BSCROLLTOP;
        add_action('admin_init', array($this, '_settings_init'));
        add_action('admin_menu', array($this, '_add_admin_pages'), 110);

    }

    /**
     *
     */
    public function _add_admin_pages() {

        $page = add_submenu_page( BFSL_PLUGINS_DEV_GROUPE_ID,
            'Scroll top',
            'Scroll top',
            'manage_options',
            $this->settings_page_slug,
            array( $this, '_settings_page')
        );
    }

    /**
     *
     */
    public function _settings_init(){
        register_setting( 'BscrolltopSettings', 'bst_general_settings' );

        add_settings_section(
            'BscrolltopSettings_section',
            __( 'General settings option ', $this->plugin_text_domain),
            array($this, 'bst_general_settings_section_callback'),
            'BscrolltopSettings'
        );
        /* General settings */
        add_settings_field(
            'bst_enable', 
            __( 'Enable Scroll top', $this->plugin_text_domain),
            array($this, 'bst_enable_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_enable_admin',
            __( 'Enable back-office Scroll top', $this->plugin_text_domain),
            array($this, 'bst_enable_admin_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_bg_box', 
            __( 'Button background color', $this->plugin_text_domain),
            array($this, 'bst_bg_box_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_bg_box_hover', 
            __( 'Button background color on hover', $this->plugin_text_domain),
            array($this, 'bst_bg_box_hover_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_height_size',
            __( 'Button height', $this->plugin_text_domain),
            array($this, 'bst_height_size_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_width_size',
            __( 'Button width', $this->plugin_text_domain),
            array($this, 'bst_width_size_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_border_radius',
            __( 'Border radius', $this->plugin_text_domain),
            array($this, 'bst_border_radius_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_float',
            __( 'Horizontal position', $this->plugin_text_domain),
            array($this, 'bst_float_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_float_padding',
            __( 'Padding for horizontal position', $this->plugin_text_domain),
            array($this, 'bst_float_padding_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_float_vertical',
            __( 'Vertical position', $this->plugin_text_domain),
            array($this, 'bst_float_vertical_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_float_vertical_padding',
            __( 'Padding for vertical position', $this->plugin_text_domain),
            array($this, 'bst_float_vertical_padding_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_animation_speed',
            __( 'Animation speed', $this->plugin_text_domain),
            array($this, 'bst_animation_speed_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_field(
            'bst_animation_type',
            __( 'Animation type', $this->plugin_text_domain),
            array($this, 'bst_animation_type_render'),
            'BscrolltopSettings',
            'BscrolltopSettings_section'
        );
        add_settings_section(
            'BscrolltopHelp_section',
            __( 'Help', $this->plugin_text_domain),
            array($this, 'bst_help_section_callback'),
            'BscrolltopHelp'
        );
    }

    /**
     *
     */
    public function _settings_page(){
        ?>
        <div class="wrap">

            <h2><?php echo $this->plugin_name; ?></h2>
            <?php

            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings';
            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo $this->settings_page_slug; ?>&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General settings', $this->plugin_text_domain);?></a>
                <a href="?page=<?php echo $this->settings_page_slug; ?>&tab=help_options" class="nav-tab <?php echo $active_tab == 'help_options' ? 'nav-tab-active' : ''; ?>">Help</a>
                <a href="admin.php?page=brozzme-plugins" class="nav-tab <?php echo $active_tab == 'brozzme' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Brozzme', BSCROLLTOP_TEXT_DOMAIN );?></a>

            </h2>
            <form id="brozzme-theme-panel-form" action='options.php' method='post'>
                <?php
                if( $active_tab == 'help_options' ) {
                    settings_fields('BscrolltopHelp');
                    do_settings_sections('BscrolltopHelp');
                }
                else {
                    settings_fields('BscrolltopSettings');
                    do_settings_sections('BscrolltopSettings');
                    submit_button();
                }

                ?>

            </form>
        </div>
        <?php
    }

    /**
     *
     */
    public function bst_general_settings_section_callback(){

        delete_transient('b7e_scroll_top');

    }


    /**
     *
     */
    public function bst_enable_render(){
        ?>
        <div id="bounds">
            <label class="on"><input type="radio" name="bst_general_settings[bst_enable]" value="true"  <?php checked( $this->options['bst_enable'], 'true' ); ?>>
                <span><?php _e( 'Yes', $this->plugin_text_domain);?></span></label>
            <label class="off"><input type="radio" name="bst_general_settings[bst_enable]" value="false"  <?php checked( $this->options['bst_enable'], 'false' ); ?>>
                <span><?php _e( 'No', $this->plugin_text_domain);?></span></label>
        </div>

        <?php
        
        if(brozzme_divi__back_to_top_override::is_theme() === true || brozzme_oceanwp__back_to_top_override::is_theme() === true){
            if(brozzme_divi__back_to_top_override::is_theme() === true){
                ?>
                <div class="brozzme-info" style="clear: both;">
                    <p><?php _e('Divi has been detected as your template theme, to avoid two back-to-top buttons, the Divi\'s option has been disabled and set to "off".', $this->plugin_text_domain);?></p>
                    <p><?php _e('If you want to get back to the Divi original back-to-top button, turn Off the above option and reactivate it in the Divi theme customizer.', $this->plugin_text_domain);?></p>
                </div>
                <?php
            }
            if(brozzme_oceanwp__back_to_top_override::is_theme() === true){
                ?>
                <div class="brozzme-info" style="clear: both;">
                    <p><?php _e('OceanWP has been detected as your template theme, to avoid two back-to-top buttons, the OceanWP\'s option has been disabled and set to "off".', $this->plugin_text_domain);?></p>
                    <p><?php _e('If you want to get back to the OceanWP original back-to-top button, turn Off the above option and reactivate it in the OceanWP theme customizer.', $this->plugin_text_domain);?></p>
                </div>
                <?php
            }
        }
    }

    /**
     *
     */
    public function bst_enable_admin_render(){
        ?>
        <div id="bounds">
            <label class="on"><input type="radio" name="bst_general_settings[bst_enable_admin]" value="true"  <?php checked( $this->options['bst_enable_admin'], 'true' ); ?>>
                <span><?php _e( 'Yes', $this->plugin_text_domain);?></span></label>
            <label class="off"><input type="radio" name="bst_general_settings[bst_enable_admin]" value="false"  <?php checked( $this->options['bst_enable_admin'], 'false' ); ?>>
                <span><?php _e( 'No', $this->plugin_text_domain);?></span></label>
        </div>

        <?php
    }

    /**
     *
     */
    public function bst_bg_box_render(){
        ?>
        <input type='text' name='bst_general_settings[bst_bg_box]' value='<?php echo $this->options['bst_bg_box']; ?>' class='color-field'>
        <?php
    }

    /**
     *
     */
    public function bst_bg_box_hover_render(){
        ?>
        <input type='text' name='bst_general_settings[bst_bg_box_hover]' value='<?php echo $this->options['bst_bg_box_hover']; ?>' class='color-field'>
        <?php
    }

    /**
     *
     */
    public function bst_border_radius_render(){
        ?>
        <input type='text' size="5" name='bst_general_settings[bst_border_radius]' value='<?php echo $this->options['bst_border_radius']; ?>' > pixels
        <p><?php _e('Default radius is 0 pixels', $this->plugin_text_domain);?></p>
        <?php
    }

    /**
     *
     */
    public function bst_float_render(){
        ?>
        <select name="bst_general_settings[bst_float]">
            <option value="left" <?php if ( $this->options['bst_float'] == 'left' ) echo 'selected="selected"'; ?>><?php _e( 'Left', $this->plugin_text_domain);?></option>
            <option value="right" <?php if ( $this->options['bst_float'] == 'right' ) echo 'selected="selected"'; ?>><?php _e( 'Right', $this->plugin_text_domain);?></option>
        </select>
        <?php
    }

    /**
     *
     */
    public function bst_float_padding_render(){
        ?>
        <input type='text' size="5" name='bst_general_settings[bst_float_padding]' value='<?php echo $this->options['bst_float_padding']; ?>' > pixels
        <p><?php _e('Default unit is 10 pixels', $this->plugin_text_domain);?></p>
        <?php
    }

    /**
     *
     */
    public function bst_float_vertical_render(){
        ?>
        <select name="bst_general_settings[bst_float_vertical]">
            <option value="top" <?php if ( $this->options['bst_float_vertical'] == 'top' ) echo 'selected="selected"'; ?>><?php _e( 'Top', $this->plugin_text_domain);?></option>
            <option value="bottom" <?php if ( $this->options['bst_float_vertical'] == 'bottom' ) echo 'selected="selected"'; ?>><?php _e( 'Bottom', $this->plugin_text_domain);?></option>
        </select>
        <?php
    }

    /**
     *
     */
    public function bst_float_vertical_padding_render(){
        ?>
        <input type='text' size="5" name='bst_general_settings[bst_float_vertical_padding]' value='<?php echo $this->options['bst_float_vertical_padding']; ?>'> pixels
        <p><?php _e('Default unit is 40 pixels', $this->plugin_text_domain);?></p>
        <?php
    }

    /**
     *
     */
    public function bst_height_size_render(){
        ?>
        <input type='text' size="5" name='bst_general_settings[bst_height_size]' value='<?php echo $this->options['bst_height_size']; ?>' > pixels
        <p><?php _e('Default unit is 40 pixels', $this->plugin_text_domain);?></p>
        <?php
    }

    /**
     *
     */
    public function bst_width_size_render(){
        ?>
        <input type='text' size="5" name='bst_general_settings[bst_width_size]' value='<?php echo $this->options['bst_width_size']; ?>' > pixels
        <p><?php _e('Default unit is 40 pixels', $this->plugin_text_domain);?></p>
        <?php
    }

    /**
     *
     */
    public function bst_animation_speed_render(){
        ?>
        <select name="bst_general_settings[bst_animation_speed]">
            <option value="O" <?php if ( $this->options['bst_animation_speed'] == '0' ) echo 'selected="selected"'; ?>><?php _e( '0', $this->plugin_text_domain);?></option>
            <option value="800" <?php if ( $this->options['bst_animation_speed'] == '800' ) echo 'selected="selected"'; ?>>0,8 <?php _e( 'second (default)', $this->plugin_text_domain);?></option>
            <option value="1000" <?php if ( $this->options['bst_animation_speed'] == '1000' ) echo 'selected="selected"'; ?>>1 <?php _e( 'second', $this->plugin_text_domain);?></option>
            <option value="2000" <?php if ( $this->options['bst_animation_speed'] == '2000' ) echo 'selected="selected"'; ?>>2 <?php _e( 'seconds', $this->plugin_text_domain);?></option>
            <option value="3000" <?php if ( $this->options['bst_animation_speed'] == '3000' ) echo 'selected="selected"'; ?>>3 <?php _e( 'seconds', $this->plugin_text_domain);?></option>
            <option value="5000" <?php if ( $this->options['bst_animation_speed'] == '5000' ) echo 'selected="selected"'; ?>>5 <?php _e( 'seconds', $this->plugin_text_domain);?></option>
        </select>
        <?php
    }

    /**
     *
     */
    public function bst_animation_type_render(){
        ?>
        <select name="bst_general_settings[bst_animation_type]">
            <option value="swing" <?php if ( $this->options['bst_animation_type'] == 'swing' ) echo 'selected="selected"'; ?>><?php _e( 'Swing', $this->plugin_text_domain);?></option>
            <option value="linear" <?php if ( $this->options['bst_animation_type'] == 'linear' ) echo 'selected="selected"'; ?>><?php _e( 'Linear', $this->plugin_text_domain);?></option>
            <option value="none" <?php if ( $this->options['bst_animation_type'] == 'none' ) echo 'selected="selected"'; ?>><?php _e( 'None', $this->plugin_text_domain);?></option>
        </select>
        <?php
    }

    /**
     *
     */
    public function bst_help_section_callback(){

        ?>
        <div class="brozzme-info large">
        <p><?php _e('Brozzme Scroll Top automatically adds a nice scroll to top button. You don\'t need to add shortcode, widget or any template tag to see it.', $this->plugin_text_domain);?></p>
        </div>

        <div class="brozzme-info">
            <h3><?php _e('You can set essentials options in the admin panel:', $this->plugin_text_domain);?></h3>
            <ul>
                <li><?php _e('Button background color', $this->plugin_text_domain);?></li>
                <li><?php _e('Button background color on hover', $this->plugin_text_domain);?></li>
                <li><?php _e('Horizontal position', $this->plugin_text_domain);?>, <?php _e('float left or right', $this->plugin_text_domain);?></li>
                <li><?php _e('Padding for horizontal position', $this->plugin_text_domain);?>, <?php _e('spacing from border (left or right).', $this->plugin_text_domain);?></li>
                <li><?php _e('Vertical position', $this->plugin_text_domain);?>, <?php _e('vertical-align top or bottom', $this->plugin_text_domain);?></li>
                <li><?php _e('Padding for vertical position', $this->plugin_text_domain);?>, <?php _e('spacing from document top or bottom', $this->plugin_text_domain);?></li>
                <li><?php _e('Button height', $this->plugin_text_domain);?></li>
                <li><?php _e('Button width', $this->plugin_text_domain);?></li>
                <li><?php _e('Speed and animation type', $this->plugin_text_domain);?></li>
                <li><?php _e('You can easily enable or disable the plugin from appearing on the front-end while you configuring it.', $this->plugin_text_domain);?></li>
                <li><?php _e('Options are not delete with plugin desactivation.', $this->plugin_text_domain);?></li>
                <li><?php _e('Divi and OceanWP users can now use Brozzme Scroll Top. The theme back-to-top button is automatically deactived when the plugin is enable.', $this->plugin_text_domain);?></li>
            </ul>
        </div>
        <div class="brozzme-info">
        <p><?php _e('There is one way to change icon svg file. Hook through "brozzme_scroll_top_icon_override" in your theme functions.php', $this->plugin_text_domain);?></p>
        <code>add_filter('brozzme_scroll_top_icon_override', 'your_icon_override');</code>
        <p>And then create your function to return new file url.</p>
        <code>function your_icon_override(){<br/>
            &nbsp;&nbsp;return 'http://your-domain.com/wp-content/uploads/2016-08/my-top-icon.svg';<br/>
        }</code>

        </div>
        <div class="brozzme-info">
        <p><b><?php _e('You can see  Brozzme (Benoti) plugins on ', $this->plugin_text_domain);?> <a href="https://profiles.wordpress.org/benoti/#content-plugins">WordPress.org</a></b></p>
        <p><b><?php _e('You\'ll very kind to put rate 5-star the plugins that you use on wp.org, it would make me very happy and would encourage other users to use them.', $this->plugin_text_domain);?> <a href="https://profiles.wordpress.org/benoti/#content-plugins">WordPress.org</a></b></p>
        </div>
        <?php

    }
}

if (class_exists('brozzme_scrolltop_settings')) {
    new brozzme_scrolltop_settings;
}