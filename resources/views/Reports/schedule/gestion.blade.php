@extends('layouts.app')
	

	@section('CustomCss')

		<style>
			.kv-avatar .krajee-default.file-preview-frame,.kv-avatar .krajee-default.file-preview-frame:hover {
			    margin: 0;
			    padding: 0;
			    border: none;
			    box-shadow: none;
			    text-align: center;
			}
			.kv-avatar {
			    display: inline-block;
			}
			.kv-avatar .file-input {
			    display: table-cell;
			    width: 213px;
			}
			.kv-reqd {
			    color: red;
			    font-family: monospace;
			    font-weight: normal;
			}
		</style>


	<link href="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.css" rel="stylesheet">
    <script src="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.js"></script>



	@endsection


	@section('content')
	     <!-- Page Wrapper -->
		  <div id="wrapper">

		    @include('layouts.sidebar')

		    <!-- Content Wrapper -->
		    <div id="content-wrapper" class="d-flex flex-column">

		      <!-- Main Content -->
		      <div id="content">

				@include('layouts.topBar') 
		       

		        <!-- Begin Page Content -->
			        <div class="container-fluid">

			          <!-- Page Heading -->
			          <h1 class="h3 mb-2 text-gray-800">Agenda del dia</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Agenda del dia</h6>
			            </div>
			            <div class="card-body">

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for=""><b>Clinica</b></label>
										<select name="clinic" id="clinic" class="form-control select2 disabled" required>
											<option value="">Seleccione</option>
										</select>
									</div>
								</div>
							</div>


			              <div class="table-responsive">
			                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
								  <th>Paciente</th>
								  <th>Clinica</th>
								  <th>Asunto</th>
								  <th>Fecha y Hora</th>
			                    </tr>
			                  </thead>
			                  <tbody></tbody>
			                </table>
			              </div>
			            </div>
			          </div>
			        </div>
			        <!-- /.container-fluid -->

		      </div>
		      <!-- End of Main Content -->

		      <!-- Footer -->
		      <footer class="sticky-footer bg-white">
		        <div class="container my-auto">
		          <div class="copyright text-center my-auto">
		            <span>Copyright &copy; Your Website 2019</span>
		          </div>
		        </div>
		      </footer>
		      <!-- End of Footer -->

		    </div>
		    <!-- End of Content Wrapper -->

		  </div>
		  <input type="hidden" id="ruta" value="<?= url('/') ?>">
	@endsection





	@section('CustomJs')

		<script>
			$(document).ready(function(){
				list();

				$("#collapse_Reportes").addClass("show");
				$("#nav_ReportEventAsesora, #modulo_Reportes").addClass("active");

				verifyPersmisos(id_user, tokens, "citys");

				GetClinic("#clinic")


			});


			$("#clinic").change(function (e) { 
				list("")
			});


			function list(cuadro) {
				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};


				var clinic = 0
				if($("#clinic").val() != ""){
					clinic = $("#clinic").val()
				}




				$("#div-input-edit").css("display", "none")
				$('#table tbody').off('click');
				var url=document.getElementById('ruta').value; 
				cuadros(cuadro, "#cuadro1");

				var table=$("#table").DataTable({
					"destroy":true,
					
					"stateSave": true,
					"serverSide":false,
					"ajax":{
						"method":"GET",
						 "url":''+url+'/api/schedule',
						 "data": {
							"clinic" : clinic
						},

						"dataSrc":""
					},
					"columns":[
						{"data": "name_client",
							render : function(data, type, row){

								if(data == null){
									return row.name_paciente
								}else{
									return data
								}
								
							}
						},
						{"data": "name_clinic"},
						{"data": "type",
							render : function(data, type, row){
								return "<b>"+data+"</b>"
							}
						},
						{"data": "fecha",
							render : function(data, type, row){
								return `${data} ${row.time}`
							}
						},


					],
					"language": idioma_espanol,
					"dom": 'Bfrtip',
					"bSort" : false,
					"responsive": true,
					"buttons":[
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				});


			}

		</script>
		



	@endsection


