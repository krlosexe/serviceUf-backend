<div class="card shadow mb-4 hidden" id="cuadro4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Editar Remision de Productos</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
      
        @csrf

        <input type="hidden" name="_method" value="put">
        

        <div class="row">

          <div class="col-md-12">

             <div class="row">
                <div class="col-md-6">
                  
                    <label for=""><b>Cliente</b></label>
                    <div class="form-group valid-required">
                        <select name="id_client" class="form-control" id="clients_edit" required>
                        </select>
                    </div>
                  
                </div>
              </div>

            
              <div class="row">

                <div class="col-md-4">
                      <label for=""><b>Local Comercial</b></label>
                      <div class="form-group valid-required">
                          <select name="commercial_premises" class="form-control" id="commercial_premises_edit" required>
                            <option value="">Seleccione</option>
                            <option value="Medellin">Medellin</option>
                            <option value="Bogota">Bogota</option>
                          </select>
                      </div>
                </div>

                <div class="col-md-4">
                    <label for=""><b>Vendedor</b></label>
                    <div class="form-group valid-required">
                        <select name="id_seller" class="form-control" id="seller_edit" required>
                        </select>
                    </div>
                </div>


              </div>


              <div class="row">
                <div class="col-md-4">
                    <label for=""><b>Productos</b></label>
                      <div class="form-group valid-required">
                        <select name="products" class="form-control select2" id="products_edit"></select>
                      </div>
                </div>


                <div class="col-md-2">
                  <br>
                  <button type="button" class="btn btn-primary btn-user" id="add_product_edit">
                      Agregar
                  </button>
                </div>
              </div>
          </div>

        </div>



        
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
			                <table class="table table-bordered" id="table_products_edit" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
                            <th>Nombre</th>
                            <th>Presentacion</th>
                            <th>Precio (COP)</th>
                            <th>Salida (Cantidad)</th>
                            <th>Existencia Actual</th>
                            <th>%IVA</th>
                            <th>Total</th>
                            <th></th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
			                  </tbody>


                        <tfoot>
                          <tr>

                            <th style="text-align: center;" id="descuento_text">
                              <label for="apply_discount">¿ Quieres Aplicar Descuento (10%)?</label>
                              <input type="checkbox" id="apply_discount_edit" class="form-control">
                            </th>

                            <th colspan="6" style="text-align: right;">Subtotal 
                              <input type="hidden" name="subtotal" id="subtotal_edit"> 
                              <input type="hidden" name="subtotal_with_discount" id="subtotal_with_discount_edit"> 
                            </th>
                            <th style="text-align: right;" id="subtotal_text_edit">$0</th>
                          </tr>
                          <tr>
                            <th colspan="7" style="text-align: right;">IVA <input type="hidden" name="vat_total" id="vat_total_edit"></th>
                            <th style="text-align: right;" id="vat_total_text_edit">$0</th>
                          </tr>


                          <tr>
                            <th colspan="7" style="text-align: right;">Descuento <input type="hidden" name="discount_total" id="discount_total_edit"></th>
                            <th style="text-align: right;" id="discount_total_text_edit">$0</th>
                          </tr>


                          <tr>
                            <th style="text-align: left;">RTE Fuente</th>
                            <th style="text-align: right;"><input type="text" class="form-control discount_edit" name="rte_fuente" id="rte_fuente_edit" value="0"></th>


                            <th colspan="5" style="text-align: right;">RTE Fuente <input type="hidden" name="rte_fuente_total" id="rte_fuente_total_edit"></th>
                            <th style="text-align: right;" id="rte_fuente_text_edit">$0</th>
                          </tr>





                          <tr>
                            <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_edit"></th>
                            <th style="text-align: right;" id="total_invoice_text_edit">$0</th>
                          </tr>


                        </tfoot>



			                </table>

                      <div class="row">
                        <div class="col-md-6">
                          <label for=""><b>Observaciones</b></label>
                            <div class="form-group valid-required">
                              <textarea class="form-control" name="observations" id="observations_edit" cols="30" rows="10"></textarea>
                            </div>
                        </div>



                        <div class="col-md-6">
                         
                          
                        </div>


                      </div>



			            </div>
             </div>
        </div>

        <input type="hidden" name="inicial" id="inicial">
        <input type="hidden" name="id_user" class="id_user">
        <input type="hidden" name="token" class="token">

        <input type="hidden" name="id_user_edit" id="id_edit">


          <br>
          <br>
        </div>
          <center>

            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro4')">
                Cancelar
            </button>


            <button type="submit" type="button"  class="btn btn-info btn-user">
                Guardar
            </button>



            <button id="print" type="button"  class="btn btn-primary btn-user">
                Imprimir
            </button>





            <!--
            <button id="send_usuario" class="btn btn-primary btn-user">
                Guardar
            </button>-->

          </center>
          <br>
          <br>
      </form>
      
    </div>

