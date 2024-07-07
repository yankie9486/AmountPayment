<?php

?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __('Amount Payment', $this->plugin_name); ?></h1>
    <p>Add a payment amount field to the checkout page. Add a class to a button or link with <code>.add_to_cart_payment</code> to add product to cart. <br />The selected product below will be added to the cart and redirected to the checkout page with the amount paymnt field added at the top.</p>
    <p>On the checkout page use shortcode instead of blocks. <code>[woocommerce_checkout]</code>
        <hr class="wp-header-end">
    <form action="options.php" method="post">
        <?php
        settings_fields('amount_payment_settings');
        do_settings_sections(__FILE__);

        $options = get_option('amount_payment_settings');
        ?>

        <fieldset style="padding:10px 0;">
            <legend class="screen-reader-text"><span>Select Product</span></legend>
            <label for="amount_payment_product">
                <span><?php esc_attr_e('Choose a product to add to cart for amount payment.', $this->plugin_name); ?></span>
                <br />
                <select name="amount_payment_settings[amount_payment_product]" id="amount_payment_product">
                    <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product->get_id(); ?>" <?php echo (isset($options['amount_payment_product']) && intval($options['amount_payment_product']) == $product->get_id()) ? 'selected="selected"' : ''; ?>> <?php echo $product->get_name(); ?></option>
                    <?php endforeach; ?>
                </select>

            </label>
        </fieldset>
        <fieldset style="padding:10px 0;">
            <legend class="screen-reader-text"><span>Auto Complete Order</span></legend>
            <label for="amount_payment_auto_complete">
                <input name="amount_payment_settings[amount_payment_auto_complete]" type="checkbox" id="amount_payment_auto_complete" value="1" <?php echo (isset($options["amount_payment_auto_complete"]) && '' != $options["amount_payment_auto_complete"]) ? "checked" : ''; ?> />
                <span><?php esc_attr_e('Enable auto complete order for new orders.', $this->plugin_name); ?></span>
            </label>
        </fieldset>
        <p>
            <input class="button-primary" type="submit" value="<?php echo __('Save', $this->plugin_name); ?>">
        </p>

    </form>

</div>