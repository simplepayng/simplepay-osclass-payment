<?php

    /*

    Append the following lines to the function payment_buttons($amount = '0.00', $description = '', $itemnumber = '101', $extra_array = null) (line 109) present in this file
    and before the "}" bracket

    */

    if(osc_get_preference('simplepay_enabled', 'payment')==1) {
        SimplePayPayment::button($amount, $description, $itemnumber, $extra_array);
    };

    /*

    Append the following line to the function payment_buttons_js() (line 127) present in this file
    and before the "}" bracket

    */

    if(osc_get_preference('simplepay_enabled', 'payment')==1) { SimplePayPayment::dialogJS(); };

?>
