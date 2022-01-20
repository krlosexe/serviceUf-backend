<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Valoracion</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-view" enctype="multipart/form-data">
      
        @csrf
        


        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li  class="nav-item">
                <a id="tab0" class="nav-link active" data-toggle="tab" href="#init_view" role="tab" aria-controls="init" aria-selected="true">Datos Generales</a>
            </li>
            <li  class="nav-item">
                <a id="tab1" class="nav-link" data-toggle="tab" href="#fotos-view" role="tab" aria-controls="fotos" aria-selected="false">Fotos</a>
            </li>
        </ul>

        <br><br>


        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active tab_content0" id="init_view" role="tabpanel" aria-labelledby="patient_record_edit">

              <div class="row">
                  <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                                <label for=""><b>Paciente</b></label>
                                <select name="id_cliente" id="paciente-view" class="form-control select2" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                                <label for=""><b>Fecha</b></label>
                                <input type="date" name="fecha" id="fecha-view" class="form-control select2" required min="<?= date("Y-m-d")?>">
                            </div>
                        </div>


                        <div class="col-md-6">
                          <div class="form-group">
                                <label for=""><b>Hora desde</b></label>
                                <input type="time" name="time" id="time-view" class="form-control select2" required>
                            </div>
                        </div>


                        <div class="col-md-6">
                          <div class="form-group">
                                <label for=""><b>Hora hasta</b></label>
                                <input type="time" name="time_end" id="time-end-view" class="form-control select2" required>
                            </div>
                        </div>
                      </div>
                      


                      <div class="row">

                          <div class="col-md-6">
                              <div class="form-group">
                                  <div class="form-group">
                                    <label for=""><b>Tipo</b></label>
                                    <select name="type" id="type-view" class="form-control select2" required>
                                        <option value="Al Contado">Al Contado</option>
                                        <option value="Financiado">Financiado </option>
                                    </select>
                                </div>
                              </div>
                          </div>
                          
                      </div>


                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for=""><b>Estatus</b></label>
                                <select name="status" id="status-view" class="form-control select2" required>
                                    <option value="0">Pendiente</option>
                                    <option value="1">Procesado</option>
                                    <option value="2">Cancelado</option>
                                </select>
                            </div>
                        </div>
                      </div>

                      



                  </div>


                  <div class="col-md-6">

                      <div class="col-md-12">
                          <div class="form-group">
                              <label for=""><b>Obervaciones</b></label>
                              <textarea name="observaciones" id="observaciones-view" class="form-control" cols="30" rows="5"></textarea>
                          </div>
                      </div>


                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-sm-12 text-center"> 
                                <label for=""><b>Adjuntar Cotizacion</b></label>
                                <div>
                                    <div class="file-loading">
                                        <input id="file-input-view" name="file" type="file" disabled>
                                    </div>
                                </div>
                                <div class="kv-avatar-hintss">
                                    <small>Seleccione una foto</small>
                                </div>
                            </div>
                        </div>
                      </div>




                  </div>

                </div>



            </div>


            <div class="tab-pane fade tab_content0" id="fotos-view" role="tabpanel" aria-labelledby="patient_record_edit">
              <div id="photos_view" class="row">

               </div>
            </div>


        </div>
        
        <input type="hidden" name="id_user" class="id_user">
        <input type="hidden" name="token" class="token">
          <br>
          <br>
        </div>
          <center>
            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro3')">
                Cancelar
            </button>
          </center>
          <br>
          <br>
      </form>
      
    </div>

