<?php 
  include_once '../funciones/sesiones.php';
  include_once '../funciones/bd_conexion.php';
  include_once '../templates/header.php'; 
  include_once '../templates/barra.php';
  include_once '../templates/navegacion.php';
?>
  <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Registro de pagos
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title orange-icon">Registre el pago aquí</h3>
            </div>
            <form role="form" id="registro-pago" name="registro-pago" type="post" action="../actions/modelo-caja.php" enctype="multipart/form-data">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="numero-pago">Nº de factura <i>(opcional)</i></label>
                      <input type="text" class="form-control" id="numero-pago" name="numero-pago" autocomplete="off" placeholder="ej: A-000 055879...">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="descripcion">Descripcion</label>
                      <input type="name" class="form-control" id="descripcion" name="descripcion" placeholder="Ej: Compra de bolsas en proveedor..." required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_deuda">Fecha</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" name="fecha-pago" autocomplete="off" required>
                        </div>
                    </div>
                  </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="precio-venta">Valor ($)</label>
                            <input type="text" class="form-control text-right solo-numero-cero" id="valor-pago" name="valor-pago" value="" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="precio-venta">Motivo</label>
                            <select id="motivo-pago" name="motivo-pago" class="form-control select2" Style="width: 100%;" required>
                                <option value="0">- Seleccione -</option>
                                <option value="1">Compra de productos</option>
                                <option value="2">Compra de insumos</option>
                                <option value="3">Pago a proveedores</option>
                                <option value="4">Pago de impuestos</option>
                                <option value="6">Carga de combustibles</option>
                                <option value="5">Otros pagos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre-est">Observaciones</label>
                            <input type="text" class="form-control" name="nombre-est" id="nombre-est" placeholder="Comentarios...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="si-caja" class="col-md-3">
                        <div class="form-group cent-text" style="margin-top:10px">
                            <label>¿Impacta en caja actual?</label>
                            <div class="seleccion" style="width:100%">
                                <label for="imp-caja">Si
                                <input type="radio" name="imp-caja" id="pago-si" value="si">
                                </label>
                                <label for="imp-caja">No
                                <input type="radio" name="imp-caja" id="pago-no" value="no">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="exampleInputFile">Subir recibo/factura</label>
                            <input type="file" id="imagen-pago" name="subir-pago">
                            <p class="help-block"><i>Puede subir las fotos o escaneos del pago.</i></p>
                        </div>
                    </div>
                </div>
              </div> <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="registro-modelo" value="registrar-pago">
                <button type="submit" class="btn btn-primary">Registrar</button>
              </div>
            </form>
        </div>
    </section>
  </div>

<?php include_once '../templates/footer.php'; ?>
