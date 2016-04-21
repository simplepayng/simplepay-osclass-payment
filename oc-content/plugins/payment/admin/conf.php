<?php

    /*

    Append the following lines between the condition "if(Params::getParam('plugin_action')=='done')" (line 3)
    and the comment "// HACK : This will make possible use of the flash messages ;)" (line 49)

    */

    osc_set_preference('simplepay_theme_color',Params::getParam('simplepay_theme_color'),'payment','boolean');
    osc_set_preference('simplepay_custom_img',Params::getParam('simplepay_custom_img'),'payment','STRING');
    osc_set_preference('simplepay_description',Params::getParam('simplepay_description'),'payment','STRING');
    osc_set_preference('simplepay_public_test_api_key', payment_crypt(Params::getParam("simplepay_public_test_api_key")), 'payment', 'STRING');
    osc_set_preference('simplepay_private_test_api_key', payment_crypt(Params::getParam("simplepay_private_test_api_key")), 'payment', 'STRING');
    osc_set_preference('simplepay_public_live_api_key', payment_crypt(Params::getParam("simplepay_public_live_api_key")), 'payment', 'STRING');
    osc_set_preference('simplepay_private_live_api_key', payment_crypt(Params::getParam("simplepay_private_live_api_key")), 'payment', 'STRING');
    osc_set_preference('simplepay_sandbox', Params::getParam("simplepay_sandbox") ? Params::getParam("simplepay_sandbox") : '0', 'payment', 'BOOLEAN');
    osc_set_preference('simplepay_enabled', Params::getParam("simplepay_enabled") ? Params::getParam("simplepay_enabled") : '0', 'payment', 'BOOLEAN');

    /*

    Append the following line after the html tag "<select name='currency' id='currency'>" (line 141)
    and before the respective closing one (line 160) .

    */

?>

    <option value="NGN" <?php if(osc_get_preference('currency', 'payment')=="NGN") { echo 'selected="selected"';}; ?>>NGN</option>

<?php

    /*

    Append the following lines between the html tag "</div>" (line 379)
    and the "<div class="clear"></div>" (line 380)

    */

    <h2 class="render-title separate-top"><?php _e('SimplePay settings', 'payment'); ?> <span><a href="javascript:void(0);" onclick="$('#dialog-simplepay').dialog('open');" ><?php _e('help', 'payment'); ?></a></span> <span style="font-size: 0.5em" ><a href="javascript:void(0);" onclick="$('.simplepay').toggle();" ><?php _e('Show options', 'payment'); ?></a></span></h2>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Enable SimplePay'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <label>
                    <input type="checkbox" <?php echo (osc_get_preference('simplepay_enabled', 'payment') ? 'checked="true"' : ''); ?> name="simplepay_enabled" value="1" />
                    <?php _e('Enable SimplePay as a method of payment', 'payment'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Enable Sandbox'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <label>
                    <input type="checkbox" <?php echo (osc_get_preference('simplepay_sandbox', 'payment') ? 'checked="true"' : ''); ?> name="simplepay_sandbox" value="1" />
                    <?php _e('Enable sandbox for development testing', 'payment'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Public Test API Key', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_public_test_api_key" value="<?php echo payment_decrypt(osc_get_preference('simplepay_public_test_api_key', 'payment')); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Private Test API Key', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_private_test_api_key" value="<?php echo payment_decrypt(osc_get_preference('simplepay_private_test_api_key', 'payment')); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Public Live API Key', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_public_live_api_key" value="<?php echo payment_decrypt(osc_get_preference('simplepay_public_live_api_key', 'payment')); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Private Live API Key', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_private_live_api_key" value="<?php echo payment_decrypt(osc_get_preference('simplepay_private_live_api_key', 'payment')); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Custom CheckOut Image', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_custom_img" value="<?php echo osc_get_preference('simplepay_custom_img', 'payment'); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Description', 'payment'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="simplepay_description" value="<?php echo osc_get_preference('simplepay_description', 'payment'); ?>" /></div>
    </div>
    <div class="form-row simplepay hide">
        <div class="form-label"><?php _e('Dark theme color'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <label>
                    <input type="checkbox" <?php echo (osc_get_preference('simplepay_theme_color', 'payment') ? 'checked="true"' : ''); ?> name="simplepay_theme_color" value="1" />
                </label>
            </div>
        </div>
    </div>

?>
