<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Ciudad</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-view" enctype="multipart/form-data">
      
        @csrf


        <div class="row">
            <!-- <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>N° de contrato</b></label>
                  <input type="text" name="numero_contrato" id="numero_contrato-view" class="form-control  form-control-user" required>
              </div>
           </div> -->


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Cirugia</b></label>
                  <input type="text" name="cirugia" id="cirugia-view" class="form-control  form-control-user" required>
              </div>
          </div>


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Clinica</b></label>
                  <select name="clinica" id="clinica-view" class="form-control select2" required>
                      <option value="">Seleccione</option>
                  </select>
              </div>
          </div>

          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Asesora</b></label>
                  <select  id="asesora-view" class="form-control select2" required>
                      <option value="">Seleccione</option>
                  </select>
              </div>
          </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card-header py-3">
                 <h6 class="m-0 font-weight-bold text-primary">Agenda</h6>
                </div>
            </div>
        </div>

        <!-- <br>

        <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Fecha</b></label>
                  <input type="date" name="fecha" id="fecha-view" class="form-control  form-control-user" required min="<?= date('Y-m-d')?>">
              </div>
           </div>


           <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Cirujano</b></label>
                  <input type="text" name="cirujano" id="cirujano-view" class="form-control  form-control-user" required>
              </div>
          </div>


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Enfermera</b></label>
                  <input type="text" name="enfermera" id="enfermera-view" class="form-control  form-control-user" required>
              </div>
          </div>


          <div class="col-md-3">
              <div class="form-group">
                  <label for=""><b>Descripcion</b></label>
                  <input type="text" name="descripcion" id="descripcion-view" class="form-control  form-control-user" required>
              </div>
          </div>


          <div class="col-md-12" style="padding-top: 25px;">
            <button type="button" class="btn btn-primary waves-effect pull-left" onclick="addAppointment('#tableRegistrar')">Agregar</button>
          </div>

        </div>

        <br> -->

        <div class="row">
          <div class="col-sm-12">
            <table class="table table-bordered table-striped table-hover" id="tableView">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Hora desde</th>
                  <th>Hora Hasta</th>
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

