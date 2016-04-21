<?php
	
	/*

	Append the following lines to the function install() present in this file
	before the line 154 - $this->dao->select('pk_i_id') ; 
	
	*/
	
	osc_set_preference('simplepay_theme_color','','payment','boolean');
	osc_set_preference('simplepay_custom_img','','payment','STRING');
	osc_set_preference('simplepay_description','','payment','STRING');
	osc_set_preference('simplepay_public_test_api_key', payment_crypt(''), 'payment', 'STRING');
	osc_set_preference('simplepay_private_test_api_key', payment_crypt(''), 'payment', 'STRING');
	osc_set_preference('simplepay_public_live_api_key', payment_crypt(''), 'payment', 'STRING');
	osc_set_preference('simplepay_private_live_api_key', payment_crypt(''), 'payment', 'STRING');
	osc_set_preference('simplepay_sandbox', 'sandbox', 'payment', 'STRING');
	osc_set_preference('simplepay_enabled', '0', 'payment', 'BOOLEAN');


	/*

	Append the following lines to the function unistall() present in this file
	before the "}" bracket 

	*/

	osc_delete_preference('simplepay_theme_color', 'payment');
	osc_delete_preference('simplepay_custom_img', 'payment');
	osc_delete_preference('simplepay_description', 'payment');
	osc_delete_preference('simplepay_test_public_api_key', 'payment');
	osc_delete_preference('simplepay_test_private_api_key', 'payment');
	osc_delete_preference('simplepay_public_api_key', 'payment');
	osc_delete_preference('simplepay_private_api_key', 'payment');
	osc_delete_preference('simplepay_sandbox', 'payment');
	osc_delete_preference('simplepay_enabled', 'payment');


?>
