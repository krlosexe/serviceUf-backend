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
			          <h1 class="h3 mb-2 text-gray-800">Productos</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Productos</h6>

			              <button onclick="nuevo()" class="btn btn-primary btn-icon-split" style="float: right;">
		                    <span class="icon text-white-50">
		                      <i class="fas fa-plus"></i>
		                    </span>
		                    <span class="text">Nuevo registro</span>
		                  </button>
			            </div>
			            <div class="card-body">
			              <div class="table-responsive">
			                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
								  <th>Acciones</th>
								  <th>Codigo</th>
								  <th>Descripcion</th>
								  <th>Precio COP</th>
			                      <th>Fecha de registro</th>
								  <th>Registrado por</th>
			                    </tr>
			                  </thead>
			                  <tbody>

			                  </tbody>
			                </table>
			              </div>
			            </div>
			          </div>


			          @include('warehouse.products.store')
					  @include('warehouse.products.view')
					  @include('warehouse.products.edit')


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
				list();
				update();

				$("#collapse_Almacen").addClass("show");
				$("#nav_products, #modulo_Almacen").addClass("active");

				verifyPersmisos(id_user, tokens, "products");
			});



			function update(){
				enviarFormularioPut("#form-update", 'api/products', '#cuadro4', false, "#avatar-edit");
			}


			function store(){
				enviarFormulario("#store", 'api/products', '#cuadro2');
			}



			function list(cuadro) {

				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};
				$('#table tbody').off('click');
				var url=document.getElementById('ruta').value;
				cuadros(cuadro, "#cuadro1");

				var table=$("#table").DataTable({
					"destroy":true,

					"stateSave": true,
					"serverSide":false,
					"ajax":{
						"method":"GET",
						 "url":''+url+'/api/products',
						 "data": {
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
									botones += "<span class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
								if(actualizar == 1)
									botones += "<span class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
								if(data.status == 1 && actualizar == 1)
									botones += "<span class='desactivar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
								else if(data.status == 2 && actualizar == 1)
									botones += "<span class='activar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
								if(borrar == 1)
									botones += "<span class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span>";

								botones += "<a href='api/invoice/print/"+row.id+"' target='_blank' class='print btn btn-sm btn-success waves-effect' data-toggle='tooltip' title='Imprmir'><i class='fas fa-print' style='margin-bottom:5px'></i></a> ";
								return botones;
							}
						},
						{"data":"code"},
						{"data":"description"},

						{"data":"price_cop",
							render : (data, type, row) => {
								return number_format(data, 2)
							}

						},
						{"data": "fec_regins"},
						{"data": "email_regis"}

					],
					"language": idioma_espanol,
					"dom": 'Bfrtip',
					"responsive": true,
					"buttons":[
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				});


				ver("#table tbody", table)
				edit("#table tbody", table)
				activar("#table tbody", table)
				desactivar("#table tbody", table)
				eliminar("#table tbody", table)


			}



			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();

				cuadros("#cuadro1", "#cuadro2");
			}

			/* ------------------------------------------------------------------------------- */
			/*
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function ver(tbody, table){
				$(tbody).on("click", "span.consultar", function(){
					$("#alertas").css("display", "none");
					var data = table.row( $(this).parents("tr") ).data();

					$("#code_view").val(data.code).attr("disabled", "disabled")
					$("#description_view").val(data.description).attr("disabled", "disabled")
					$("#precio_cop_view").val(data.price_cop).attr("disabled", "disabled")
					$("#presentation_view").val(data.presentation).attr("disabled", "disabled")

					GetCategories("#category_view", data.category)


					cuadros('#cuadro1', '#cuadro3');
				});
			}



			/* ------------------------------------------------------------------------------- */
			/*
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function edit(tbody, table){
				$(tbody).on("click", "span.editar", function(){
					$("#alertas").css("display", "none");
					var data = table.row( $(this).parents("tr") ).data();

					$("#code_edit").val(data.code)
					$("#description_edit").val(data.description)
					$("#register_invima_edit").val(data.register_invima)
					$("#precio_cop_edit").val(data.price_cop)
					$("#presentation_edit").val(data.presentation)

					$("#price_distributor_x_caja_edit").val(data.price_distributor_x_caja)
					$("#price_distributor_x_vial_edit").val(data.price_distributor_x_vial)
					$("#price_cliente_x_caja_edit").val(data.price_cliente_x_caja)
					$("#price_cliente_x_vial_edit").val(data.price_cliente_x_vial)

					$("#available_store_edit").prop("checked", data.available_store ? true : false)

					GetCategories("#categorie_edit")

					var categories = []
					$.each(data.categories, function (key, item) {
						categories.push(item.id_category)
					});

					$("#categorie_edit").val(categories)


					if(data.photo != ""){
						img = '<img src="'+data.photo+'" class="file-preview-image kv-preview-data">'
					}else{rfc2c = ""}
					$("#avatar-edit").fileinput("destroy")
					$("#avatar-edit").fileinput({
						theme: "fas",
						overwriteInitial: true,
						maxFileSize: 10000,
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
						defaultPreviewContent: '<img src="img/default-user.png" width="150" alt="Your Avatar">',
						layoutTemplates: {main2: '{preview}  {remove} {browse}'},
						allowedFileExtensions: ["jpg", "png", "gif", "jpeg"],
						initialPreview: [
							img
						],
						initialPreviewConfig: [

							{caption: data.photo , downloadUrl: data.photo  ,url: "uploads/delete", key: data.photo}

						],

					});






					$("#id_edit").val(data.id)
					cuadros('#cuadro1', '#cuadro4');
				});
			}




		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/status/'+data.id+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
				});
			}
		/* ------------------------------------------------------------------------------- */

		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function activar(tbody, table){
				$(tbody).on("click", "span.activar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/status/'+data.id+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/status/'+data.id+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}






			function GetCategories(select){
				var url=document.getElementById('ruta').value;

				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};
				$.ajax({
					url:''+url+'/api/categories',
					type:"GET",
					data: data,
					dataType:'JSON',
					async: false,
					beforeSend: function(){

					},
					error: function (repuesta) {

					},
					success: function(data){
						$(select+" option").remove();
			            $.each(data, function(i, item){
			                if (item.status == 1) {
			                    $(select).append($('<option>',
			                     {
			                        value: item.id,
			                        text : item.name
			                    }));
			                }
			            });
					}

				});
			}





		</script>




	@endsection


