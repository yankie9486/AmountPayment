<?php

?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __('Amount Payment', $this->plugin_name); ?></h1>
    <p>Add a payment amount field to the checkout page. Add a class to a button or link with <code>.add_to_cart_payment</code> to add product to cart. <br />The selected product below will be added to the cart and redirected to the checkout page with the amount paymnt field added at the top.</p>
    <hr class="wp-header-end">
    <form action="options.php" method="post">
        <?php
        settings_fields('amount_payment_settings');
        do_settings_sections(__FILE__);

        $options = get_option('amount_payment_settings');
        ?>

        <p>
            <label><span id="amount_payment_product">Choose a product to add to cart for amount payment</span>
                <select name="amount_payment_settings[amount_payment_product]" id="amount_payment_product" aria-labelledby="amount_payment_product">
                    <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product->get_id(); ?>" <?php echo (isset($options['amount_payment_product']) && intval($options['amount_payment_product']) == $product->get_id()) ? 'selected="selected"' : ''; ?>> <?php echo $product->get_name(); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <input type="submit" value="Save">
        </p>

    </form>

</div>