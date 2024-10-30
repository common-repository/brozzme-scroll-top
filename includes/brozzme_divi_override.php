<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 29/03/2017
 * Time: 15:00
 */
class brozzme_divi__back_to_top_override
{

    /**
     * brozzme_divi__back_to_top_override constructor.
     */
    public function __construct()
    {
        $this->general_options = get_option('bst_general_settings');

        if($this->general_options['bst_enable'] == 'true'){
            $this->_init();
        }

    }

    /**
     * disable divi_back_to_top option if the activate theme is Divi
     */
    public function _init(){
        
        if($this->is_theme() == true){

            $et_divi_options = get_option('et_divi');

            if($et_divi_options){

                if($et_divi_options['divi_back_to_top'] == 'on'){

                    $et_divi_options['divi_back_to_top'] = 'off';

                    update_option('et_divi', $et_divi_options );
                }
            }
        }
    }


    /**
     * Check if the theme is DIVI
     * @return bool
     */
    public static function is_theme(){
        $the_theme = wp_get_theme();
        
        if($the_theme->get_template() == 'Divi'){
            return true;
        }
    }
}

new brozzme_divi__back_to_top_override();