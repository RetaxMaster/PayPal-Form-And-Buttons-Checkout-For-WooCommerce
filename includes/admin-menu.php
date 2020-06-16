<div class="rm_paypal_checkout">

    <?php if(isset($_GET["success"]) && $_GET["success"] == "true"): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span>¡Datos guardados con éxito!</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div><br>
    <?php endif; ?>
    
    <div class="card config-container">
        
        <h1>Configuración de PayPal</h1>

        <form action="<?= esc_html( admin_url( 'admin-post.php' ) ); ?>" method="post">

            <?php wp_nonce_field('paypal_config_nonce', 'paypal_checkout_config'); ?>
            <?php //Con este input podemos definir la acción en la que nos engancharemos para salvar las información en admin-post.php usando add_action("admin_post_ElValueDelInput") ?>
            <input name='action' type="hidden" value='rm_paypal_checkout_saving'>

            <fieldset>

                <legend>Tipo de integración</legend>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="BasicIntegration" name="BasicIntegration" aria-describedby="basicIntegrationHelp" checked>
                    <label class="custom-control-label" for="BasicIntegration">Integración básica</label>
                    <small id="basicIntegrationHelp" class="form-text text-muted">Esta integración te permite usar los botones y el formulario de pago por defecto de PayPal</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="AdvancedIntegration" name="AdvancedIntegration" aria-describedby="advancedIntegrationHelp">
                    <label class="custom-control-label" for="AdvancedIntegration">Integración avanzada</label>
                    <small id="advancedIntegrationHelp" class="form-text text-muted">Con esta integración podrás usar nuestro formulario personalizado de cobro mediante el uso de shortcodes</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="InProduction" name="InProduction" aria-describedby="InProductionHelp">
                    <label class="custom-control-label" for="InProduction">Modo producción</label>
                    <small id="InProductionHelp" class="form-text text-muted">Cuando lo actives podrás empezar a generar cobros reales</small>
                </div>

            </fieldset>

            <hr>

            <fieldset id="basic-config">

                <legend>Autenticación</legend>

                <div class="form-group">
                    <label for="clientId">Cliend ID:</label>
                    <input type="text" class="form-control" id="clientId" name="clientId" aria-describedby="clientIdHelp" placeholder="Username" value="<?= get_option("rm_paypal_checkout_client_id", "") ?>">
                    <small id="clientIdHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                </div>

            </fieldset>

            <fieldset id="advanced-config">

                <legend>Credenciales API</legend>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" aria-describedby="usernameHelp" placeholder="Username" value="<?= get_option("rm_paypal_checkout_username", "") ?>">
                    <small id="usernameHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Password" value="<?= get_option("rm_paypal_checkout_password", "") ?>">
                    <small id="passwordHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="signature">Signature:</label>
                    <input type="password" class="form-control" id="signature"  name="signature" aria-describedby="signatureHelp" placeholder="Signature" value="<?= get_option("rm_paypal_checkout_signature", "") ?>">
                    <small id="signatureHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

            </fieldset>

            <div class="button-container align-right">
                <button type="submit" name="submit" id="submit" class="btn btn-primary">Guardar cambios</button>
            </div>

        </form>

    </div>

</div>