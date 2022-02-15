@extends('layouts.app')


	@section('CustomCss')




	 <!-- include summernote css/js -->
	 <link href="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.css" rel="stylesheet">
     <script src="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.js"></script>



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
			          <h1 class="h3 mb-2 text-gray-800">Pacientes</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de pacientes</h6>

			            </div>
			            <div class="card-body">

			              <div class="table-responsive dataTables_wrapper dt-bootstrap4 no-footer">

			                <table class="table table-bordered " id="table" width="100%" cellspacing="0">

			                  <thead>
			                    <tr>
								  <th>Acciones</th>
								  <th>Foto</th>
								  <th>Nombres</th>
								  <th>Telefono</th>
								  <th>Correo</th>
								  <th>Servicios Completados</th>
								  <th>Estatus</th>
								  <th>Estado</th>
			                      <th style="width: 180px;">Fecha de registro</th>
			                    </tr>
			                  </thead>
			                  <tbody>

			                  </tbody>
			                </table>

							<div class="dataTables_info" id="table_info" role="status" aria-live="polite"></div>


							<div class="dataTables_paginate paging_simple_numbers">
								<ul class="pagination"></ul>
							</div>

			              </div>
			            </div>
			          </div>


			          @include('catalogos.clientes.store')
					  @include('catalogos.clientes.view')
					  @include('catalogos.clientes.edit')


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
				store();
	 		//	list();
				update();

				list("",1)
				$("#collapse_Catálogos").addClass("show");
				$("#nav_clients, #modulo_Catálogos").addClass("active");
				verifyPersmisos(id_user, tokens, "clients");


			});



			function getProcedures(select, select_default){

				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/procedures/all',
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
						},
					dataType:'JSON',
					async: false,
					beforeSend: function(){
						$('#page-loader').css("display", "block");
					},
					error: function (data) {
						$('#page-loader').css("display", "none");
					},
					success: function(data){
						$('#page-loader').css("display", "none");
						$(select+" option").remove();
						$(select).append($('<option>',
						{
						value: "",
						text : "Seleccione"
						}));
						$.each(data, function(i, item){

							$(select).append($('<option>',
							{
							value: item.id,
							text : item.name,
							selected : select_default == item.id ? true : false
							}));

						});



						$(select).select2({
							width: '100%'
						});



					}
				});
			}




			function GetUsersTasksclient(select, select_default){

				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/user',
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
						},
					dataType:'JSON',
					async: false,
					beforeSend: function(){
						$('#page-loader').css("display", "block");
					},
					error: function (data) {
						$('#page-loader').css("display", "none");
					},
					success: function(data){
						$('#page-loader').css("display", "none");
						$(select+" option").remove();
						$(select).append($('<option>',
						{
						value: "",
						text : "Seleccione"
						}));
						$.each(data, function(i, item){
							if (item.status == 1) {
								$(select).append($('<option>',
								{
								value: item.id,
								text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
								selected : select_default == item.id ? true : false
								}));
							}
						});



						$(select).select2({
							width: '100%'
						});



					}
				});
			}



			function GetAsesorasbyBusisnessLine2(line_business, asesoras){

				$(line_business).change(function (e) {

					var id_line_business = $(this).val()
					var url=document.getElementById('ruta').value;
					$.ajax({
						url:''+url+'/api/get-asesoras-business-line',
						type:'POST',
						data: {
							"id_user": id_user,
							"token"  : tokens,
							"array_line" : id_line_business
						},
						dataType:'JSON',
						async: false,
						beforeSend: function(){
						// mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
						},
						error: function (data) {
						//mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');
						},
						success: function(data){
							$(asesoras+" option").remove();
							$(asesoras).append($('<option>',
							{
								value: "",
								text : "Seleccione"
							}));

							$.each(data, function(i, item){
								if (item.status == 1) {

								$(asesoras).append($('<option>',
								{
									value: item.id,
									text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,

								}));

								if(item.id == id_user){
									//$(asesoras+" option").not(':selected').remove();
									//return false;
								}

								}
							});

						}
					});
				});

			}



			function update(){
				enviarFormularioPutClient("#form-update", 'api/clients', '#cuadro4');
			}


			function store(){
				enviarFormulario("#store", 'api/clients', '#cuadro2');
            }



            function enviarFormularioPutClient(form, controlador, cuadro, auth = false, inputFile){
                $(form).submit(function(e){
                    e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
                    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable
                    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros

                    var method = $(this).attr('method'); //obtiene el method del formulario
                    console.log(method)


                    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
                    $.ajax({
                        url:''+url+'/'+controlador+'/'+$("#id_edit").val(),
                        type:method,
                        dataType:'JSON',
                        data:formData,
                        cache:false,
                            contentType:false,
                            processData:false,
                        beforeSend: function(){
                            mensajes('info', '<span>Espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                        },
                        error: function (repuesta) {
                            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                            var errores=repuesta.responseText;
                            if(errores!="")
                                mensajes('danger', errores);
                            else
                                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");
                        },
                        success: function(respuesta){
                            if (respuesta.success == false) {
                                mensajes('danger', respuesta.message);
                                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                            }else{
                                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                                mensajes('success', respuesta.mensagge);

                                if (auth) {
                                localStorage.setItem('token', respuesta.token);
                                localStorage.setItem('email', respuesta.email);
                                localStorage.setItem('user_id', respuesta.user_id);
                                window.location.href = url+"/dashboard";
                                }else{

                                    list(cuadro, number_page);
                                }

                            }

                        }

                    });
                });
            }



            function list(cuadro) {
				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};

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
						 "url":''+url+'/api/clients',
						 "data": {
							"rol"    : name_rol,
							"id_user": id_user,
							"token"  : tokens,
						},
						"dataSrc":""
					},
					"columns":[
						{"data": null,
							render : function(data, type, row) {
								var botones = "";
								if(consultar == 1)
									//botones += "<span class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
								if(actualizar == 1)
									botones += "<span class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
									
								if(data.status == 1 && actualizar == 1)
									botones += "<span class='desactivar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
								else if(data.status == 2 && actualizar == 1)
									botones += "<span class='activar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
								if(borrar == 1)
									//botones += "<span class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span>";
								
								if(row.service_provider == "Reviewing"){
									botones += "<span class='aprobar btn btn-sm btn-success waves-effect' data-toggle='tooltip' title='Aprobar'><i class='fas fa-check' style='margin-bottom:5px'></i></span> ";
								}
								return botones;
							}
						},
						{"data": "photo_profile",
							render : function(data, type, row) {
								return "<img width='40' height='40' style='border-radius: 100%' src='https://serviceuf.pdtcomunicaciones.com/img/usuarios/profile/"+data+"'>"
							}
						},
						{"data":"names",
							render : function(data, type, row) {
								return data+" "+row.last_names;
							}
						},
						{"data": "phone"},
						{"data": "email"},
						{"data": "request_offerts.count_services"},
						{"data": "service_provider"},
						{"data": "service_provider_status"},
						{"data": "created_at"},
					],
					"language": idioma_espanol,
					"dom": 'Bfrtip',
					"responsive": true,
					"buttons":[
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				});

				table
				.search("").draw()


				ver("#table tbody", table)
				edit("#table tbody", table)
				activar("#table tbody", table)
				desactivar("#table tbody", table)
				eliminar("#table tbody", table)
				aprobar("#table tbody", table)


			}






			$("#create_task_client").change(function (e) {

				if($('#create_task_client').is(':checked') ) {
					$("#content_create_task").css("display", "block")
					$("#responsable, #issue-store, #fecha-store, #time-store, #followers-store").attr("required", "required")
				}else{
					$("#content_create_task").css("display", "none")
					$("#responsable, #issue-store, #fecha-store, #time-store, #followers-store").removeAttr("required")
				}
			});



			$("#create_valorations_client").change(function (e) {

				if($('#create_valorations_client').is(':checked') ) {
					$("#content_create_valoration").css("display", "block")
					GetClinic2("#clinic_valoration")

					$("#fecha-store-valoration, #time-store-valoration, #time-end-store, #surgeon-store, #type-store, #clinic_valoration, #way_to_pay-store").attr("required", "required")

				}else{
					$("#content_create_valoration").css("display", "none")

					$("#fecha-store-valoration, #time-store-valoration, #time-end-store, #surgeon-store, #type-store, #clinic_valoration, #way_to_pay-store").removeAttr("required")

				}
            });




            function prevClient(cuadroOcultar){
                console.log(number_page)
                $(cuadroOcultar).slideUp("slow"); //oculta el cuadro.
                $("#cuadro1").slideDown("slow"); //muestra el cuadro.
                list(cuadroOcultar, number_page);
            }






			function listRefferers(cuadro = "", page = 1){


				var url=document.getElementById('ruta').value;

				cuadros(cuadro, "#cuadro1");



				if($("#search").val() != ""){

					var search = $("#search").val()

				}else{
					var search = null
				}


				$.ajax({
					url:''+url+'/api/clients/refferes/code/'+code_client,
					type:'GET',
					dataType:'JSON',

					beforeSend: function(){

						var html = ""
						 html += "<tr>"
								html += "<td colspan='8'> Cargando...</td>"
							html += "</tr>"

						$("#table tbody").html(html)
					},
					error: function (data) {
					},
					success: function(result){

						var html = ""

						if(result.data.length == 0){

							html += "<tr>"
								html += "<td colspan='8'> No se encontraron Resultados...</td>"
							html += "</tr>"

						$("#table tbody").html(html)


							return false;
						}
						$.map(result.data, function (item, key) {

							var botones = "";
							if(consultar == 1)
								//botones += "<span data='"+JSON.stringify(item)+"' class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
							if(actualizar == 1)
								botones += "<span data='"+JSON.stringify(item)+"' class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
							if(item.status == 1 && actualizar == 1)
								botones += "<span data='"+JSON.stringify(item)+"' class='desactivar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
							else if(item.status == 2 && actualizar == 1)
								botones += "<span data='"+JSON.stringify(item)+"' class='activar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";

							if((item.id_user_asesora == id_user) || borrar == 1)
								botones += "<span data='"+JSON.stringify(item)+"' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span>";
						//	return botones;




							if(item.prp == "Si"){
								var code = "<i class='fa fa-barcode'></i> "+item.code_client
							}else{
								var code = ""
							}

							if(item.name_update != null){
								var name_update = "Por: "+item.name_update+" "+item.apellido_update
							}else{
								var name_update = ""
							}


							html += "<tr>"
								html += "<td>"+botones+"</td>"
								html += "<td><b>"+item.nombres+"</b><br><i class='fa fa-phone'></i> <a href='#'>"+item.telefono+"</a><br><i class='fa fa-envelope'></i> <a href='#'>"+item.email+"</a><br>"+code+" </td>"
								html += "<td>"+item.identificacion+"</td>"
								html += "<td>"+item.origen+"</td>"
								html += "<td>"+item.nombre_line+"</td>"
								html += "<td>"+item.name_city+"</td>"
								html += "<td>"+item.state+"</td>"
								html += "<td>"+item.fec_regins+"<br><b>Ultima modificacion</b><br>"+item.fec_update+" <b>"+name_update+"</b></td>"
								html += "<td><b>"+item.name_register+" "+item.apellido_register+"</b></td>"
							html += "</tr>"

						});

						var table = $("#table tbody").html(html)

						if(result.next_page_url != null){
							var next = result.next_page_url.split("page=")[1]
							var className = ''
						}else{
							var next = result.last_page
							var className = 'disabled'
						}


						if(result.prev_page_url != null){
							var prev = result.prev_page_url.split("page=")[1]
							var className = ''
						}else{
							var prev = 1;
							var className = 'disabled'
						}


						var li = ""
						li  += '<li class="paginate_button page-item previous '+className+'" onclick="list(\'\', '+prev+')" id="table_previous"><a href="javascript:void(0)" aria-controls="table" data-dt-idx="0" tabindex="0" class="page-link">Anterior</a></li>'

						li += '<li class="paginate_button page-item next" onclick="list(\'\', '+next+')" id="table_next"><a href="javascript:void(0)" aria-controls="table" data-dt-idx="8" tabindex="0" class="page-link">Siguiente</a></li>'

						$(".pagination").html(li)


						$("#table_info").text("Mostrando registros del "+result.from+" al  "+result.to+" de un total de "+result.total+" registros")

					}
				});


				ver("#table tbody")
				edit("#table tbody")
				activar("#table tbody")
				desactivar("#table tbody")
				eliminar("#table tbody")





				var business_line = $("#linea-negocio-filter").val()
				var adviser       = $("#id_asesora_valoracion-filter").val()
				var origen        = $("#origen-filter").val()

				var date_init     = $("#date_init").val()
				var date_finish   = $("#date_finish").val()
				var search        = $("#search").val()
				var city          = $("#city-filter").val()


				if(business_line.length == 0){
					business_line = 0
				}else{
					business_line  = business_line.join()
				}


				if(adviser.length == 0){
					adviser = 0
				}else{
					adviser  = adviser.join()
				}



				if(date_init.length == 0){
					date_init = 0
				}

				if(date_finish.length == 0){
					date_finish = 0
				}


				if(search.length === 0){
					search = 5
				}

				$("#xls").remove();
				$("#view_xls").remove();

				var a = '<button id="xls" class="dt-button buttons-excel buttons-html5">Excel</button>';
				$('.dt-buttons').append(a);

				var b = '<button id="view_xls" target="_blank" style="opacity: 0" href="api/clients/export/excel/'+business_line+'/'+adviser+'/'+origen+'/'+date_init+'/'+date_finish+'/'+$("#state-filter").val()+'/'+search+'/'+city+'" class="dt-button buttons-excel buttons-html5">xls</button>';
				$('.dt-buttons').append(b);

				$("#xls").click(function (e) {
					url = $("#view_xls").attr("href");

					console.log(url)
					window.open(url, '_blank');
				});

			}


			function GetComments(comment_content, id_client){
				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/clients/comments/'+id_client,
					type:'GET',
					dataType:'JSON',

					beforeSend: function(){

					},
					error: function (data) {
					},
					success: function(result){

						var url=document.getElementById('ruta').value;
						var html = "";

						$.map(result, function (item, key) {
							html += '<div class="col-md-12" style="margin-bottom: 15px">'
								html += '<div class="row">'
									html += '<div class="col-md-2">'
										html += "<img class='rounded' src='"+url+"/img/usuarios/profile/"+item.img_profile+"' style='height: 4rem;width: 4rem; margin: 1%; border-radius: 50%!important;' title='"+item.name_follower+" "+item.last_name_follower+"'>"

									html += '</div>'
									html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;overflow: scroll">'
										html += '<div>'+item.comment+'</div>'

										html += '<div><b>'+item.name_user+" "+item.last_name_user+'</b> <span style="float: right;overflow: scroll">'+item.create_at+'</span></div>'


									html += '</div>'
								html += '</div>'
							html += '</div>'

						});


						$(comment_content).html(html)
					}
				});
			}





			function SubmitComment(id, api, table, btn, summer){

				$(btn).unbind().click(function (e) {

					var html = ""

					html += '<div class="col-md-12" style="margin-bottom: 15px">'
						html += '<div class="row">'
							html += '<div class="col-md-2">'
							html += '</div>'
							html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;overflow: scroll">'
								html += '<div>'+$(summer).val()+'</div>'

								html += '<div><b></b> <span style="float: right">Ahora Mismo</span></div>'

							html += '</div>'
						html += '</div>'
					html += '</div>'

					$(table).append(html)


					var url=document.getElementById('ruta').value;

					$.ajax({
						url:''+url+"/"+api,
						type:'POST',
						data: {
							"id_user" : id_user,
							"token"   : tokens,
							"id"      : id,
							"comment" : $(summer).val(),

						},
						dataType:'JSON',
						beforeSend: function(){
							$(btn).text("espere...").attr("disabled", "disabled")
						},
						error: function (data) {
							$(btn).text("Comentar").removeAttr("disabled")
						},
						success: function(data){
							$(btn).text("Comentar").removeAttr("disabled")
							$(summer).summernote("reset");
						}
					});

				});

			}

			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();
                $(".nav-link").removeClass("active")
                $("#tab0-0").addClass("active")
                $(".tab-pane").removeClass("active")
                $(".tab-pane").removeClass("show")

                $(".tab_content0-0").addClass("active")
                $(".tab_content0-0").addClass("show")

				GetCity("#city");
				GetClinic("#city", "#clinic")
			//	GetAsesorasbyBusisnessLine("#linea-negocio", "#asesora");
			//	GetAsesorasValoracion("#id_asesora_valoracion")
				GetBusinessLine("#linea-negocio");
				Children("#children", "#number_children")
				Surgery("#surgery", "#previous_surgery")
				Disease("#disease", "#major_disease")
				Drugs("#drugs_check", "#drugs")
				Medication("#medication", "#drink_medication")
				Allergic("#allergic ", "#allergic_medication")
				$("#clinic").attr("disabled", "disabled")


				$("#tablecx tbody").html("");
				getCategory("#category", 124124124)
				ChangeCategory("#category", "#sub_category")
				$("#tablecx tbody").html("");


				$('#summernote, #summernote_valorations').summernote('reset');
				$('#summernote, #summernote_valorations').summernote({
					'height' : 200
				});



				$("#acquittance").fileinput('destroy');
				$("#acquittance").fileinput({
					theme: "fas",
					overwriteInitial: true,
					maxFileSize: 21500,
					showClose: false,
					showCaption: false,
					browseLabel: '',
					removeLabel: '',
					browseIcon: '<i class="fa fa-folder-open"></i>',
					removeIcon: '<i class="fas fa-trash-alt"></i>',
					previewFileIcon: '<i class="fas fa-file"></i>',
					removeTitle: 'Cancel or reset changes',
					elErrorContainer: '#kv-avatar-errors-1',
					msgErrorClass: 'alert alert-block alert-danger',

					layoutTemplates: {main2: '{preview}  {remove} {browse}'},
					allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "docs"],
				});



///

				$("#nutricional").fileinput('destroy');
				$("#nutricional").fileinput({
					theme: "fas",
					overwriteInitial: true,
					maxFileSize: 21500,
					showClose: false,
					showCaption: false,
					browseLabel: '',
					removeLabel: '',
					browseIcon: '<i class="fa fa-folder-open"></i>',
					removeIcon: '<i class="fas fa-trash-alt"></i>',
					previewFileIcon: '<i class="fas fa-file"></i>',
					removeTitle: 'Cancel or reset changes',
					elErrorContainer: '#kv-avatar-errors-1',
					msgErrorClass: 'alert alert-block alert-danger',

					layoutTemplates: {main2: '{preview}  {remove} {browse}'},
					allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "docx"],
				});




				GetAsesorasValoracion2("#asesora")



				cuadros("#cuadro1", "#cuadro2");
			}




			function GetClinic2(select){

				console.log("adasd")
				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/clinic',
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
						},
					dataType:'JSON',
					async: false,
					beforeSend: function(){
					// mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
					},
					error: function (data) {
						//mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');
					},
					success: function(data){
						$(select+" option").remove();
						$(select).append($('<option>',
						{
						value: "",
						text : "Seleccione"
						}));
						$.each(data, function(i, item){
						if (item.status == 1) {
							$(select).append($('<option>',
							{
							value: item.id_clinic,
							text : item.nombre
							}));
						}
					});

					}
				});
			}





			var count_phone = 0
			$("#add_phone").click(function (e) {
				e.preventDefault();

				count_phone++

				var html = ""


				html += '<div class="col-md-10 phone_add_'+count_phone+'">'
					html += '<div class="form-group">'
						html += '<label for=""><b>Telefono</b></label>'
						html += '<input type="number" name="telefono2[]" class="form-control form-control-user"  placeholder="PJ. 315 2077862">'
					html += '</div>'
				html += '</div>'


				html += '<div class="col-md-2 phone_add_'+count_phone+'"">'
				html += '<br>'
					html += '<button type="button" id="add_phone" onclick="deletePhone('+count_phone+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
				html += '</div>'



				$("#add_phone_content").append(html)
			});





			var count_phone_edit = 0
			$("#add_phone_edit").click(function (e) {
				e.preventDefault();

				count_phone_edit++

				var html = ""


				html += '<div class="col-md-10 phone_add_'+count_phone_edit+'">'
					html += '<div class="form-group">'
						html += '<label for=""><b>Telefono</b></label>'
						html += '<input type="number" name="telefono2[]" class="form-control form-control-user"  placeholder="PJ. 315 2077862">'
					html += '</div>'
				html += '</div>'


				html += '<div class="col-md-2 phone_add_'+count_phone_edit+'"">'
				html += '<br>'
					html += '<button type="button" id="add_phone" onclick="deletePhone('+count_phone_edit+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
				html += '</div>'



				$("#phone_add_content_edit").append(html)
			});


			function deletePhone(id){
				$(".phone_add_"+id).remove()
			}


			function deletePhoneEdit(id){
				$(".phone_add_edit_"+id).remove()
			}














			var count_emal = 0
			$("#add_email").click(function (e) {
				e.preventDefault();

				count_emal++

				var html = ""


				html += '<div class="col-md-10 email_add_'+count_emal+'">'
					html += '<div class="form-group">'
						html += '<label for=""><b>E-mail</b></label>'
						html += '<input type="email" name="email2[]" class="form-control form-control-user disabled"  placeholder="PJ. correo@dominio.com" required>'
					html += '</div>'
				html += '</div>'


				html += '<div class="col-md-2 email_add_'+count_emal+'"">'
				html += '<br>'
					html += '<button type="button" id="add_email" onclick="deleteemail('+count_emal+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
				html += '</div>'



				$("#add_email_content").append(html)
			});





			var count_email_edit = 0
			$("#add_email_edit").click(function (e) {
				e.preventDefault();

				count_email_edit++

				var html = ""


				html += '<div class="col-md-10 email_add_'+count_email_edit+'">'
					html += '<div class="form-group">'
						html += '<label for=""><b>E-mail</b></label>'
						html += '<input type="email" name="email2[]" class="form-control form-control-user"  placeholder="PJ. correo@dominio.com">'
					html += '</div>'
				html += '</div>'


				html += '<div class="col-md-2 email_add_'+count_email_edit+'"">'
				html += '<br>'
					html += '<button type="button" id="add_email" onclick="deleteemail('+count_email_edit+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
				html += '</div>'



				$("#email_add_content_edit").append(html)
			});


			function deleteemail(id){
				$(".email_add_"+id).remove()
			}


			function deleteemailEdit(id){
				$(".email_add_edit_"+id).remove()
			}






			/* ------------------------------------------------------------------------------- */
			/*
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			let iden = null;
			function ver(tbody, table){
				$(tbody).unbind().on("click", "span.consultar", function(){
					$("#alertas").css("display", "none");

					var data = JSON.parse($(this).attr("data"))

					GetCity("#city_view");
					GetClinic("#city_view", "#clinic_view")
					GetBusinessLine("#linea-negocio-view");
					GetAsesorasbyBusisnessLine("#linea-negocio-view", "#asesora-view");

					GetAsesorasValoracion("#id_asesora_valoracion-view")
					GetComments("#comments", data.id_cliente)




					$("#id_asesora_valoracion-view").val(data.id_asesora_valoracion).attr("disabled", "disabled")

					$("#code-view").text(data.code_client)
					$("#state_view").val(data.state)
					$("#state_view").trigger("change");
					$("#state_view").attr("disabled", "disabled")
					$("#nombre_view").val(data.nombres).attr("disabled", "disabled")
					$("#apellido_view").val("").attr("disabled", "disabled")
					$("#identificacion_view").val(data.identificacion).attr("disabled", "disabled")
					$("#telefono_view").val(data.telefono).attr("disabled", "disabled")
					$("#email_view").val(data.email).attr("disabled", "disabled")
					$("#direccion_view").val(data.direccion).attr("disabled", "disabled")
					$("#fecha_nacimiento_view").val(data.fecha_nacimiento).attr("disabled", "disabled")
					$("#origen_view").val(data.origen).attr("disabled", "disabled")
					$("#forma_pago_view").val(data.forma_pago).attr("disabled", "disabled")


					$("#facebook_view").val(data.facebook).attr("disabled", "disabled")
					$("#instagram_view").val(data.instagram).attr("disabled", "disabled")
					$("#twitter_view").val(data.twitter).attr("disabled", "disabled")
					$("#youtube_view").val(data.youtube).attr("disabled", "disabled")
					$("#prp_view").val(data.prp).attr("disabled", "disabled")
					$("#prp_view").trigger("change");


					$("#city_view").val(data.city).attr("disabled", "disabled")
					$("#city_view").trigger("change")

					$("#clinic_view").val(data.clinic).attr("disabled", "disabled")

					$("#year_view").val(calcularEdad(data.fecha_nacimiento))

					$("#identificacion_verify_view").prop("checked", data.identificacion_verify ? true : false)

					$("#name_surgery_view").val(data.name_surgery).attr("disabled", "disabled")
					$("#current_size_view").val(data.current_size).attr("disabled", "disabled")
					$("#desired_size_view").val(data.desired_size).attr("disabled", "disabled")
					$("#implant_volumem_view").val(data.implant_volumem).attr("disabled", "disabled")

					$("#eps_view").val(data.eps).attr("disabled", "disabled")
					$("#height_view").val(data.height).attr("disabled", "disabled")
					$("#weight_view").val(data.weight).attr("disabled", "disabled")

					$("#children_view").prop("checked", data.children ? true : false)
					$("#smoke_view").prop("checked", data.smoke ? true : false)
					$("#alcohol_view").prop("checked", data.alcohol ? true : false)
					$("#surgery_view").prop("checked", data.surgery ? true : false)
					$("#disease_view").prop("checked", data.disease ? true : false)
					$("#medication_view").prop("checked", data.medication ? true : false)
					$("#allergic_view").prop("checked", data.allergic ? true : false)

					$("#number_children_view").val(data.number_children).attr("disabled", "disabled")
					$("#previous_surgery_view").val(data.previous_surgery).attr("disabled", "disabled")
					$("#major_disease_view").val(data.major_disease).attr("disabled", "disabled")
					$("#drink_medication_view").val(data.drink_medication).attr("disabled", "disabled")
					$("#allergic_medication_view").val(data.allergic_medication).attr("disabled", "disabled")


					$("#dependent_independent_view").val(data.dependent_independent).attr("disabled", "disabled")
					$("#type_contract_view").val(data.type_contract).attr("disabled", "disabled")
					$("#antiquity_view").val(data.antiquity).attr("disabled", "disabled")
					$("#average_monthly_income_view").val(data.average_monthly_income).attr("disabled", "disabled")
					$("#previous_credits_view").val(data.previous_credits).attr("disabled", "disabled")
					$("#reported_view").val(data.reported).attr("disabled", "disabled")
					$("#bank_account_view").val(data.bank_account).attr("disabled", "disabled")

					$("#properties_view").prop("checked", data.properties ? true : false)
					$("#vehicle_view").prop("checked", data.vehicle ? true : false)


					$("#linea-negocio-view").val(data.id_line).attr("disabled", "disabled")
					$("#linea-negocio-view").trigger("change");
					$("#asesora-view").val(data.id_user_asesora).attr("disabled", "disabled")


					var url=document.getElementById('ruta').value;
					var html = "";
					$.map(data.logs, function (item, key) {
						html += '<div class="col-md-12" style="margin-bottom: 15px">'
							html += '<div class="row">'
								html += '<div class="col-md-2">'
									html += "<img class='rounded' src='"+url+"/img/usuarios/profile/"+item.img_profile+"' style='height: 4rem;width: 4rem; margin: 1%; border-radius: 50%!important;' title='"+item.name_follower+" "+item.last_name_follower+"'>"

								html += '</div>'
								html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;overflow: scroll">'
									html += '<div>'+item.event+'</div>'

									html += '<div><b>'+item.name_user+" "+item.last_name_user+'</b> <span style="float: right">'+item.create_at+'</span></div>'


								html += '</div>'
							html += '</div>'
						html += '</div>'

					});

					$("#logs_view").html(html)



					var html = ""
					var count_phone = 0
					$.map(data.phones, function (item, key) {
						count_phone++
						html += '<div class="col-md-12 phone_add_'+count_phone+'">'
							html += '<div class="form-group">'
								html += '<label for=""><b>Telefono</b></label>'
								html += '<input type="number" name="telefono2[]" class="form-control form-control-user"  placeholder="PJ. 315 2077862" value="'+item.phone+'" disabled>'
							html += '</div>'
						html += '</div>'


						html += '<div class="col-md-2 phone_add_'+count_phone+'"">'
						html += '<br>'
						//	html += '<button type="button" id="add_phone" onclick="deletePhone('+count_phone+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
						html += '</div>'


					});

					$("#phone_add_content_view").html(html)




					var html = ""
					var count_email = 0
					$.map(data.emails, function (item, key) {
						count_email++
						html += '<div class="col-md-12 email_add_'+count_email+'">'
							html += '<div class="form-group">'
								html += '<label for=""><b>E-mail</b></label>'
								html += '<input type="email" name="email2[]" class="form-control form-control-user"  value="'+item.email+'" disabled>'
							html += '</div>'
						html += '</div>'


						html += '<div class="col-md-2 email_add_'+count_email+'"">'
						html += '<br>'
						//	html += '<button type="button" id="add_email" onclick="deleteemail('+count_email+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
						html += '</div>'


					});

					$("#email_add_content_view").html(html)




					cuadros('#cuadro1', '#cuadro3');
/*

					var url = document.getElementById('ruta').value+"/valuations/client/"+data.id_cliente+"/0"
					$('#iframeValuationsView').attr('src', url);


					var url = document.getElementById('ruta').value+"/preanesthesia/client/"+data.id_cliente+"/0"
					$('#iframepPreanestesiaView').attr('src', url);


					var url = document.getElementById('ruta').value+"/surgeries/client/"+data.id_cliente+"/0"
					$('#iframepCirugiaView').attr('src', url);


					var url = document.getElementById('ruta').value+"/revision-appointment/client/"+data.id_cliente+"/0"
					$('#iframepRevisionView').attr('src', url);


					var url = document.getElementById('ruta').value+"/clients/tasks/"+data.id_cliente+"/0"
					$('#iframepTracingView').attr('src', url);


*/
				valuations("#tab4_view", "#iframeValuationsView", data)
				preanestesias("#tab5_view", "#iframepPreanestesiaView", data)
				surgeries("#tab6_view", "#iframepCirugiaView", data)
				revisiones("#tab7_view", "#iframepRevisionView", data)
				tasks("#tab8_view", "#iframepTracingView", data)


				});
			}



			function GetAsesorasValoracion2(select, select_default = false){
				console.log(select_default)
				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/get-asesoras',
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
					},
					dataType:'JSON',
				//	async: false,
					beforeSend: function(){
					// mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
					},
					error: function (data) {
					//mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');
					},
					success: function(data){
					$(select+" option").remove();
					$(select).append($('<option>',
					{
						value: "",
						text : "Seleccione"
					}));

					$.each(data, function(i, item){
						if (item.status == 1) {

							$(select).append($('<option>',
							{
								value: item.id,
								text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
								selected: select_default == item.id ? true : false
							}));

						}
					});

					}
				});
			}
			/* ------------------------------------------------------------------------------- */
			/*
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function edit(tbody){
				$(tbody).on("click", "span.editar", function(){
					$("#alertas").css("display", "none");


					var data = JSON.parse($(this).attr("data"))


                    $(".nav-link").removeClass("active")
                    $("#tab0").addClass("active")
                    $(".tab-pane").removeClass("active")
                    $(".tab-pane").removeClass("show")

                    $(".tab_content0").addClass("active")
                    $(".tab_content0").addClass("show")



					GetCity("#city_edit");
					GetClinic("#city_edit", "#clinic_edit")
				//	GetAsesorasbyBusisnessLine("#linea-negocio-edit", "#asesora-edit");
					GetBusinessLine("#linea-negocio-edit");

					GetAsesorasValoracion2("#asesora-edit", data.id_user_asesora)

					Children("#children_edit", "#number_children_edit")
					Surgery("#surgery_edit", "#previous_surgery_edit")
					Disease("#disease_edit", "#major_disease_edit")
					Drugs("#drugs_check_edit", "#drugs_edit")
					Medication("#medication_edit", "#drink_medication_edit")
					Allergic("#allergic_edit ", "#allergic_medication_edit")


					GetAsesorasValoracion("#id_asesora_valoracion-edit")


					getCategory("#category_edit", data.id_category)
					ChangeCategory("#category_edit", "#sub_category_edit")


					$("#category_edit").trigger("change");
					$("#tipo_identificacion_edit").val(data.tipo_identificacion)
					$("#sub_category_edit").val(data.id_sub_category)

                    $("#procediminetos_cambios").val("")


					var html, count
					$("#tablecx_edit tbody").html("");
					$.map(data.procedures, function (item, key) {

						count++
						html += "<tr id='tr_procedure_edit_"+count+"'>"
							html += "<td>"+item.name+"<input type='hidden' name='sub_category[]' value='"+item.id_sub_category+"'></td>"
							html += "<td><span onclick='eliminarOp(\""+'#tr_procedure_edit_'+count+"\", \""+item.name+"\")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
						html += "</tr>"

						$("#tablecx_edit tbody").html(html);

					});




					$("#id_asesora_valoracion-edit").val(data.id_asesora_valoracion)
					$("#code-edit").text(data.code_client)

					$("#state_edit").val(data.state).trigger("change")

					$("#nombre_edit").val(data.nombres).trigger("change")
					$("#apellido_edit").val("")
					$("#identificacion_edit").val(data.identificacion)
					$("#telefono_edit").val(data.telefono)
					$("#email_edit").val(data.email)
					$("#direccion_edit").val(data.direccion)
					$("#fecha_nacimiento_edit").val(data.fecha_nacimiento)

					$("#origen_edit").val(data.origen)
					$("#forma_pago_edit").val(data.forma_pago)
                    $("#contrato_edit").val(data.contrato)


					$("#city_edit").val(data.city)
					$("#city_edit").trigger("change")

					$("#clinic_edit").val(data.clinic)
					$("#tipo_identificacion").val(data.tipo_identificacion)
					$("#year_edit").val(calcularEdad(data.fecha_nacimiento))

					$("#identificacion_verify_edit").prop("checked", data.identificacion_verify ? true : false)

					$("#name_surgery_edit").val(data.name_surgery)
					$("#observations_edit").val(data.observations)

					$("#current_size_edit").val(data.current_size)
					$("#desired_size_edit").val(data.desired_size)
					$("#implant_volumem_edit").val(data.implant_volumem)



					$("#facebook_edit").val(data.facebook)
					$("#instagram_edit").val(data.instagram)
					$("#twitter_edit").val(data.twitter)
					$("#youtube_edit").val(data.youtube)

                    $("#friend_facebook_edit").val(data.friend_facebook)
                    $("#followers_instagram_edit").val(data.followers_instagram)

					$("#photos_google_edit").val(data.photos_google)



					$("#prp_edit").val(data.prp)
					$("#prp_edit").trigger("change");



					$("#eps_edit").val(data.eps)
					$("#height_edit").val(data.height)
					$("#weight_edit").val(data.weight)

					$("#children_edit").prop("checked", data.children ? true : false)
					$("#smoke_edit").prop("checked", data.smoke ? true : false)
					$("#alcohol_edit").prop("checked", data.alcohol ? true : false)
					$("#surgery_edit").prop("checked", data.surgery ? true : false)
					$("#disease_edit").prop("checked", data.disease ? true : false)
					$("#drugs_check").prop("checked", data.drugs_check ? true : false)
					$("#medication_edit").prop("checked", data.medication ? true : false)
					$("#allergic_edit").prop("checked", data.allergic ? true : false)

					$("#number_children_edit").val(data.number_children).prop("readonly", data.children ? false : true)
					$("#previous_surgery_edit").val(data.previous_surgery).prop("readonly", data.surgery ? false : true)
					$("#major_disease_edit").val(data.major_disease).prop("readonly", data.disease ? false : true)
					$("#drugs_edit").val(data.drugs).prop("readonly", data.drugs_check ? false : true)
					$("#drink_medication_edit").val(data.drink_medication).prop("readonly", data.medication ? false : true)
					$("#allergic_medication_edit").val(data.allergic_medication).prop("readonly", data.allergic ? false : true)


					$("#dependent_independent_edit").val(data.dependent_independent)
					$("#type_contract_edit").val(data.type_contract)
					$("#antiquity_edit").val(data.antiquity)
					$("#average_monthly_income_edit").val(data.average_monthly_income)
					$("#previous_credits_edit").val(data.previous_credits)
					$("#reported_edit").val(data.reported)
					$("#bank_account_edit").val(data.bank_account)
					$("#have_initial_edit").val(data.have_initial)
					$("#properties_edit").prop("checked", data.properties ? true : false)
					$("#vehicle_edit").prop("checked", data.vehicle ? true : false)
					$("#pay_to_study_credit").prop("checked", data.pay_to_study_credit ? true : false)
                    $("#method_pay_study_credit_edit").val(data.payment_method)


					$("#linea-negocio-edit").val(data.id_line)
					$("#linea-negocio-edit").trigger("change");

					$("#asesora-edit").val(data.id_user_asesora)



                    $("#procedure_px_edit").val(data.procedure_px)
                    $("#date_procedure_edit").val(data.date_procedure)

					getStatus(data.id_cliente)
					cuadros('#cuadro1', '#cuadro4');



					SubmitComment(data.id_cliente, "api/comments/clients", "#comments_edit", "#add-comments", "#summernote_edit")




					$("#id_edit").val(data.id_cliente)



					var url=document.getElementById('ruta').value;
					var html = "";



				$.map(data.logs, function (item, key) {

					html += '<div class="col-md-10" style="padding: 2%;border-radius: 17px;">'
						html += '<div class="col-md-12" style="margin-bottom: 15px">'
							html += '<div class="row">'
								html += '<div class="col-md-2">'
									html += "<img class='rounded' src='"+url+"/img/usuarios/profile/"+item.img_profile+"' style='height: 4rem;width: 4rem; margin: 1%; border-radius: 50%!important;' title='"+item.name_follower+" "+item.last_name_follower+"'>"
								html += '</div>'
								html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;overflow: visible">'
									html += '<div>'+item.event+'</div>'
									html += '<div><b>'+item.name_user+" "+item.last_name_user+'</b> <span style="float: right">'+item.create_at+'</span></div>'
								html += '</div>'
							html += '</div>'
						html += '</div>'
					html += '</div>'

				});




					$("#logs_edit").html(html)

					var html = ""
					var count_phone = 0
					$.map(data.phones, function (item, key) {
						count_phone++
						html += '<div class="col-md-10 phone_add_edit_'+count_phone+'">'
							html += '<div class="form-group">'
								html += '<label for=""><b>Telefono</b></label>'
								html += '<input type="number" name="telefono2[]" class="form-control form-control-user"  placeholder="PJ. 315 2077862" value="'+item.phone+'">'
							html += '</div>'
						html += '</div>'


						html += '<div class="col-md-2 phone_add_edit_'+count_phone+'"">'
						html += '<br>'
							html += '<button type="button" id="add_phone" onclick="deletePhoneEdit('+count_phone+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
						html += '</div>'


					});

					$("#phone_add_content_edit").html(html)




					var html = ""
					var count_email = 0
					$.map(data.emails, function (item, key) {
						count_email++
						html += '<div class="col-md-10 email_add_edit_'+count_email+'">'
							html += '<div class="form-group">'
								html += '<label for=""><b>E-mail</b></label>'
								html += '<input type="email" name="email2[]" class="form-control form-control-user"  value="'+item.email+'">'
							html += '</div>'
						html += '</div>'


						html += '<div class="col-md-2 email_add_edit_'+count_email+'"">'
						html += '<br>'
							html += '<button type="button" id="add_email" onclick="deleteemailEdit('+count_email+')" class="btn btn-danger"><i class="fa fa-trash"></i></button>'
						html += '</div>'


					});

					$("#email_add_content_edit").html(html)





					$('#summernote_edit').summernote('reset');
					$('#summernote_edit').summernote({
						'height' : 200
					});


//

					var url_imagen = '/img/nutricional/'
					var url        = document.getElementById('ruta').value;


					if((data.nutricional != "" ) &&  (data.nutricional != null)){
						var ext = data.nutricional.split('.');
						if (ext[1] == "pdf") {
							img = '<embed class="kv-preview-data file-preview-pdf" src="'+url+url_imagen+data.nutricional+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
						}else{
							img = '<img src="'+url+url_imagen+data.nutricional+'" class="file-preview-image kv-preview-data">'
						}

					}else{img = ""}


					$("#nutricional_edit").fileinput('destroy');
					$("#nutricional_edit").fileinput({
						theme: "fas",
						overwriteInitial: true,
						maxFileSize: 1500,
						showClose: false,
						showCaption: false,
						browseLabel: '',
						removeLabel: '',
						browseIcon: '<i class="fa fa-folder-open"></i>',
						removeIcon: '<i class="fas fa-trash-alt"></i>',
						previewFileIcon: '<i class="fas fa-file"></i>',
						removeTitle: 'Cancel or reset changes',
						elErrorContainer: '#kv-avatar-errors-1',
						msgErrorClass: 'alert alert-block alert-danger',

						layoutTemplates: {main2: '{preview}  {remove} {browse}'},
						allowedFileExtensions: ["jpg", "png", "gif", "pdf", "docs"],
						initialPreview: [
							img
						],

						initialPreviewConfig: [
							{caption: data.cotizacion , downloadUrl: url+url_imagen+data.cotizacion  ,url: url+"uploads/delete", key: data.cotizacion}
						],
					});




					var url=document.getElementById('ruta').value;
					var html = "";




					GetComments("#comments_edit", data.id_cliente)



					valuations("#tab4_edit", "#iframeValuationsEdit", data)
					preanestesias("#tab5_edit", "#iframepPreanestesiaEdit", data)
					surgeries("#tab6_edit", "#iframepCirugiaEdit", data)
					revisiones("#tab7_edit", "#iframepRevisionEdit", data)
					tasks("#tab8_edit", "#iframepTracingEdit", data)

					masajes("#tab9_edit", "#iframepMsajesEdit", data)
					hiperbarica("#tab12_edit", "#iframehiperbaricaEdit", data)
					nutricional("#tab14_edit", "#iframenutricionalEdit", data)

					examenes("#tab11_edit", "#iframeexamenesEdit", data)
					reffered("#tab10_edit", "#iframepRefferedsEdit", data)


					cuadros('#cuadro1', '#cuadro4');

					getTasksAdvisers(data.id_cliente)
				});
			}



			function eliminarOp(tr, name_cx){
				$(tr).remove();
				var procedimientos=[]
				procedimientos.push($("#procediminetos_cambios").val())
				procedimientos.push("Se elimino un procedimiento " + name_cx)
				$("#procediminetos_cambios").val(procedimientos)
			}






			function getTasksAdvisers(id_client){


				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/clients/tasks/advisers/'+id_client,
					type:'GET',
					dataType:'JSON',

					success: function(data){
						//$("#testimony").prop("checked", data.testimony ? true : false)
						//$("#testimony_date").val(data.testimony_date)

						$("#before_and_after").prop("checked", data.before_and_after ? true : false)
						$("#before_and_after_date").val(data.before_and_after_date)

						//$("#califications").prop("checked", data.califications ? true : false)
						//$("#califications_date").val(data.califications_date)

						$("#survey").prop("checked", data.survey ? true : false)
					}
				});


			}


			function tasks(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/clients/tasks/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}



			function revisiones(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/revision-appointment/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function surgeries(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/surgeries/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function valuations(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/valuations/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function preanestesias(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/preanesthesia/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}



			function hiperbarica(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/hiperbarica/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function nutricional(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/nutricional/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function masajes(tab, iframe, data){
				$(tab).click(function (e) {

					var url = document.getElementById('ruta').value+"/masajes/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function examenes(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/examenes/client/"+data.id_cliente+"/1"
					$(iframe).attr('src', url);

				});
			}


			function reffered(tab, iframe, data){
				$(tab).click(function (e) {
					var url = document.getElementById('ruta').value+"/clients/reffereds/"+data.id_cliente+""
					$(iframe).attr('src', url);

				});
			}










			function copyToClipboard(element) {
				var $temp = $("<input>");
				$("body").append($temp);
				$temp.val($(element).text()).select();
				document.execCommand("copy");
				$temp.remove();

				mensajes('success', "Codigo: "+$(element).text()+" Copiado");


			}




			$("#add-comments").click(function (e) {
				/*
				var html = ""


				html += '<div class="col-md-12" style="margin-bottom: 15px">'
					html += "<input type='hidden' name='comments[]' value='"+$("#summernote_edit").val()+"'>"
					html += '<div class="row">'
						html += '<div class="col-md-2">'
							//html += "<img class='rounded' src='/img/usuarios/profile/"+item.img_profile+"' style='height: 4rem;width: 4rem; margin: 1%; border-radius: 50%!important;' title='"+item.name_follower+" "+item.last_name_follower+"'>"

						html += '</div>'
						html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;">'
							html += '<div>'+$("#summernote_edit").val()+'</div>'

							html += '<div><b></b> <span style="float: right">Ahora Mismo</span></div>'

						html += '</div>'
					html += '</div>'
				html += '</div>'

				$("#comments_edit").append(html)

				$('#summernote_edit').summernote('reset');

				*/
			});







			function GetClinic(city, select){
				$(city).unbind().change(function (e) {
					GetClinicByCity(select, $(this).val())
				});
			}

			function Children(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}



			function Surgery(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}



			function Disease(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}

			function Drugs(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}


			function Medication(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}

			function Allergic(checkbox, input){
				$(checkbox).change(function (e) {
					if ($(checkbox).is(':checked')){
						$(input).removeAttr("readonly").focus();
					}else{
						$(input).val("").attr("readonly", "readonly");
					}
				});
			}




			$("#identificacion").change(function (e) {

				var url=document.getElementById('ruta').value;
				$.ajax({
					url:''+url+'/api/clients/identification/'+$(this).val(),
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
					},
					dataType:'JSON',

					beforeSend: function(){
					// mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
					},
					error: function (data) {
						$(".disabled").removeAttr("disabled")
						//$(".disabled").val("")

						$("#state").trigger("change")
						$("#city").trigger("change")
						$("#linea-negocio").trigger("change");


						$(".tabs-remove").css("display", "none")
						$("#btn-store").removeAttr("disabled")


					},
					success: function(data){

						$(".disabled").attr("disabled", "disabled")

						$("#state").val(data.state).trigger("change")

						$("#nombre").val(data.nombres).trigger("change")
						$("#apellido").val(data.apellidos)
						$("#telefono").val(data.telefono)
						$("#email").val(data.email)
						$("#direccion").val(data.direccion)
						$("#fecha_nacimiento").val(data.fecha_nacimiento)

						$("#origen").val(data.origen)
						$("#forma_pago").val(data.forma_pago)


						$("#city").val(data.city)
						$("#city").trigger("change")

						$("#clinic").val(data.clinic)

						$("#year").val(calcularEdad(data.fecha_nacimiento))

						$("#identificacion_verify").prop("checked", data.identificacion_verify ? true : false)

						$("#name_surgery").val(data.name_surgery)


						$("#linea-negocio").val(data.id_line)
						$("#linea-negocio").trigger("change");

						$("#asesora").val(data.id_user_asesora)


						$(".tabs-remove").css("display", "block")

						$("#btn-store").attr("disabled", "disabled")


						var url = document.getElementById('ruta').value+"/valuations/client/"+data.id_cliente+"/0"
						$('#iframeValuationsStore').attr('src', url);

						var url = document.getElementById('ruta').value+"/preanesthesia/client/"+data.id_cliente+"/0"
						$('#iframepPreanestesiaStore').attr('src', url);


						var url = document.getElementById('ruta').value+"/surgeries/client/"+data.id_cliente+"/0"
						$('#iframepCirugiaStore').attr('src', url);


						var url = document.getElementById('ruta').value+"/revision-appointment/client/"+data.id_cliente+"/0"
						$('#iframepRevisionStore').attr('src', url);

					}
				});
			});




		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data = JSON.parse($(this).attr("data"))
					statusConfirmacion('api/status-cliente/'+data.id_cliente+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
				});
			}
		/* ------------------------------------------------------------------------------- */

		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function activar(tbody, table){
				$(tbody).on("click", "span.activar", function(){
					var data = JSON.parse($(this).attr("data"))
					statusConfirmacion('api/status-cliente/'+data.id_cliente+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data = JSON.parse($(this).attr("data"))
					statusConfirmacionClient('api/status-cliente/'+data.id_cliente+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
            }


			function aprobar(tbody, table){
				$(tbody).on("click", "span.aprobar", function(){
					var data=table.row($(this).parents("tr")).data();

					var url = document.getElementById('ruta').value
					$.ajax({
						url:''+url+'/api/clients/aproved/'+data.id,
						type:'GET',
						dataType:'JSON',

					success: function(data){
						list()
					}
				});


					console.log(data)
				});
            }






            function statusConfirmacionClient(controlador,  title, confirmButton){

                var data = {
                "id_user": id_user,
                "token"  : tokens,
                };


                swal({
                    title: title,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si, "+confirmButton+"!",
                    cancelButtonText: "No, Cancelar!",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable
                        $.ajax({
                            url:''+url+'/'+controlador+'/',
                            type: 'GET',
                            dataType: 'JSON',
                        //  data: data,
                            beforeSend: function(){
                                mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                            },
                            error: function (repuesta) {
                                var errores=repuesta.responseText;
                                mensajes('danger', errores);
                            },
                            success: function(respuesta){
                            mensajes('success', respuesta.mensagge);
                            list('', number_page);
                            }
                        });
                    } else {
                        swal("Cancelado", "Proceso cancelado", "error");
                    }
                });
            }



		  $("#fecha_nacimiento").change(function (e) {
			  $("#year").val(calcularEdad($(this).val()))
		  });


		  $("#fecha_nacimiento_edit").change(function (e) {
			  $("#year_edit").val(calcularEdad($(this).val()))
		  });




		  function getCategory(select, select_default = false){

			$.ajax({
				url: ''+document.getElementById('ruta').value+'/api/category',
				type:'GET',
				data: {
					"id_user": id_user,
					"token"  : tokens,
				},
				dataType:'JSON',
				async: false,
				error: function() {

				},
				success: function(data){
					$(select+" option").remove();
					$(select).append($('<option>',
					{
						value: "",
						text : "Seleccione"
					}));

					$.each(data, function(i, item){


						$(select).append($('<option>',
						{
							value: item.id,
							text : item.name,
							selected : select_default == item.id ? true : false

						}));


					});

				}

			});
		}


		function ChangeCategory(select, select_sub, select_default = false){
			$(select).change(function (e) {

				$.ajax({

					url: ''+document.getElementById('ruta').value+'/api/category/sub/'+$(select).val(),
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
					},
					dataType:'JSON',
					async: false,
					error: function() {

					},
					success: function(data){
						console.log(data)
						$(select_sub+" option").remove();
						$(select_sub).append($('<option>',
						{
							value: "",
							text : "Seleccione"
						}));

						$.each(data, function(i, item){


							$(select_sub).append($('<option>',
							{
								value: item.id,
								text : item.name,
								selected : select_default == item.id ? true : false

							}));


						});

					}

				});

			});
		}


		var count = 0
		$("#btn-add-surgerie").click(function (e) {
			count++
			var html
			html += "<tr id='tr_procedure_"+count+"'>"
				html += "<td>"+$("#sub_category option:selected").text()+"<input type='hidden' name='sub_category[]' value='"+$("#sub_category").val()+"'></td>"
				html += "<td><span onclick='eliminarTr(\""+'#tr_procedure_'+count+"\")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
			html += "</tr>"

			$("#tablecx tbody").append(html);

		});
//

		var count2 = 0
		$("#btn-add-surgerie-edit").click(function (e) {
			count2++
			var html
			html += "<tr id='tr_procedure_edit2_"+count2+"'>"
				html += "<td>"+$("#sub_category_edit option:selected").text()+"<input type='hidden' name='sub_category[]' value='"+$("#sub_category_edit").val()+"'></td>"
				html += "<td><span onclick='eliminarTr(\""+'#tr_procedure_edit2_'+count2+"\")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
			html += "</tr>"

			$("#tablecx_edit tbody").append(html);

			var procedimientos=[]
		    procedimientos.push($("#procediminetos_cambios").val())
			procedimientos.push("Se Agrego "+ $("#sub_category_edit option:selected").text())




			$("#procediminetos_cambios").val(procedimientos)
		});




		$("#pay_consultation").change(function (e) {

			if(!$('#pay_consultation').is(':checked') ) {
				$("#code_prp-store").removeAttr("disabled").focus()
				$("#code_prp-store").attr("required", "required")
				$("#way_to_pay-store").removeAttr("required")
			}else{
				$("#code_prp-store").removeAttr("required")
				$("#code_prp-store").attr("disabled", "disabled")
				$("#way_to_pay-store").attr("required", "required")
			}

		});



		$("#way_to_pay-store").change(function (e) {

			if($(this).val() == "Transferencia"){
				$("#content_acquittance").css("display", "block")
				$("#acquittance").attr("required", "required")
			}else{
				$("#content_acquittance").css("display", "none")
				$("#acquittance").removeAttr("required")
			}

		});


		$("#process_status").click(function(e) {
		    ProcesStatus()
	     });

		 function getStatus(id_cliente){
			try {
				var url = document.getElementById('ruta').value;
				$.ajax({
					url: '' + url + '/api/clients/request/financing/status/credit/'+id_cliente,
					type:'GET',
					dataType:'JSON',
					error: function() {},
					success: function(data){
                        console.log(data, "CREDIT")
						$("#id_cliente").val(data.id_client)
						if(data.status == 'Pendiente'){
							$("#process_status").css('display','block')
						}else{
							$("#process_status").css('display','none')
						}

                        $("#method_pay_study_credit_edit").val(data.payment_method)
                        $("#date_pay_study_credit_edit").val(data.created_at)
                        $("#id_transaccion").val(data.id_transactions)


						if(data.photo_recived){
						    $("#load_img").attr('src', `img/credit/comprobantes/${data.photo_recived}`)
						}else{
						$("#load_img").attr('src', ``)
						}
					}
				});
			} catch (e) {
				console.log(e)
			}
		}
		function ProcesStatus() {
		try {
			var url = document.getElementById('ruta').value;
			$.ajax({
				url: '' + url + '/api/clients/request/financing/updated/status',
				type: 'POST',
				data:{ id:$("#id_cliente").val() },
				error: function() {},
					success: function(data){
						alert("La solicitud fue procesada correctamente");
						$("#process_status").css('display','none')
					}
			});
		} catch (e) {
			console.log(e)
		}




	}



	$("#state_edit").change(function (e) {

		if($(this).val() == "Operada"){
			$("#section_procedure").css("display", "block")
			$("#procedure_px_edit").removeAttr("disabled").attr("required", "required")
			$("#date_procedure_edit").removeAttr("disabled").attr("required", "required")
		}else{
			$("#section_procedure").css("display", "none")
			$("#procedure_px_edit").removeAttr("required").attr("disabled", "disabled")
			$("#date_procedure_edit").removeAttr("required").attr("disabled", "disabled")
		}

	});

	</script>

	@endsection


