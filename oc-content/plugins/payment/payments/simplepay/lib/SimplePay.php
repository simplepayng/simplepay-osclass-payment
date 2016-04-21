<?php

// Dependencies

if (!function_exists('curl_init')) {
    throw new Exception('Stripe needs the CURL PHP extension.');
}

// Resources

require(dirname(__FILE__) . '/Verifify.php');


?>