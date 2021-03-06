<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>App</title>

  <!-- Custom fonts for this template-->
  <link href="<?= url('/') ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= url('/') ?>/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?= url('/') ?>/css/custom.css" rel="stylesheet">



  <link href="<?= url('/') ?>/vendor/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
  <link href="<?= url('/') ?>/vendor/bootstrap-fileinput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>

  <script src="<?= url('/') ?>/vendor/jquery/jquery.min.js"></script>
    <!-- Custom styles for this page -->
  <link href="<?= url('/') ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?= url('/') ?>/vendor/sweetalert/sweetalert.css" rel="stylesheet">


  <link href="<?= url('/') ?>/vendor/select2-4.0.11/dist/css/select2.min.css" rel="stylesheet" />
    
  <style>
    .alert {
      position: relative;
      padding: .75rem 1.25rem;
      margin-bottom: 1rem;
      border: 1px solid transparent;
      border-radius: .25rem;
    } 
  </style>
</head>




  <body class="{{ Request::path() != '/' ? 'dasboard-body' : ''}} bg-gradient-primary" style="{{ $id_line != 17 ? 'overflow: hidden;' : ''}} background: #fff !important;
  ">

<div class="card shadow mb-4 " id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"> CUESTIONARIO CUMPLIMIENTO DE PROTOCOLO DE BIOSEGURIDAD PARA LOS TRABAJADORES DE LA {{$name_line}}. </h6>
  </div>


  <div class="alert alert-primary" role="alert">
      El presente cuestionario se realiza con el objetivo de dar cumplimiento a los lineamientos establecidos en el Protocolo de Bioseguridad, establecido por la instituci??n a fin de salvaguardar la vida, integridad y salud de los pacientes y trabajadores de la IPS. Por lo cual, es su obligaci??n diligenciar el presente cuestionario de manera diaria.
  </div>

  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="_method" value="post">

        <div class="row">
			
			
           <div class="col-md-12">
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Nombre del Trabajador:*</b></label>
                        <input type="text" name="nombres" id="nombres" class="form-control" required >
                    </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>N??mero del Documento de Identidad:*</b></label>
                        <input type="text" name="identificacion" id="identificacion" class="form-control" required >
                    </div>
                </div>


              </div>

			        <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>??Ha presentado alguno de los siguientes s??ntomas?</b></label>

                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Fiebre" id="fiebre">
                          <label class="form-check-label" for="fiebre">
                           Fiebre
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="temperatura" id="temperatura">
                          <label class="form-check-label" for="temperatura">
                            ??Temperatura >37.5 ??C por m??s de 3 d??as? 
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Tos" id="Tos">
                          <label class="form-check-label" for="Tos">
                            Tos
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Dificultad para respirar" id="Dificultad para respirar">
                          <label class="form-check-label" for="Dificultad para respirar">
                            Dificultad para respirar
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Secreci??n nasal" id="Secreci??n nasal">
                          <label class="form-check-label" for="Secreci??n nasal">
                           Secreci??n nasal
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Malestar general" id="Malestar general">
                          <label class="form-check-label" for="Malestar general">
                           Malestar general
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value=" ??N??useas, v??mito, diarrea, dolor abdominal, p??rdida del olfato o gusto?" id=" ??N??useas, v??mito, diarrea, dolor abdominal, p??rdida del olfato o gusto?">
                          <label class="form-check-label" for=" ??N??useas, v??mito, diarrea, dolor abdominal, p??rdida del olfato o gusto?">
                            ??N??useas, v??mito, diarrea, dolor abdominal, p??rdida del olfato o gusto?
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value=" Dolor de garganta" id=" Dolor de garganta">
                          <label class="form-check-label" for=" Dolor de garganta">
                            Dolor de garganta
                          </label>
                        </div>


                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sintomas[]" value="Ninguna de las Anteriores" id="Ninguna de las Anteriores">
                          <label class="form-check-label" for="Ninguna de las Anteriores">
                            Ninguna de las Anteriores
                          </label>
                        </div>



                    </div>
                </div>


                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>??Ha tenido alg??n viaje al extranjero en los ??ltimos 30 d??as?</b></label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="travel" id="si1_travel" value="Si" required>
                          <label class="form-check-label" for="si1_travel">
                            Si
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="travel" id="no_travel" value="No" required>
                          <label class="form-check-label" for="no_travel">
                            No
                          </label>
                        </div>
                    </div>
                </div>

              </div>



              <div class="row">
                


                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>??Ha tenido alg??n contacto con alguna persona procedente de los sitios decirculaci??n de COVID-19 en los ??ltimos 30 d??as?</b></label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="contacto_sospechoso" id="contacto_sospechoso" value="Si" required>
                          <label class="form-check-label" for="contacto_sospechoso">
                            Si
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="contacto_sospechoso" id="contacto_sospechoso_no" value="No" required>
                          <label class="form-check-label" for="contacto_sospechoso_no">
                            No
                          </label>
                        </div>
                    </div>
                </div>



                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Ha estado en contacto estrecho con alg??n paciente sospechoso o confirmado de coronavirus?</b></label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="contacto_paciente_dias" id="contacto_paciente_dias_si1" value="Si" required>
                          <label class="form-check-label" for="contacto_paciente_dias_si1">
                            Si
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="contacto_paciente_dias" id="contacto_paciente_dias_no2" value="No" required>
                          <label class="form-check-label" for="contacto_paciente_dias_no2">
                            No
                          </label>
                        </div>


                    </div>
                </div>

              </div>




              <div class="row">

              <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Se compromete a informar a su empleador en caso de que despu??s de la pr??ctica del presente cuestionario, usted llegar?? a presentar alguno de los sintomas expresados en el numeral 1 o llegue a tener contacto con alguien que presente sintomas de COVID 19 o sea caso confirmado de dicha enfermedad?</b></label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="compromiso" id="compromiso_si1" value="Si" required>
                          <label class="form-check-label" for="compromiso_si1">
                            Si
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="compromiso" id="compromiso_no2" value="No" required>
                          <label class="form-check-label" for="compromiso_no2">
                            No
                          </label>
                        </div>


                    </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Ha cumplido con las recomendaciones de cuidado entregado por su empleador ?</b></label>
                        
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="declara" id="declara_si1" value="Si" required>
                          <label class="form-check-label" for="declara_si1">
                            Si
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="declara" id="declara_no2" value="No" required>
                          <label class="form-check-label" for="declara_no2">
                            No
                          </label>
                        </div>


                    </div>
                </div>


                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Observaciones</b></label>
                        
                        <textarea name="observaciones" class="form-control" id="observaciones" cols="30" rows="10"></textarea>


                    </div>
                </div>
              </div>


              <div class="alert alert-warning" role="alert">
              ELTRABAJADOR DECLARA QUE LA INFORMACI??N CONTENIDA EN EL PRESENTE CUESTIONARIO ES AUTENTICA Y CORRESPONDE A LA VERDAD,  POR LO CUAL REALIZA EL ENVIO DEL PRESENTE  FORMULARIO-CUESTIONARIO VIA WEB CON SU NOMBRE COMPLETO Y NUMERO DE DOCUMENTO. 

              </div>



              
           </div>

        </div>

       		<input type="hidden" name="state" value="No Contactada">
			   <input type="hidden" name="origen" value="Formulario Web">
			<input type="hidden" name="id_line" id="id_line" value="{{$id_line}}">
			<input type="hidden" name="id_line" id="id_line" value="{{$id_line}}">
			<input type="hidden" name="id_line_asesora" value="{{$id_line}}">
          <br>
          <br>
        </div>
          <center>
            <button id="btn-submit" class="btn btn-primary btn-user">
                Enviar
            </button>

          </center>
          <br>
          <br>
      </form>
      
    </div>

	
		  
	<input type="hidden" id="ruta" value="<?= url('/') ?>">


  <!-- Bootstrap core JavaScript-->

  <script src="<?= url('/') ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= url('/') ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= url('/') ?>/js/sb-admin-2.min.js"></script>
  


   <!-- Page level plugins -->
  <script src="<?= url('/') ?>/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?= url('/') ?>/js/demo/chart-area-demo.js"></script>
  <script src="<?= url('/') ?>/js/demo/chart-pie-demo.js"></script>

  


 <!-- Page level plugins -->
    <script src="<?= url('/') ?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= url('/') ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= url('/') ?>/js/demo/datatables-demo.js"></script>

  


    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/js/plugins/piexif.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/js/locales/fr.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/themes/fas/theme.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/bootstrap-fileinput/themes/explorer-fas/theme.js" type="text/javascript"></script>





    <script src="<?= url('/') ?>/vendor/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="<?= url('/') ?>/vendor/sweetalert/sweetalert-dev.js" type="text/javascript"></script>

    <script src="<?= url('/') ?>/vendor/select2-4.0.11/dist/js/select2.min.js"></script>


  <script src="<?= url('/') ?>/js/funciones.js"></script>
  

  <script>
    //var user_id = localStorage.getItem('user_id');
	

	$(document).ready(function(){
		store();
	});


	function store(){
		id_line = 69;
		enviarFormularioForm("#store", 'api/forms/bioseguridad', '#cuadro2');
	}



	function enviarFormularioForm(form, controlador, cuadro, auth = false){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:''+url+'/'+controlador+'',
                type:method,
                dataType:'JSON',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');

                    $('#btn-submit').attr("disabled", "disabled")
                    $('#btn-submit').text("Espere...")


                },
                error: function (repuesta) {
                    $('#btn-submit').removeAttr('disabled'); //activa el input submit
                    $('#btn-submit').text("Enviar")
                    var errores=repuesta.responseText;
                    if(errores!=""){
                    mensajes('danger', errores);
                    warning(respuesta.errores)
                            }else{
                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");  
                    warning("Ha ocurrido un error, por favor intentelo de nuevo.")
                  }
					   
                },
                 success: function(respuesta){
                   $('#btn-submit').removeAttr('disabled'); //activa el input submit
                    $('#btn-submit').text("Enviar")
                  warning(respuesta.mensagge)
                  $("#store")[0].reset();
                 // enviarEmail()
                }	

            });
        });
    }


  </script>

  

  

</body>

</html>



