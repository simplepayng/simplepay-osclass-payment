<?php

Class SimplePayPayment
{

    public function __construct() { }

    public static function button($amount = '0.00', $description = '', $itemnumber = '101', $extra_array = null) {
        $extra = payment_prepare_custom($extra_array);
        $extra .= 'concept,'.$description.'|';
        $extra .= 'product,'.$itemnumber.'|';
        $r = rand(0,1000);
        $extra .= 'random,'.$r;

        $public_key = osc_get_preference('simplepay_public_live_api_key', 'payment');
        $button = osc_get_preference('simplepay_theme_color', 'payment') ? "pay_with_simplepay_dark.png" : "pay_with_simplepay_light.png" ;

        $button_src = osc_base_url() . 'oc-content/plugins/' . osc_plugin_folder(__FILE__) .'/img/'. $button;

        if(osc_get_preference('simplepay_sandbox', 'payment')){
            $public_key = osc_get_preference('simplepay_public_test_api_key', 'payment');
        }
        
        echo '
            <script>
            
                // gateway token callback
            
                function processPayment (token) {
                    verify_payment(token);
                }

                // Configuration of the gateway

                var handler = SimplePay.configure({
                   token: processPayment, 
                   key: \''.payment_decrypt($public_key).'\',
                   image: \''. osc_get_preference('simplepay_custom_img', 'payment') .'\',
                   plateform : \'osclass\'
                   
                });

            </script>
            
            <!-- Simplepay Button Implementation -->
            
            <li class="payment simplepay-btn">
                <a href="javascript:simplepay_gateway(\''.$amount.'\',\''.$description.'\',\''.$itemnumber.'\',\''.$extra.'\')">
                    <img src="'. $button_src.'"/>
                </a>
            </li>';

    }

    public static function dialogJS() {

        // Get the User Info
        $user_info = User::newInstance()->findByEmail(osc_logged_user_email());

        // Get the Merchant custom description
        $description = osc_get_preference('simplepay_description', 'payment');

        ?>
            <!-- Ajax Verification form -->
            <form action="<?php echo osc_base_url(true); ?>" method="POST" id="simplepay-payment-form">
                <input type="hidden" name="page" value="ajax" />
                <input type="hidden" name="action" value="runhook" />
                <input type="hidden" name="hook" value="simplepay" />
                <input type="hidden" name="amount" value="" id="simplepay-amount"/>
                <input type="hidden" name="extra" value="" id="simplepay-extra" />
            </form>
            <div id="simplepay-results"></div>

            <script>

                // gateway checkout popup with the client information

                function simplepay_gateway(amount, description, itemnumber, extra) {

                    $('#simplepay-extra').attr('value', extra);
                    $('#simplepay-amount').attr('value',SimplePay.amountToLower(amount));


                    handler.open(SimplePay.CHECKOUT,
                    {

                        email: '<?php echo $user_info['s_email']; ?>',
                        phone: '<?php echo $user_info['s_phone_land']; ?>',
                        description: '<?php if($description==''){ ?>'+description+'<?php } else { echo $description; } ?>',
                        address: '<?php echo $user_info['s_address']; ?>',
                        postal_code: '<?php echo $user_info['s_zip']; ?>',
                        city: '<?php echo $user_info['s_city']; ?>',
                        country: '',
                        amount: SimplePay.amountToLower(amount),
                        currency: '<?php echo osc_get_preference("currency", "payment"); ?>'

                    });

                }

                // ajax request to validade the payment

                function verify_payment(token){

                    var payment_token = $('<input type=hidden name=simplepayToken />').val(token);
                    var form = $('#simplepay-payment-form');

                    form.append(payment_token);

                    $.post(form.attr('action'), form.serialize(), function (data) {
                        $('#simplepay-results').html(data);
                    }).error(function(){
                        $('.wrapper-flash').append("<div class='flashmessage flashmessage-warning'><?php echo _e('There were an error processing your payment', 'payment'); ?></div>");
                    });

                }


            </script>

        <?php
    }

    public static  function ajaxPayment() {

        // Verification with data supplied by ajax
        $payment_response = self::processPayment();

        // Response from the Payment
        $status = $payment_response[0];
        $product_type = $payment_response[1];

        if($status==PAYMENT_COMPLETED){

            //Inform the user that the payment proceed correctly
            osc_add_flash_ok_message(sprintf(__('Success! Please write down this transaction ID in case you have any problem: %s', 'payment'), Params::getParam('simplepay_transaction_id')));


            //Add the transation to the DB
            if($product_type[0]==101){

                $item = Item::newInstance()->findByPrimaryKey($product_type[2]);
                $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);
                View::newInstance()->_exportVariableToView('category', $category);
                payment_js_redirect_to(osc_search_category_url());

            } else if($product_type[0]==201) {

                if(osc_is_web_user_logged_in()) {

                    payment_js_redirect_to(osc_route_url('payment-user-menu'));

                } else {

                    View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey($product_type[2]));
                    payment_js_redirect_to(osc_item_url());

                }

            } else {

                payment_js_redirect_to(osc_base_path());

            }


        }else{
            //Inform the user that there was an error in the Merchant configuration, it wasn't possible verify the payment
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error");
            exit;

        }


    }

    public static function processPayment() {
        require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'lib/SimplePay.php';

        //Merchant Private Api key Loading
        $pr_key = osc_get_preference('simplepay_private_test_api_key', 'payment');

        if(osc_get_preference('simplepay_sandbox', 'payment')==0) {
            $pr_key = osc_get_preference('simplepay_private_live_api_key', 'payment');
        }

        //Verify args
        $verify_input_data = array (
            'token' => Params::getParam('simplepayToken'),
            'amount' => Params::getParam('amount'),
            'currency' => osc_get_preference("currency", "payment")
        );

        // Amount Check
        if($verify_input_data['amount']<=0) {
            return PAYMENT_FAILED;
        }

        // Response from the Verification
        $verify_response = SimplePayVerify::verifyPayment($verify_input_data,$pr_key);

        if($verify_response[0] == '200'){
            $verify_data = json_decode(chop($verify_response[1]), TRUE);

            // Correct Verification Status
            if ($verify_data['response_code']=='20000'){

                Params::setParam('simplepay_transaction_id', $verify_data['id']);

                $data = payment_get_custom(Params::getParam('extra'));
                $product_type = explode('x', $data['product']);

                $payment_id = ModelPayment::newInstance()->saveLog(
                    $data['concept'],
                    $verify_data['id'],
                    $verify_data['amount'],
                    'NGN',
                    $data['email'],
                    $data['user'],
                    $data['itemid'],
                    $product_type[0],
                    'SIMPLEPAY'
                );

                // Registration of the purchase in OsClass Payment DB

                if ($product_type[0] == '101') {
                    ModelPayment::newInstance()->payPublishFee($product_type[2], $payment_id);
                } else if ($product_type[0] == '201') {
                    ModelPayment::newInstance()->payPremiumFee($product_type[2], $payment_id);
                } else {
                    ModelPayment::newInstance()->addWallet($data['user'], $verify_data['amount']);
                }

                return Array(PAYMENT_COMPLETED,$product_type);

            }

        }

        //Wrong Verification Status

        error_log("Error verifing the payment error-> ".json_encode($verify_response[1]));
        return PAYMENT_FAILED;

    }

}

?>