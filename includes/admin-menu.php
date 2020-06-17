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
                    <input type="checkbox" class="custom-control-input" id="BasicIntegration" name="BasicIntegration" aria-describedby="basicIntegrationHelp" <?= $basic_integration ?>>
                    <label class="custom-control-label" for="BasicIntegration">Integración básica</label>
                    <small id="basicIntegrationHelp" class="form-text text-muted">Esta integración te permite usar los botones y el formulario de pago por defecto de PayPal</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="AdvancedIntegration" name="AdvancedIntegration" aria-describedby="advancedIntegrationHelp" <?= $advanced_integration ?>>
                    <label class="custom-control-label" for="AdvancedIntegration">Integración avanzada</label>
                    <small id="advancedIntegrationHelp" class="form-text text-muted">Con esta integración podrás usar nuestro formulario personalizado de cobro mediante el uso de shortcodes</small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input" id="InProduction" name="InProduction" aria-describedby="InProductionHelp" <?= $production_mode ?>>
                    <label class="custom-control-label" for="InProduction">Modo producción</label>
                    <small id="InProductionHelp" class="form-text text-muted">Cuando lo actives podrás empezar a generar cobros reales</small>
                </div>

            </fieldset>

            <hr>

            <fieldset id="basic-config-sandbox" class="sandbox basic-integration <?= $hide_sandbox_basic ?>">

                <legend>Autenticación</legend>

                <div class="form-group">
                    <label for="sandboxAccount">Sandbox Account:</label>
                    <input type="text" class="form-control" id="sandboxAccount" name="sandboxAccount" aria-describedby="sandboxAccountHelp" placeholder="Sandbox Account" value="<?= $sandbox_basic_account ?>">
                    <small id="sandboxAccountHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="clientId">Client ID:</label>
                    <input type="text" class="form-control" id="clientId" name="clientId" aria-describedby="clientIdHelp" placeholder="Client ID" value="<?= $sandbox_basic_client_id ?>">
                    <small id="clientIdHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="secret">Secret:</label>
                    <input type="password" class="form-control" id="secret" name="secret" aria-describedby="secretHelp" placeholder="Secret" value="<?= $sandbox_basic_secret ?>">
                    <small id="secretHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                </div>

            </fieldset>

            <fieldset id="advanced-config-sandbox" class="sandbox advanced-integration <?= $hide_sandbox_advanced ?>">

                <legend>Credenciales API</legend>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" aria-describedby="usernameHelp" placeholder="Username" value="<?= $sandbox_advanced_username ?>">
                    <small id="usernameHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Password" value="<?= $sandbox_advanced_password ?>">
                    <small id="passwordHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

                <div class="form-group">
                    <label for="signature">Signature:</label>
                    <input type="password" class="form-control" id="signature"  name="signature" aria-describedby="signatureHelp" placeholder="Signature" value="<?= $sandbox_advanced_signature ?>">
                    <small id="signatureHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> de prueba creadas.</small>
                </div>

            </fieldset>

            <fieldset id="basic-config" class="production basic-integration <?= $hide_production_basic ?>">

                <legend>Autenticación</legend>

                <div class="form-group">
                    <label for="p-Account">Cuenta:</label>
                    <input type="text" class="form-control" id="p-Account" name="p-Account" aria-describedby="p-AccountHelp" placeholder="Cuenta" value="<?= $production_basic_account ?>">
                    <small id="p-AccountHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> creadas.</small>
                </div>

                <div class="form-group">
                    <label for="p-clientId">Client ID:</label>
                    <input type="text" class="form-control" id="p-clientId" name="p-clientId" aria-describedby="p-clientIdHelp" placeholder="Client ID" value="<?= $production_basic_client_id ?>">
                    <small id="p-clientIdHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> creadas.</small>
                </div>

                <div class="form-group">
                    <label for="p-secret">Secret:</label>
                    <input type="password" class="form-control" id="p-secret" name="p-secret" aria-describedby="p-secretHelp" placeholder="Secret" value="<?= $production_basic_secret ?>">
                    <small id="p-secretHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/applications/" target="_blank">tus aplicaciones</a> de prueba creadas.</small>
                </div>

            </fieldset>

            <fieldset id="advanced-config" class="production advanced-integration <?= $hide_production_advanced ?>">

                <legend>Credenciales API</legend>

                <div class="form-group">
                    <label for="p-username">Username:</label>
                    <input type="text" class="form-control" id="p-username" name="p-username" aria-describedby="p-usernameHelp" placeholder="Username" value="<?= $production_advanced_username ?>">
                    <small id="p-usernameHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> creadas.</small>
                </div>

                <div class="form-group">
                    <label for="p-password">Password:</label>
                    <input type="password" class="form-control" id="p-password" name="p-password" aria-describedby="p-passwordHelp" placeholder="Password" value="<?= $production_advanced_password ?>">
                    <small id="p-passwordHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> creadas.</small>
                </div>

                <div class="form-group">
                    <label for="p-signature">Signature:</label>
                    <input type="password" class="form-control" id="p-signature"  name="p-signature" aria-describedby="p-signatureHelp" placeholder="Signature" value="<?= $production_advanced_signature ?>">
                    <small id="p-signatureHelp" class="form-text text-muted">Puedes obtenerlo en <a href="https://developer.paypal.com/developer/accounts/" target="_blank">tus cuentas</a> creadas.</small>
                </div>

            </fieldset>

            <div class="button-container align-right">
                <button type="submit" name="submit" id="submit" class="btn btn-primary">Guardar cambios</button>
            </div>

        </form>

    </div>

</div>