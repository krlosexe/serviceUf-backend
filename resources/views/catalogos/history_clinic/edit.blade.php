<div class="card shadow mb-4 hidden" id="cuadro4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edicion de pacientes</h6>
    </div>

    <div class="card-body">

        <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" onclick="getPreanestesia()">
                    <a id="tab0" class="nav-link active" id="patient_record_edit" data-toggle="tab" href="#init_edit" role="tab" aria-controls="init" aria-selected="true">Consulta Preanestésica</a>
                </li>
                <li class="nav-item" onclick="getFormQuirurgica()">
                    <a id="tab1" class="nav-link" id="information_aditionals_edit" data-toggle="tab" href="#info-add-edit" role="tab" aria-controls="info-add" aria-selected="false">Descripcion Quirurgica</a>
                </li>

                <li class="nav-item" onclick="getFormhistroia()">
                    <a id="tab2" class="nav-link" id="init_history_edit" data-toggle="tab" href="#init_history_edit" role="tab" aria-controls="info-add" aria-selected="false">Historia Clinica Estética</a>
                </li>

                <li class="nav-item" onclick="getFormNotas()" >
                    <a id="tab2" class="nav-link" id="notas_enfermeria_edit" data-toggle="tab" href="#notas_enfermeria_edit" role="tab" aria-controls="info-add" aria-selected="false">Notas Enfermeria</a>
                </li>

                <li class="nav-item" onclick="getFormRegistros()">
                    <a id="tab3" class="nav-link"  data-toggle="tab" href="#info-credit-patient-edit" role="tab" aria-controls="info-add" aria-selected="false">Registros Anestésicos</a>
                </li>

                <li class="nav-item" onclick="getFormEnfermeria()">
                    <a id="tab3" class="nav-link"  data-toggle="tab" href="#info-enfermeria-edit" role="tab" aria-controls="info-add" aria-selected="false">Registro Enfermería</a>
                </li>
                
                <li class="nav-item" onclick="getFormSedacion()">
                    <a id="tab3" class="nav-link"  data-toggle="tab" href="#info-sedacion-edit" role="tab" aria-controls="info-add" aria-selected="false">Sedación</a>
                </li>
            
                <li class="nav-item" onclick="getFormPreoperatorio()">
                    <a id="tab3" class="nav-link"  data-toggle="tab" href="#info-preoperatorio-edit" role="tab" aria-controls="info-add" aria-selected="false">Registro Preoperatorio</a>
                </li>
            </ul>
            <br><br>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active tab_content0" id="init_edit" role="tabpanel" aria-labelledby="patient_record_edit">
                   @include('catalogos.history_clinic.preanestesia')
                </div>

                <div class="tab-pane fade tab_content1-0" id="info-add-edit" role="tabpanel" aria-labelledby="patient_record_edit">
                    @include('catalogos.history_clinic.quirurgica')
                </div>

                <div class="tab-pane fade tab_content1-0" id="init_history_edit" role="tabpanel" aria-labelledby="patient_record">
                  @include('catalogos.history_clinic.historia')

                </div>


            <div class="tab-pane fade tab_content1-0" id="notas_enfermeria_edit" role="tabpanel" aria-labelledby="patient_record">

                
                    <div class="col-md-12">
                    <br>
                        <h3>Notas Enfermerias</h3>
                        <hr>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group valid-required">
                                <input type="text" class="form-control form-control-user" name="descripcion_enfermeria" id="descripcion_enfermeria">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table intraoperatorio" id="tablenfemeria_edit" width="100%" cellspacing="0">

                                        <thead>
                                            <tr>
                                            <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                        <center>

                            <button type="button" class="btn btn-danger btn-user" onclick="prevClient('#cuadro4')">
                                Limpiar
                            </button>
                            <button type="button" class="btn btn-primary btn-user" id="btn-notas">
                                Guardar
                            </button>
                        </center>

                
            </div>

                <div class="tab-pane fade tab_content1-0" id="info-credit-patient-edit" role="tabpanel" aria-labelledby="patient_record">
                    @include('catalogos.history_clinic.registroanest')
                </div>

                <div class="tab-pane fade tab_content1-0" id="info-enfermeria-edit" role="tabpanel" aria-labelledby="patient_record">
                    @include('catalogos.history_clinic.registroenfermeria')
                </div>
                

                <div class="tab-pane fade tab_content1-0" id="info-sedacion-edit" role="tabpanel" aria-labelledby="patient_record">
                    @include('catalogos.history_clinic.sedacion')
                </div>
                
                <div class="tab-pane fade tab_content1-0" id="info-preoperatorio-edit" role="tabpanel" aria-labelledby="patient_record">
                    @include('catalogos.history_clinic.registropreoperatorio')
                </div>

            </div>


            <input type="hidden" name="id_user" class="id_user">
            <input type="hidden" name="token" class="token">
            <input type="hidden" id="id_cliente">
            <input type="hidden" name="id_user_edit" id="id_edit">
            <br>
        </form>
    </div>
</div>
