<div <?= $ship_attrs ?>>
    <div class="row justify-content-center">
        <div class="col-12">
            <form action="#" method="post" class="card p-4" id="shipment-form">
                <h2>Envío</h2>

                <div class="row">

                    <div class="form-group mb-3 col-12">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" placeholder="Dirección" id="address" name="address">
                    </div>
                    
                    <div class="form-group mb-3 col-12">
                        <label for="colonia">Colonia</label>
                        <input type="text" class="form-control" placeholder="Colonia" id="colonia" name="colonia">
                    </div>

                    <div class="form-group mb-3 col-12">
                        <label for="city">Ciudad</label>
                        <input type="text" class="form-control" placeholder="Ciudad" id="city" name="city">
                    </div>

                    <div class="form-group mb-3 col-12 col-sm-6">
                        <label for="state">Estado</label>
                        <select id="state" class="form-control" name="state">
                            <option value="Aguascalientes">Aguascalientes</option>
                            <option value="Baja California">Baja California</option>
                            <option value="Baja California Sur">Baja California Sur</option>
                            <option value="Campeche">Campeche</option>
                            <option value="Chiapas">Chiapas</option>
                            <option value="Chihuahua">Chihuahua</option>
                            <option value="CDMX" selected>Ciudad de México</option>
                            <option value="Coahuila">Coahuila</option>
                            <option value="Colima">Colima</option>
                            <option value="Durango">Durango</option>
                            <option value="Estado de México">Estado de México</option>
                            <option value="Guanajuato">Guanajuato</option>
                            <option value="Guerrero">Guerrero</option>
                            <option value="Hidalgo">Hidalgo</option>
                            <option value="Jalisco">Jalisco</option>
                            <option value="Michoacán">Michoacán</option>
                            <option value="Morelos">Morelos</option>
                            <option value="Nayarit">Nayarit</option>
                            <option value="Nuevo León">Nuevo León</option>
                            <option value="Oaxaca">Oaxaca</option>
                            <option value="Puebla">Puebla</option>
                            <option value="Querétaro">Querétaro</option>
                            <option value="Quintana Roo">Quintana Roo</option>
                            <option value="San Luis Potosí">San Luis Potosí</option>
                            <option value="Sinaloa">Sinaloa</option>
                            <option value="Sonora">Sonora</option>
                            <option value="Tabasco">Tabasco</option>
                            <option value="Tamaulipas">Tamaulipas</option>
                            <option value="Tlaxcala">Tlaxcala</option>
                            <option value="Veracruz">Veracruz</option>
                            <option value="Yucatán">Yucatán</option>
                            <option value="Zacatecas">Zacatecas</option>
                        </select>
                    </div>

                    <div class="form-group mb-3 col-12 col-sm-6">
                        <label for="cp">Código postal</label>
                        <input type="text" class="form-control" placeholder="Código postal" id="cp" name="cp">
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>