<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Cirugias</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf







        <div class="row">
           <div class="col-md-6">

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for=""><b>Fecha</b></label>
                        <input type="date" name="fecha" id="fecha-store" class="form-control select2" required>
                    </div>
                </div>

                <!-- <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Hora desde</b></label>
                        <input type="time" name="time" id="time-store" class="form-control select2" required>
                    </div>
                </div>


                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Hora Hasta</b></label>
                        <input type="time" name="time_end" id="time-end-store" class="form-control select2" required>
                    </div>
                </div> -->



              </div>


              <div class="row">

                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="identificacion_verify"><b>La Fecha es Tentativa ?</b></label>
                          <label class='container-check'>
                              <input type='checkbox' name='attempt' class='checkitem chk-col-blue' id='attempt' value='1'>
                              <span class='checkmark'></span>
                              <label for=''></label>
                          </label>
                      </div>
                  </div>


                  <div class="col-md-6">
                      <div class="form-group">
                          <div class="form-group">
                            <label for=""><b>Tipo</b></label>
                            <select name="type" id="type-store" class="form-control select2" required>
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
                          <label for=""><b>Cirujano</b></label>
                          <input type="text" name="surgeon" id="surgeon-store" class="form-control" required >
                      </div>
                  </div>

                  <!-- <div class="col-md-6">
                    <div class="form-group">
                          <label for=""><b>Quirofano</b></label>
                          <input type="text" name="operating_room" id="operating_room-store" class="form-control" required >
                      </div>
                  </div> -->
              </div>


                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                              <label for=""><b>Nombre de Procedimiento</b></label>
                              <input type="text" name="surgerie_name" id="surgerie_name" class="form-control" required >
                          </div>
                      </div>
                    </div>

              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for=""><b>Seguidores</b></label>
                        <select name="followers[]" id="follower" class="form-control select2 getUsers" multiple>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label for=""><b>Estatus</b></label>
                        <select name="status_surgeries" id="status-store" class="form-control select2" required>
                            <option value="0">Pendiente</option>
                        </select>
                    </div>
                </div>
              </div>



           </div>


           <div class="col-md-6">
              <div class="row">

                    <div class="col-md-12">
                    <div class="form-group">
                            <label for=""><b>Clinica</b></label>
                            <select name="clinic" id="clinic-store" class="form-control select2" required>
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>

              </div>


              <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                        <label for=""><b>Comentarios</b></label>
                        <textarea id="summernote" name="comment"></textarea>
                    </div>
                </div>
              </div>

              <br><br>

<!--
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                        <label for=""><b>Cambiar Estado del Px</b></label>
                        <select name="state_px" id="state-filter" class="form-control select2 disabled">
                            <option value="0">Seleccione</option>
                            <option value="Afiliada">Afiliada</option>
                            <option value="Agendada">Agendada</option>
                            <option value="Aprobada">Aprobada</option>
                            <option value="Aprobada / Descartada">Aprobada / Descartada</option>
                            <option value="Asesorada No Agendada"> Asesorada No Agendada</option>
                            <option value="Asesorado por FB esperando contacto Telefonico">Asesorado por FB esperando contacto Telefonico</option>
                            <option value="Demandada">Demandada</option>
                            <option value="Descartada">Descartada</option>
                            <option value="Llamada no Asesorada">Llamada no Asesorada</option>
                            <option value="No Asistio">No Asistio</option>
                            <option value="No Contactada">No Contactada</option>
                            <option value="No Contesta">No Contesta</option>
                            <option value="Numero Equivocado">Numero Equivocado</option>
                            <option value="Operada">Operada</option>
                            <option value="Programada">Programada</option>
                            <option value="Re Agendada a Valoracion">Re Agendada a Valoracion</option>
                            <option value="Valorada">Valorada</option>
                            <option value="Valorada / Descartada">Valorada / Descartada</option>
                        </select>
                        </div>
                    </div>
                </div>
            -->

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-group">
                        <label for=""><b>Monto de la Cirugia</b></label>
                        <input type="number" name="amount" id="amount-store" class="form-control" onkeydown="noPuntoComa( event )" required>
                    </div>
                    </div>
                </div>
            </div>

            <div id="tr_additional_0" class="row">
                <div class='col-md-5'><input type='text' class='form-control' name='aditional[]' value="Faja Alta" placeholder='Faja Alta' readonly></div>
                <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_0')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
                <br><br>
            </div>

            <div id="tr_additional_1" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]' value="Faja Bidireccional" placeholder='Faja Bidireccional' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_1')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
			
           <div id="tr_additional_2" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Estabalizador" placeholder='Estabalizador' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_2')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
		    <div id="tr_additional_3" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Brasier" placeholder='Brasier' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_3')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_4" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Fajon Espuma" placeholder='Fajon Espuma' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_4')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_5" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Tabla Mariposa" placeholder='Tabla Mariposa' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_5')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
            <div id="tr_additional_6" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Flotadores" placeholder='Flotadores' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_6')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_7" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Medias Antiembolicas" placeholder='Medias Antiembolicas' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_7')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_8" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Chaleco - Torero" placeholder='Chaleco - Torero' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_8')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
            <div id="tr_additional_9" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Shorts Gluteoplastia" placeholder='Shorts Gluteoplastia' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_9')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
		    <div id="tr_additional_10" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Fragmin" placeholder='Fragmin' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_10')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_11" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Mentonera" placeholder='Mentonera' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_11')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
            <div id="tr_additional_12" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Medicamentos" placeholder='Medicamentos' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_12')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
								
		    <div id="tr_additional_13" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Balaca" placeholder='Balaca' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_13')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_14" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Camara hiperbalica" placeholder='Camara hiperbalica' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_14')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
            <div id="tr_additional_15" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="PQTE 10 mensajes" placeholder='PQTE 10 mensajes' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_15')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
								
		    <div id="tr_additional_16" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Carboxiterapia" placeholder='Carboxiterapia' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_16')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_17" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Ozono" placeholder='Ozono' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_17')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
            <div id="tr_additional_18" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Láser Sesión" placeholder='Láser Sesión' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_18')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>
				
		    <div id="tr_additional_19" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Enfermera" placeholder='Enfermera' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_19')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

		    <div id="tr_additional_20" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Ambulancia" placeholder='Ambulancia' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_20')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

           <div id="tr_additional_21" class="row">
               <div class='col-md-5'><input type='text' class='form-control' name='aditional[]'  value="Prolactina" placeholder='Prolactina' readonly></div>
               <div class='col-md-4'><input type='number' class='form-control price_aditional' onkeydown='noPuntoComa( event )' name='price_aditional[]' placeholder='Precio del adicional'></div>
                <div class='col-md-3'><span onclick="eliminarTr('#tr_additional_21')" class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></div>
               <br><br>
           </div>

                <div class="row" id="additional">

                </div>

              

                <div class="row">
                    <br>
                    <button type="button" id="add-additional" class="btn btn-primary btn-user">
                        Agregar Adicionales <i class="fa fa-plus"></i>
                    </button>
                </div>


              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-group">
                        <label for=""><b>Total</b></label>
                        <input type="number" name="amount" id="total-store" class="form-control" disabled>
                    </div>
                    </div>
                </div>
              </div>


           </div>



        </div>
        <input type="hidden"  name="id_cliente" value="{{$id_client}}">
        <input type="hidden" name="id_user" class="id_user">
        <input type="hidden" name="token" class="token">
          <br>
          <br>
        </div>
          <center>

            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro2')">
                Cancelar
            </button>
            <button id="send_usuario" class="btn btn-primary btn-user">
                Registrar
            </button>

          </center>
          <br>
          <br>
      </form>

    </div>

