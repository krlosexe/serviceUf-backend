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
			          <h1 class="h3 mb-2 text-gray-800">Cotizaciones</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Cotizaciones</h6>

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
								  <th>#</th>
								  <th>Tipo</th>
								  <th>Cliente</th>
								  <th>Bodega</th>
								  <th>Valor de Factura</th>
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


			          @include('warehouse.quotes.store')
					  @include('warehouse.quotes.view')
					  @include('warehouse.quotes.edit')


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

		  <input type="hidden" id="indicador_edit">


	@endsection





	@section('CustomJs')

		<script>
			$(document).ready(function(){
				store();
				list();
				update();

				$("#collapse_Almacen").addClass("show");
				$("#nav_quotes, #modulo_Almacen").addClass("active");

				verifyPersmisos(id_user, tokens, "output");
			});



			function update(){
				enviarFormularioPut("#form-update", 'api/products/entry/output', '#cuadro4', false, "#avatar-edit");
			}


			function store(){
				enviarFormulario("#store", 'api/products/entry/output', '#cuadro2');
			}





			function list(cuadro) {

				var data = {
					"id_user": id_user,
					"token"  : tokens,
					"type"   : "Cotizacion"
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
						 "url":''+url+'/api/products/entry/output',
						 "data": {
							"id_user": id_user,
							"token"  : tokens,
							"type"   : "Cotizacion"
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

								if(actualizar == 1)
									botones += "<a href='api/invoice/print/"+row.id+"' target='_blank' class='print btn btn-sm btn-success waves-effect' data-toggle='tooltip' title='Imprmir'><i class='fas fa-print' style='margin-bottom:5px'></i></a> ";

								return botones;
							}
						},
						{"data":"number"},
						{"data": "type"},
						{"data":"name_client"},
						{"data":"warehouse"},
						{"data": "total_invoice",
							render : function(data, type, row) {
								var botones = "";

								return number_format(data, 2);
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



				$(".buttons-excel").remove()


				var a = '<button id="xls" class="dt-button buttons-excel buttons-html5">Excel</button>';
				$(".dt-buttons").append(a)

				var b = '<button id="view_xls" target="_blank" style="opacity: 0" href="api/output/export/excel" class="dt-button buttons-excel buttons-html5">xls</button>';
				$('.dt-buttons').append(b);



				$("#xls").click(function (e) {
					url = $("#view_xls").attr("href");

					console.log(url)
					window.open(url, '_blank');
				});


			}








			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();

				ProductsGetExistence("#commercial_premises", "#products", "#table_products", '')

				GetComercialPremisesNew("#commercial_premises")

				AddProductos("#add_product", "#products", "#table_products")


				getClients("#clients")


				$("#commercial_premises").trigger("change")


				$("#indicador_edit").val(0)

				$("#content_pay").html("")


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

					$("#warehouse_view").val(data.warehouse).trigger("change").attr("disabled", "disabled")


					getClients("#clients_view", data.id_client)



					if(data.discount_total > 0){
						$("#apply_discount_view").prop("checked", true)
					}else{
						$("#apply_discount_view").prop("checked", false)
					}


					$("#subtotal_text_view").text(`$ ${number_format(data.subtotal, 2)}`)
					$("#subtotal_view").val(data.subtotal)
					$("#subtotal_with_discount_view").val(data.subtotal_with_discount)


					$("#vat_total_text_view").text(`$ ${number_format(data.vat_total, 2)}`)
					$("#vat_total_view").val(data.vat_total)

					$("#discount_total_view").val(data.discount_total)
					$("#discount_total_text_view").text(number_format(data.discount_total, 2))

					$("#total_invoice_text_view").text(`$ ${number_format(data.total_invoice, 2)}`)
					$("#total_invoice_view").val(data.total_invoice)


					$("#observations_view").val(data.observations)







					ShowProdcuts("#table_products_view", data.products)
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



					$("#indicador_edit").val(1)

					getClients("#clients_edit", data.id_client)


					ProductsGetExistence("#commercial_premises_edit", "#products_edit", "#table_products_edit", '_edit', data.id_seller)

					GetComercialPremisesNew("#commercial_premises_edit")

					$("#commercial_premises_edit").val(data.commercial_premises).trigger("change")

					ShowProdcuts("#table_products_edit", data.products)
					AddProductosEdit("#add_product_edit", "#products_edit", "#table_products_edit")


					if(data.discount_total > 0){
						$("#apply_discount_edit").prop("checked", true)
					}else{
						$("#apply_discount_edit").prop("checked", false)
					}

					$("#type_edit").val(data.type)
					$("#subtotal_text_edit").text(`$ ${number_format(data.subtotal, 2)}`)
					$("#subtotal_edit").val(data.subtotal)
					$("#subtotal_with_discount_edit").val(data.subtotal_with_discount)


					$("#vat_total_text_edit").text(`$ ${number_format(data.vat_total, 2)}`)
					$("#vat_total_edit").val(data.vat_total)

					$("#discount_total_edit").val(data.discount_total)
					$("#discount_total_text_edit").text(number_format(data.discount_total, 2))

					$("#total_invoice_text_edit").text(`$ ${number_format(data.total_invoice, 2)}`)
					$("#total_invoice_edit").val(data.total_invoice)


					$("#observations_edit").val(data.observations)
					$("#content_pay").html("")
					$("#seller_edit").val(data.id_seller)

					ShowPays(data.pays)
					$("#id_edit").val(data.id)
					cuadros('#cuadro1', '#cuadro4');
				});
			}

			$("#print").click(function (e) {

				window.open(`api/invoice/print/${$("#id_edit").val()}`, "_blank");

			});


		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/entry/output/status/'+data.id+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
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
					statusConfirmacion('api/products/entry/output/status/'+data.id+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/entry/output/status/'+data.id+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}


			function ProductsGetExistence(commercial_premises, product, table, edit, seller = false) {
				$(commercial_premises).unbind().change(function (e) {

					$(table+" tbody").html("")


					$.ajax({
						url: `${document.getElementById('ruta').value}/api/inventory/${$(this).val()}`,
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


							console.log(product)

							$(product+" option").remove();
							$(product).append($('<option>',
							{
								value: "",
								text : "-Seleccione"
							}));

							$.each(data, function(i, item){


								$(product).append($('<option>',
								{
									value: `${item.id_product}|${item.existence}|${item.price}|${item.presentation}`,
									text : item.name_product,

								}));


							});


							$(product).select2({
								width : "100%",
								sorter: function(data) {
									/* Sort data using lowercase comparison */
									return data.sort(function (a, b) {
										a = a.text.toLowerCase();
										b = b.text.toLowerCase();
										if (a > b) {
											return 1;
										} else if (a < b) {
											return -1;
										}
										return 0;
									});
								}
							});

						}

					});

					console.log(`#seller${edit}`, "SELECRT")
					$.ajax({
						url: `${document.getElementById('ruta').value}/api/get/seller/${$(this).val()}`,
						type:'GET',
						data: {
							"id_user": id_user,
							"token"  : tokens,
						},
						dataType:'JSON',
						async: false,
						error: function() {

						},
						success: function(data2){
							$(`#seller${edit} option`).remove();
							$(`#seller${edit}`).append($('<option>',
							{
								value: "",
								text : "-Seleccione"
							}));

							$.each(data2, function(i, item){

								$(`#seller${edit}`).append($('<option>',
								{
									value: `${item.id}`,
									text :`${item.nombres} ${item.apellido_p}`,
									selected : seller == item.id ? true : false
								}));

							});

							$(`#seller${edit}`).select2({
								width : "100%",
								sorter: function(data) {
									/* Sort data using lowercase comparison */
									return data.sort(function (a, b) {
										a = a.text.toLowerCase();
										b = b.text.toLowerCase();
										if (a > b) {
											return 1;
										} else if (a < b) {
											return -1;
										}
										return 0;
									});
								}
							});

						}

					});


				});
			}








			function getProducts(select, select_default = false){

				$.ajax({
					url: ''+document.getElementById('ruta').value+'/api/products',
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
							text : "- Seleccione"
						}));

						$.each(data, function(i, item){


							$(select).append($('<option>',
							{
								value: item.id,
								text : item.description,
								selected : select_default == item.id ? true : false

							}));


						});


						$(select).select2({
							width : "100%",
							sorter: function(data) {
								/* Sort data using lowercase comparison */
								return data.sort(function (a, b) {
									a = a.text.toLowerCase();
									b = b.text.toLowerCase();
									if (a > b) {
										return 1;
									} else if (a < b) {
										return -1;
									}
									return 0;
								});
							}
						});

					}

				});
			}




			function AddProductos(btn, select_product, table){
				$(btn).unbind().click(function (e) {



					const array_product       = $(select_product).val().split("|")
					const id_product          = array_product[0]
					const total               = array_product[1]
					const price               = array_product[2]
					const presentation    = array_product[3]
					const description   = $(`${select_product} option:selected`).text()

					var html

					var validaProduct = false
					$(table + " tbody tr").each(function() {
						if (id_product == $(this).find(".id_product").val()) {
							validaProduct = true;
						}
					});

					if(!validaProduct){
						html += "<tr>"
							html +="<td>"+description+" <input type='hidden' class='id_product' name='id_product[]' value='"+id_product+"' > </td>"
							html +="<td>"+presentation+" </td>"
							html +="<td><input type='number' class='form-control items_calc price_product' name='price[]' value='"+price+"' min = '1' onkeyup='calcProduc(this)' style='text-align: right'  required></td>"
							html +="<td><input type='number' class='form-control items_calc qty_product' name='qty[]' value='0' min = '1' onchange='calcProduc(this)' max='"+total+"' required></td>"
							html +="<td><input type='number' disabled class='form-control items_calc existence' value='"+total+"' min = '1' required><input type='hidden' disabled class='form-control items_calc existence_hidden' value='"+total+"'></td>"
							html +="<td><input type='checkbox' class='form-control vat_product items_calc' checked  onchange='calcProduc(this)'><input type='hidden' class='vat_hidden' name='vat[]' value='0'></td>"
							html +="<td><input type='text' readonly class='form-control items_calc total_product' name='total[]'  required style='text-align: right'></td>"
							html +="<td><span onclick='deleteProduct(this, "+'""'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
						html += "</tr>"
					}else{
						warning('¡La opción seleccionada ya se encuentra agregada!');
					}



					$(table+" tbody").append(html)

				});
			}






			function AddProductosEdit(btn, select_product, table){
				$(btn).unbind().click(function (e) {

					const array_product       = $(select_product).val().split("|")
					const id_product          = array_product[0]
					const total               = array_product[1]
					const price               = array_product[2]
					const presentation    = array_product[3]
					const description   = $(`${select_product} option:selected`).text()

					var html

					var validaProduct = false
					$(table + " tbody tr").each(function() {
						if (id_product == $(this).find(".id_product").val()) {
							validaProduct = true;
						}
					});

					if(!validaProduct){
						html += "<tr>"
							html +="<td>"+description+" <input type='hidden' class='id_product' name='id_product[]' value='"+id_product+"' > </td>"
							html +="<td>"+presentation+" </td>"
							html +="<td><input type='number' class='form-control items_calc price_product' name='price[]' value='"+price+"' min = '1' onkeyup='calcProduc(this, "+'"_edit"'+")' style='text-align: right'  required></td>"
							html +="<td><input type='number' class='form-control items_calc qty_product' name='qty[]' value='0' min = '1' onchange='calcProduc(this, "+'"_edit"'+")' max='"+total+"' required></td>"
							html +="<td><input type='number' disabled class='form-control items_calc existence' value='"+total+"' min = '1' required><input type='hidden' disabled class='form-control items_calc existence_hidden' value='"+total+"'></td>"
							html +="<td><input type='checkbox' class='form-control vat_product items_calc' checked  onchange='calcProduc(this, "+'"_edit"'+")'><input type='hidden' class='vat_hidden' name='vat[]' value='0'></td>"
							html +="<td><input type='text' readonly class='form-control items_calc total_product' name='total[]'  required style='text-align: right'></td>"
							html +="<td><span onclick='deleteProduct(this, "+'""'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
						html += "</tr>"
					}else{
						warning('¡La opción seleccionada ya se encuentra agregada!');
					}



					$(table+" tbody").append(html)

				});
			}




			function ShowProdcuts(table, data){

				console.log(data)
				$(table+" tbody").html("")
				$.map(data, function (item, key) {
					let html = ""
					html += "<tr>"
						html +="<td>"+item.description+" <input type='hidden' class='id_product' name='id_product[]' value='"+item.id_product+"' > </td>"
						html +="<td>"+item.presentation+"</td>"


						html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales price_product items_calc' value='"+number_format(item.price_cop, 2)+"'  onkeyup='calcProduc(this, "+'"_edit"'+")' name='price[]' required></td>"


						html +="<td><input type='number' class='form-control qty_product items_calc' name='qty[]' value='"+item.qty+"' max="+item.existence+" onchange='calcProduc(this, "+'"_edit"'+")' required><input type='hidden' class='form-control qty_product_hidden items_calc' value='"+item.qty+"' disabled></td>"

						html +="<td><input type='number' class='form-control  items_calc existence' name='existence'  value='"+item.existence+"' disabled><input type='hidden' disabled class='form-control items_calc existence_hidden' value='"+item.existence+"' disabled></td>"


						if(item.vat == 1){
							html +="<td><input type='checkbox' class='form-control vat_product items_calc'checked onchange='calcProduc(this, "+'"_edit"'+")'><input type='hidden' class='vat_hidden' name='vat[]' value='"+item.vat+"'></td>"
						}else{
							html +="<td><input type='checkbox' class='form-control vat_product items_calc' onchange='calcProduc(this, "+'"_edit"'+")'><input type='hidden' class='vat_hidden' name='vat[]' value='"+item.vat+"'></td>"
						}

						html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales total_product' value='"+number_format(item.total, 2)+"'  name='total[]' readonly required></td>"
						html +="<td><span onclick='deleteProduct(this, "+'"_edit"'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
					html += "</tr>"


					$(table+" tbody").append(html)
					$("#price_edit_"+item.id).val(item.price)

				});








			}




			function deleteProduct(element, edit = ''){
				var tr = $(element).parent("td").parent("tr").remove()

				calcSubTotal(".price_product", edit)
				calcTotalVat(".vat_product", edit)
				calTotal(".total_product", edit)
			}








			function getClients(select, select_default = false){

				$.ajax({
					url: ''+document.getElementById('ruta').value+'/api/clients',
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
							text : "- Seleccione"
						}));

						$.each(data, function(i, item){

							if(data.status == 1){

							}
							$(select).append($('<option>',
							{
								value: item.id,
								text : item.name,
								selected : select_default == item.id ? true : false

							}));
						});


						$(select).select2({
							width : "100%",
							sorter: function(data) {
								/* Sort data using lowercase comparison */
								return data.sort(function (a, b) {
									a = a.text.toLowerCase();
									b = b.text.toLowerCase();
									if (a > b) {
										return 1;
									} else if (a < b) {
										return -1;
									}
									return 0;
								});
							}
						});

					}

				});
			}









			function calcProduc(element, edit = ''){

				var price = inNum($(element).parent("td").parent("tr").children("td").find(".price_product").val())
				var qty   = inNum($(element).parent("td").parent("tr").children("td").find(".qty_product").val())
				var vat   = $(element).parent("td").parent("tr").children("td").find(".vat_product")

				var existence        = $(element).parent("td").parent("tr").children("td").find(".existence")
				var existence_hidden = $(element).parent("td").parent("tr").children("td").find(".existence_hidden")


				if(edit != ''){
					var qty_hidden   = inNum($(element).parent("td").parent("tr").children("td").find(".qty_product_hidden").val())
					existence.val((existence_hidden.val() - qty) + qty_hidden)
				}else{

					existence.val(existence_hidden.val() - qty)

				}


				let total
				if(vat.is(':checked')){
					total = ((price * qty) * 1.19)
					$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(1)

				}else{
					total = (price * qty)
					$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(0)
				}


				$(element).parent("td").parent("tr").children("td").find(".total_product").val(number_format(total, 2))

				calcSubTotal(".price_product", edit)
				calcTotalVat(".vat_product", edit)
				calTotal(".total_product", edit)


			}




			function calcSubTotal(fields, edit = ''){
				let subtotal = 0
				$.map($(fields), function (item, key) {
					const qty   = $(item).parent("td").parent("tr").children("td").find(".qty_product").val()
					const total = inNum($(item).val()) * qty
					subtotal    = parseFloat(subtotal) + parseFloat(total)
				});

				var discount_field  = $(`#apply_discount${edit}`)

				let discount_ammount
				if(discount_field.is(':checked')){

					//console.log("SI Descuento")
					discount_ammount =  subtotal * 0.10
					//subtotal = subtotal - discount_ammount
					//$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(1)
				}else{
				//	console.log("NO Descuento")
					discount_ammount = 0
					//$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(0)
				}

				var discount_field2  = $(`#apply_discount2${edit}`)

				let discount_ammount2
				if(discount_field2.is(':checked')){

					//console.log("SI Descuento")
					discount_ammount2 =  subtotal * 0.15
					//subtotal = subtotal - discount_ammount2
					//$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(1)
				}else{
				//	console.log("NO Descuento")
					discount_ammount2 = 0
					//$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(0)
				}


				$(`#discount_total${edit}`).val((parseFloat(discount_ammount) + parseFloat(discount_ammount2)))
				$(`#discount_total_text${edit}`).text(`$ ${number_format((parseFloat(discount_ammount)  + parseFloat(discount_ammount2)), 2)}`)

				$(`#subtotal_text${edit}`).text(`$ ${number_format(subtotal, 2)}`)
				$(`#subtotal${edit}`).val(subtotal)



				let sub_total_with_discount = subtotal - (parseFloat(discount_ammount) + parseFloat(discount_ammount2))

				/*
					const percentage_rte_fuete = inNum($(`#rte_fuente${edit}`).val())
					const rte_fuete            = (sub_total_with_discount / 100) * percentage_rte_fuete

					console.log(rte_fuete)

					sub_total_with_discount    = ((sub_total_with_discount - rte_fuete))
				*/

				$(`#subtotal_with_discount${edit}`).val(sub_total_with_discount)

			}



			function calcTotalVat(fields,  edit = ''){
				let totalVat  = 0
				$.map($(fields), function (item, key) {

					if($(item).is(':checked')){
						const price = inNum($(item).parent("td").parent("tr").children("td").find(".price_product").val())
						const qty   = $(item).parent("td").parent("tr").children("td").find(".qty_product").val()

						const vat = ((price * qty) * 0.19)

						totalVat = totalVat + vat
					}

				});



				//const totalVat2 = (($(`#subtotal_with_discount${edit}`).val()) * 0.19)


				console.log(($(`#subtotal_with_discount${edit}`).val()) * 0.19, "IVA")
				$(`#vat_total_text${edit}`).text(`$ ${number_format(totalVat, 2)}`)
				$(`#vat_total${edit}`).val(totalVat)
			}


			function calTotal(fields, edit = ''){

				let total_invoice = 0


				$.map($(fields), function (item, key) {

					if($(item).val() != ""){
						total_invoice = parseFloat(total_invoice) + parseFloat(inNum($(item).val()))
					}

				});


				const discount  = inNum($(`#discount_total${edit}`).val())

				const percentage_rte_fuete = inNum($(`#rte_fuente${edit}`).val())
				const rte_fuete            = ($(`#subtotal_with_discount${edit}`).val() / 100) * percentage_rte_fuete
				const vat                  = parseFloat($(`#vat_total${edit}`).val())

				total_invoice = parseFloat($(`#subtotal_with_discount${edit}`).val())
			//	console.log(total_invoice)
				total_invoice = total_invoice  + vat - rte_fuete

				$(`#rte_fuente_text${edit}`).text(`$ ${number_format(rte_fuete, 2)}`)
				$(`#rte_fuente_total${edit}`).val(rte_fuete)

				$(`#total_invoice_text${edit}`).text(`$ ${number_format(total_invoice, 2)}`)
				$(`#total_invoice${edit}`).val(total_invoice)

				$(`#balance${edit}`).val(total_invoice)

				if(edit != ""){
					CalcBalanceEdit()
				}else{
					CalcBalance()
				}

			}



			$("#apply_discount").change(function (e) {
				calcSubTotal(".price_product")
				calcTotalVat(".vat_product")
				calTotal(".total_product")
			});


			$("#apply_discount2").change(function (e) {
				calcSubTotal(".price_product")
				calcTotalVat(".vat_product")
				calTotal(".total_product")
			});





			$("#apply_discount_edit").change(function (e) {
				calcSubTotal(".price_product", '_edit')
				calcTotalVat(".vat_product", '_edit')
				calTotal(".total_product", '_edit')
			});


			$("#apply_discount_edit2").change(function (e) {
				calcSubTotal(".price_product", '_edit')
				calcTotalVat(".vat_product", '_edit')
				calTotal(".total_product", '_edit')
			});


			$(".discount").keyup(function (e) {
				calcSubTotal(".price_product")
				calcTotalVat(".vat_product")
				calTotal(".total_product")
			});


			$(".discount_edit").keyup(function (e) {
				calcSubTotal(".price_product", '_edit')
				calcTotalVat(".vat_product", '_edit')
				calTotal(".total_product", '_edit')
			});




			function GetComercialPremisesNew(select){

				var url=document.getElementById('ruta').value;
				$.ajax({
				  url:''+url+'/api/comercial/premises',
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
							value: "null",
							text : "Seleccione"
						}));
						$.each(data, function(i, item){

							$(select).append($('<option>',
							{
							value: item.id,
							text : item.name
							}));

						});


						$(select).val(id_commercial_premises).trigger("change")
						if(id_rol != 6){
							$(select).attr("readonly", "readonly")
						}else{
							$(select).removeAttr("readonly")
						}

				  }
				});
			}





			function ShowPays(data){

				$("#content_pay_edit").html("")


				$.map(data, function (item, key) {
					var html = ""
					html += "<br>"
					html += "<div class='row'>"
							html += "<div class='col-md-6'>"
								html += "<input type='text' class='form-control method_pay_edit' required name='method_pay[]' placeholder = 'Metodo de Pago' value='"+item.method+"'>"
							html += "</div>"
							html += "<div class='col-md-6'>"
								html += "<input type='text' class='form-control amount_pay_edit' required name='amount_pay[]' placeholder = 'Monto' value='"+item.amount+"' onkeyup='CalcBalanceEdit()'>"
							html += "</div>"
					html += "</div>"

					$("#content_pay_edit").append(html)
				});

				CalcBalanceEdit()
			}





			$("#add_pay").click(function (e) {
				var html = ""
				html += "<br>"
				html += "<div class='row'>"
						html += "<div class='col-md-6'>"
							html += "<input type='text' class='form-control method_pay' required name='method_pay[]' placeholder = 'Metodo de Pago'>"
						html += "</div>"
						html += "<div class='col-md-6'>"
							html += "<input type='text' class='form-control amount_pay' required name='amount_pay[]' placeholder = 'Monto' onkeyup='CalcBalance()'>"
						html += "</div>"
				html += "</div>"

				$("#content_pay").append(html)

			});


			function CalcBalance(){
				var total = 0
				$(".amount_pay").map(function(obj, item){
					total = total + inNum($(item).val())
				});

				const balance = $("#total_invoice").val() - total
				$("#balance").val(balance)

			}





			$("#add_pay_edit").click(function (e) {
				var html = ""
				html += "<br>"
				html += "<div class='row'>"
						html += "<div class='col-md-6'>"
							html += "<input type='text' class='form-control method_pay_edit' required name='method_pay[]' placeholder = 'Metodo de Pago'>"
						html += "</div>"
						html += "<div class='col-md-6'>"
							html += "<input type='text' class='form-control amount_pay_edit' required name='amount_pay[]' placeholder = 'Monto' onkeyup='CalcBalanceEdit()'>"
						html += "</div>"
				html += "</div>"

				$("#content_pay_edit").append(html)

			});






			function CalcBalanceEdit(){
				var total = 0
				$(".amount_pay_edit").map(function(obj, item){
					total = total + inNum($(item).val())
				});

				const balance = $("#total_invoice_edit").val() - total
				$("#balance_edit").val(balance)

			}





		</script>




	@endsection


