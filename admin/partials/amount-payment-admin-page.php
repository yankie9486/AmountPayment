<?php

?>
<div class="wrap">

    <label for="products">Choose a product to add to cart for amount payment</label>
    <select name="products" id="amount_payment_product">
        <option value="volvo">Volvo</option>
        <?php foreach ($products as $product) : ?>
            <?php var_dump($product); ?>
        <?php endforeach; ?>
    </select>
</div>