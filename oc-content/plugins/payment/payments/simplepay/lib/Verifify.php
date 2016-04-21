<?php

    class SimplePayVerify {

        public function __construct() {}

        public static function verifyPayment($data,$pr_key){

            $data_string = json_encode($data);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.simplepay.ng/v1/payments/verify');

            curl_setopt($ch, CURLOPT_USERPWD, payment_decrypt($pr_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));

            $curl_response = curl_exec($ch);
            $curl_response = preg_split("/\r\n\r\n/",$curl_response);
            $response_content = $curl_response[1];
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return Array($response_code,$response_content);

        }

    }

?>