<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 01/11/2018
 * Time: 05:00
 */
class brozzme_oceanwp__back_to_top_override
{

    /**
     * brozzme_oceanwp__back_to_top_override constructor.
     */
    public function __construct()
    {
        $this->general_options = get_option('bst_general_settings');

        if($this->general_options['bst_enable'] == 'true'){
            $this->_init();
        }
    }

    /**
     * disable ocean_scroll_top option if the activate theme is OceanWP
     */
    public function _init(){
        
        if($this->is_theme() == true){
            $et_oceanwp_options = get_option('theme_mods_oceanwp');
            if($et_oceanwp_options){

                if(in_array('ocean_scroll_top', array_keys($et_oceanwp_options))){

                    if($et_oceanwp_options['ocean_scroll_top'] === false){

                    }elseif($et_oceanwp_options['ocean_scroll_top'] === true || $et_oceanwp_options['ocean_scroll_top'] === null){
                        $et_oceanwp_options['ocean_scroll_top'] = false;

                        update_option('theme_mods_oceanwp', $et_oceanwp_options );
                    }
                }else{
                    $et_oceanwp_options['ocean_scroll_top'] = false;
                }
            }
        }
    }


    /**
     * Check if the theme is oceanwp
     * @return bool
     */
    public static function is_theme(){
        $the_theme = wp_get_theme();
        
        if($the_theme->get_template() == 'oceanwp'){
            return true;
        }
    }
}

new brozzme_oceanwp__back_to_top_override();