<div class="card shadow mb-4 hidden" id="cuadro4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Editar Ciudad</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
      
        @csrf

        <input type="hidden" name="_method" value="put">
        <input type="hidden" name="id_paciente" id="paciente-edit">
        
        <div class="row">
           <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Paciente</b></label>
                  <input type="text" class="form-control" id="name_paciente-edit" disabled>
              </div>
          </div>
        </div>


        <div class="row">
            <!-- <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>N° de contrato</b></label>
                  <input type="text" name="numero_contrato" id="numero_contrato-edit" class="form-control  form-control-user" required>
              </div>
           </div> -->


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Cirugia</b></label>
                  <input type="text" name="cirugia" id="cirugia-edit" class="form-control  form-control-user" required>
              </div>
          </div>


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Clinica</b></label>
                  <select name="clinica" id="clinica-edit" class="form-control select2" required>
                      <option value="">Seleccione</option>
                  </select>
              </div>
          </div>

          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Asesora</b></label>
                  <select  id="asesora-edit" class="form-control select2" required>
                      <option value="">Seleccione</option>
                  </select>
              </div>
          </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card-header py-3">
                 <h6 class="m-0 font-weight-bold text-primary">Agendar</h6>
                </div>
            </div>
        </div>

        <br>

      <div class="row">
        
            <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Fecha</b></label>
                        <input type="date" name="fecha" id="fecha-edit" class="form-control  form-control-user">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Hora desde</b></label>
                        <input type="time" name="time" id="time-edit" class="form-control  form-control-user">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Hora hasta</b></label>
                        <input type="time" name="time_end" id="time-end-edit" class="form-control  form-control-user">
                    </div>
                </div>



              </div>

              <div class="row">
              
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Cirujano</b></label>
                        <input type="text" name="cirujano" id="cirujano-edit" class="form-control  form-control-user" >
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Enfermera</b></label>
                        <input type="text" name="enfermera" id="enfermera-edit" class="form-control  form-control-user" >
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for=""><b>Descripcion</b></label>
                        <input type="text" name="descripcion" id="descripcion-edit" class="form-control  form-control-user" >
                    </div>
                </div>
              
              </div>


              <div class="row">
              
                <div class="col-md-12" style="padding-top: 25px;">
                  <button type="button" class="btn btn-primary waves-effect pull-left" onclick="addAppointment('#tableEdit', 'edit')">Agregar</button>
                </div>
              </div>

              <br>


              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-bordered table-striped table-hover" id="tableEdit">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Hora desde</th>
                        <th>Hora hasta</th>
                        <th>Descripcion</th>
                        <th>Cirujano</th>
                        <th>Enfermera</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>




            </div>


            <div class="col-md-6">


                 <br><br>

                  <div class="row" id="comments_edit">
                      <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                              Foto
                            </div>
                            <div class="col-md-10">
                              Text
                            </div>
                        </div>
                      </div>
                  </div>


                  <div class="row">

                      <div class="col-md-2"></div>
                          
                      <div class="col-md-10">
                          <div class="form-group">
                              <label for=""><b>Comentarios</b></label>
                              <!-- <textarea name="observaciones" id="observaciones-store" class="form-control" cols="30" rows="5"></textarea> -->
                              <textarea id="summernote_edit" name="comment"></textarea>
                          </div>
                      </div>

                  </div>


                  <div class="row">

                      <div class="col-md-2">
                            
                      </div>

                      <div class="col-md-10">
                        <button type="button" id="add-comments"  class="btn btn-primary">
                          Comentar
                        </button>
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
            <button id="send_usuario_edit" class="btn btn-primary btn-user">
                Guardar
            </button>

          </center>
          <br>
          <br>
      </form>
      
    </div>

