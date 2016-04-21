<?php

    /*

    Append the following lines before the "}" (line 44) of the statement "if(osc_get_preference('coinjar_enabled', 'payment')==1)" (line 38)

    */

    if(osc_get_preference('simplepay_enabled', 'payment')==1) {
      require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'payments/simplepay/SimplePayPayment.php';
      osc_add_hook('ajax_simplepay', array('SimplePayPayment', 'ajaxPayment'));
    }

    /*

    Append the following lines to the function payment_load_lib() (line 73) between
    the "}" (line 87) and the "}" (line 88)

    */


    if(osc_get_preference('simplepay_enabled', 'payment')==1) {
        osc_register_script('simplepay', 'https://checkout.simplepay.ng/simplepay.js', array('jquery'));
        osc_enqueue_script('simplepay');
    }

?>
