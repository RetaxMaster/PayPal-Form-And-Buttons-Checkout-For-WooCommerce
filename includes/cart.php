<?php
    global $woocommerce;
    $cart = $woocommerce->cart;
?>

<div <?= $cart_attrs ?>>
    <div class="row justify-content-center details-container">
        <div class="col-12">
            <div class="details card p-4" id="cart-resumen">

                <div>
                    <h2>Tu carrito</h2>
                    
                    <div class="articles">

                        <?php foreach($cart->get_cart() as $cart_items): ?>
                    
                            <div class="article" data-product-key="<?= $cart_items["key"] ?>">
                                <span> <span class="delete"><i class="fas fa-times"></i></span> <?= $cart_items["quantity"] . " " . wc_get_product($cart_items["product_id"])->get_title() ?></span>
                                <span><?= parse_money($cart_items["line_total"]) ?></span>
                            </div>
                        
                        <?php endforeach; ?>
                    
                        <?php if($cart->is_empty()): ?>
                    
                            <div class="no-items article">
                                <p class="text-muted">Aún no hay artículos en tu carrito</p>
                            </div>
                        
                        <?php endif; ?>
                    
                    </div>
                </div>

                <div class="total">
                    <span class="tag">Total:</span>
                    <span class="numeric">
                        <span><?= $cart->get_total(); ?></span>
                    </span>
                </div>
                
            </div>
        </div>
    </div>
</div>