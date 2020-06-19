<div class="rm_paypal_checkout">

    <?php if(isset($_GET["success"]) && $_GET["success"] == "true"): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span>¡Datos guardados con éxito!</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div><br>
    <?php endif; ?>

    <h1>Configuración de PayPal</h1><br>
    
    <div class="card config-container">

        <form action="<?= esc_html( admin_url( 'admin-post.php' ) ); ?>" method="post">

            <?php wp_nonce_field('paypal_config_nonce', 'paypal_checkout_config'); ?>
            <?php //Con este input podemos definir la acción en la que nos engancharemos para salvar las información en admin-post.php usando add_action("admin_post_ElValueDelInput") ?>
            <input name='action' type="hidden" value='rm_paypal_checkout_saving'>

            <fieldset>

                <legend>Tipo de integración</legend>

                <p class="text-muted">Primero tienes que elegir qué tipo de integración te gustaría tener en tu página, debajo de cada una te damos una pequeña descripción, si deseas más información de cada una te recomendamos mirar los <a href="https://developer.paypal.com/docs/" target="_blank">tipos de integración</a> de PayPal, ten en cuenta que cada tipo de integración dependerá del país en el que te encuentres.</p><br>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="PayPalCheckout" name="PayPalCheckout" aria-describedby="PayPalCheckoutHelp" <?= $PayPalCheckout ?>>
                    <label class="custom-control-label" for="PayPalCheckout">PayPal Checkout</label>
                    <small id="PayPalCheckoutHelp" class="form-text text-muted"><i>(Recomendado por defecto)</i> Añade los botones de PayPal a tu sitio web y permite que tus clientes puedan pagar con su cuenta de PayPal, PayPal Credit o usando una tarjeta con el formulario de PayPal.</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="PayPalPlusMexicoBrazil" name="PayPalPlusMexicoBrazil" aria-describedby="PayPalPlusMexicoBrazilHelp" <?= $PayPalPlusMexicoBrazil ?>>
                    <label class="custom-control-label" for="PayPalPlusMexicoBrazil">PayPal Plus (Mexico and Brazil)</label>
                    <small id="PayPalPlusMexicoBrazilHelp" class="form-text text-muted">Añade un formulario de tarjetas creado por PayPal en tu sitio web, realiza el cobro sin ventanas emergentes y sin salir de tu página.</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="PaymentExperience" name="PaymentExperience" aria-describedby="PaymentExperienceHelp" <?= $PaymentExperience ?>>
                    <label class="custom-control-label" for="PaymentExperience">Payment Experience</label>
                    <small id="PaymentExperienceHelp" class="form-text text-muted">Redirige a tus clientes a una página de pago de PayPal personalizada para tu negocio con tu nombre y logo.</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="InProduction" name="InProduction" aria-describedby="InProductionHelp" <?= $production_mode ?>>
                    <label class="custom-control-label" for="InProduction">Modo producción</label>
                    <small id="InProductionHelp" class="form-text text-muted">Cuando lo actives podrás empezar a generar cobros reales</small>
                </div>

            </fieldset>

            <hr>
            
            <div class="sandbox <?= $show_sandbox ?>">    

                <fieldset id="authenticate" class="authenticate">

                    <legend>Autenticación para el modo de pruebas</legend>

                    <div class="form-group client-id <?= $show_client_id ?>">
                        <label for="clientId">Client ID:</label>
                        <input type="text" class="form-control" id="clientId" name="clientId" aria-describedby="clientIdHelp" placeholder="Client ID" value="<?= $sandbox_client_id ?>">
                        <small id="clientIdHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                    </div>

                    <div class="form-group client-secret <?= $show_client_secret ?>">
                        <label for="secret">Secret:</label>
                        <input type="password" class="form-control" id="secret" name="secret" aria-describedby="secretHelp" placeholder="Secret" value="<?= $sandbox_secret ?>">
                        <small id="secretHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                    </div>

                </fieldset>

            </div>
            
            <div class="production <?= $show_production ?>">    
                <fieldset id="p_authenticate" class="authenticate">

                    <legend>Autenticación para el modo de producción</legend>

                    <div class="form-group client-id <?= $show_client_id ?>">
                        <label for="p_clientId">Client ID:</label>
                        <input type="text" class="form-control" id="p_clientId" name="p_clientId" aria-describedby="p_clientIdHelp" placeholder="Client ID" value="<?= $production_client_id ?>">
                        <small id="p_clientIdHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> creadas.</small>
                    </div>

                    <div class="form-group client-secret <?= $show_client_secret ?>">
                        <label for="p_secret">Secret:</label>
                        <input type="password" class="form-control" id="p_secret" name="p_secret" aria-describedby="p_secretHelp" placeholder="Secret" value="<?= $production_secret ?>">
                        <small id="p_secretHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> creadas.</small>
                    </div>

                </fieldset>
            </div>


            <div class="button-container align-right">
                <button type="submit" name="submit" id="submit" class="btn btn-primary">Guardar cambios</button>
            </div>

        </form>

    </div>

</div>