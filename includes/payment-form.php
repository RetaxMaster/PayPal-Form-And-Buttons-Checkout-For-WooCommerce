<div <?= $form_attrs ?>>
    <div class="row justify-content-center">
        <div class="col-12">
            <form class="card p-4" id="rm-paypal-checkout-card-form">
                <h2>Completa tu pedido</h2>
                <p>Para completar la suscripción a tu plan debes ligar una tarjeta de crédito a tu cuenta. No haremos ningún cargo sino hasta que tu periodo de prueba haya terminado.</p><br>

                <div class="row">

                    <div class="input-group mb-3 col-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Nombre completo" aria-label="Full Name" aria-describedby="HolderName" id="HolderName">
                    </div>

                    <div class="input-group mb-3 col-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-credit-card"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Número de tarjeta" aria-label="Card number" aria-describedby="CreditCardNumber" id="CreditCardNumber">
                    </div>

                    <div class="input-group mb-3 col-md-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="CVC" aria-label="cvcInput" aria-describedby="cvcInput" id="cvcInput">
                    </div>

                    <div class="input-group mb-3 col-md-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Fecha de expiración" aria-label="ExpireDate" aria-describedby="expirationDate" id="expirationDate">
                    </div>

                </div>

                <p>Tu periodo de prueba termina el Jueves 11 de Junio del 2020 a las 5:42p.m y a partir de ese momento cobraremos la primera suscricpión por $49.00 MXN. El cargo es automático, si no deseas continuar con la suscripción, deberás darla de baja desde tu cuenta antes del día Jueves 11 de Junio del 2020 a las 5:42p.m. </p>

                <div class="button-container">
                    <button class="btn btn-primary next-button" type="submit" id="pay-button">Confirmar pedido</button>
                </div>

                <div class="or">
                    <span class="dashed"></span>
                    <span>o</span>
                    <span class="dashed"></span>
                </div>

                <div id="rm_paypal_buttons"></div>

            </form>
        </div>
    </div>
</div>