<?PHP
$fecha = $op->ToDay();
$hora = $op->Time();
?>

<script>
    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var map;
    var markers = [];
    var marcadores_bd=[];
    var mapa = null; //VARIABLE GENERAL PARA EL MAPA

    function openWebCam(){
        openWebcam();//document.write( webcam.get_html(320, 240) );
        webcam.set_api_url( 'modulo/empleado/uploadEmp.php' );
        webcam.set_hook( 'onComplete', 'my_callback_function');
    }
    function my_callback_function(response) {
        //alert("Success! PHP returned: " + response);
        msg = $.parseJSON(response);
        //alert(msg.filename);
        //modificado
        recargaImg(msg.filename, 'empleado');
    }

    function initMap(){
        /* GOOGLE MAPS */
        var formulario = $('#formNew');
        //COODENADAS INICIALES -16.5207007,-68.1615534
        //VARIABLE PARA EL PUNTO INICIAL
        var punto = new google.maps.LatLng(-16.499299167397574, -68.1646728515625);
        //VARIABLE PARA CONFIGURACION INICIAL
        var config = {
            zoom:10,
            center:punto,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        mapa = new google.maps.Map( $("#mapa")[0], config );

        google.maps.event.addListener(mapa, "click", function(event){
            //OBTENER COORDENADAS POR SEPARADO
            var coordenadas = event.latLng.toString();
            coordenadas = coordenadas.replace("(", "");
            coordenadas = coordenadas.replace(")", "");

            var lista = coordenadas.split(",");
            //alert(lista[0]+"---"+lista[1])
            var direccion = new google.maps.LatLng(lista[0], lista[1]);
            //variable marcador
            var marcador = new google.maps.Marker({
                //titulo: prompt("Titulo del marcador"),
                position: direccion,
                map: mapa, //ENQUE MAPA SE UBICARA EL MARCADOR
                animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
                draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
                //title:"Hello World!"
            });

            //PASAR LAS COORDENADAS AL FORMULARIO
            formulario.find("input[name='cx']").val(lista[0]);
            formulario.find("input[name='cy']").val(lista[1]);
            //UBICAR EL FOCO EN EL CAMPO TITULO
            formulario.find("input[name='addres']").focus();

            //UBICAR EL MARCADOR EN EL MAPA
            //setMapOnAll(null);
            markers.push(marcador);

            //AGREGAR EVENTO CLICK AL MARCADOR
            google.maps.event.addListener(marcador, "click", function(){
                //alert(marcador.titulo);
            });
            deleteMarkers(markers);
            marcador.setMap(mapa);
        });

    }

    //FUNCIONES PARA EL GOOGLE MAPS

    function deleteMarkers(lista){
        for(i in lista){
            lista[i].setMap(null);
        }
    }

    function geocodeResult(results, status) {
        // Verificamos el estatus
        if (status == 'OK') {
            // Si hay resultados encontrados, centramos y repintamos el mapa
            // esto para eliminar cualquier pin antes puesto
            var mapOptions = {
                center: results[0].geometry.location,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            //mapa = new google.maps.Map($("#mapa").get(0), mapOptions);
            // fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
            mapa.fitBounds(results[0].geometry.viewport);
            // Dibujamos un marcador con la ubicación del primer resultado obtenido
            //var markerOptions = { position: results[0].geometry.location }
            var direccion = results[0].geometry.location;
            var coordenadas = direccion.toString();

            coordenadas = coordenadas.replace("(", "");
            coordenadas = coordenadas.replace(")", "");
            var lista = coordenadas.split(",");

            //var direccion = new google.maps.LatLng(lista[0], lista[1]);
            //PASAR LAS COORDENADAS AL FORMULARIO

            $('#formNew').find("input[name='cx']").val(lista[0]);
            $('#formNew').find("input[name='cy']").val(lista[1]);
            //$('#form').find("input[name='buscar']").val('');

            var marcador = new google.maps.Marker({
                position: direccion,
                zoom:15,
                map: mapa, //ENQUE MAPA SE UBICARA EL MARCADOR
                animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
                draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
            });
            markers.push(marcador);
            deleteMarkers(markers);
            marcador.setMap(mapa);
            //marker.setMap(mapa);

        } else {
            // En caso de no haber resultados o que haya ocurrido un error
            // lanzamos un mensaje con el error
            alert("El buscador no tuvo éxito debido a: " + status);
        }
    }

    // $(document).ready(function(e) {

    $('#dateNac').datetimepicker({
        locale: 'es',
        viewMode: 'years',
        format: 'YYYY-MM-DD'
    });

    // BUSCADOR
    $('#search').on('click', function() {
        // Obtenemos la dirección y la asignamos a una variable
        var address = $('#buscar').val();
        // Creamos el Objeto Geocoder
        var geocoder = new google.maps.Geocoder();
        // Hacemos la petición indicando la dirección e invocamos la función
        // geocodeResult enviando todo el resultado obtenido
        geocoder.geocode({ 'address': address}, geocodeResult);
    });

    /* uploadIfy */
    $('#file_upload').uploadify({
        'queueID'  		: 'some_file_queue',
        'swf'      		: 'uploadify/uploadify.swf',
        'uploader'		: 'uploadify/uploadify.php',
        'method'   		: 'post',
        'multi'   		: false,
        'auto'   			: false,
        'queueSizeLimit' 	: 1,
        'fileSizeLimit' 	: '100KB',
        'fileTypeDesc' 	: 'Imagen',
        'fileTypeExts' 	: '*.jpg',
        'removeCompleted' : false,
        'buttonText'		: 'Examinar...',
        height       		: 25,
        width        		: 100,
        'formData'      	: {
            'path' : 'empleado'
        },
        // ** Eventos **
        'onSelectOnce':function(event,data){
            $('#file_upload').uploadifySettings('scriptData',{'directorio':'a','CodeUser': '21'});
        },
        'onUploadComplete': function(file){
            idImg('empleado');
            //$('#cboxTitle').html('La foto ' + file.name + ' se subio correctamente, <br> ahora puede guardar el formulario.');

            setTimeout(function(){
                $( ".uploadShow" ).toggle(2000,function(){
                    $('#save, #close').removeAttr('disabled','disabled');
                    $('#subir').text("Subir Foto");
                    $('#file_upload').uploadify('cancel', '*');
                });
            },4000);
        }
    });
    /* Abrir y cerrar uploadIfy */
    $('#subir').click(
        function(){
            var $this = $(this);
            var op = $this.find('span').text();
            if( op == 'Subir Foto' ){
                $('#subir').find('span').text("Cancelar");
                $('#save, #close').attr('disabled','disabled');
            }else{
                $('#subir').find('span').text("Subir Foto");
                $('#save, #close').removeAttr('disabled','disabled');
                $('#file_upload').uploadify('cancel', '*');
            }
            $( ".uploadShow" ).toggle(1000);
        });

    $('#dataRegister').on('show.bs.modal', function() {
        //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
        initMap();
    });

    $('#dataRegister').on('hidden.bs.modal', function (e) {
        // do something...
        $('#formNew').get(0).reset();
        $('.uploadShow').css('display','none');
        //$('#file_upload').uploadify('cancel', '*');
        $('#save, #close').removeAttr('disabled','disabled');
        $('#subir').text("Subir Foto");
        $('#foto').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
    });
 // });

</script>

<form id="formNew" action="javascript:saveForm('formNew','empleado/save.php')" class="" autocomplete="off" >
<div class="modal fade bs-example-modal-lg" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Nuevo Empleado <span class="fecha">Fecha: <?=$fecha;?> <?=$hora;?></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="datos_ajax"></div>
                    </div>
                </div>
                <div class="row">
                    <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                    <input id="tabla" name="tabla" type="hidden" value="empleado">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name" class="sr-only">Nombre:</label>
                                <input id="name" name="name" type="text" placeholder="Nombre" class="form-control" data-validation="required" autocomplete="off" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="paterno" class="sr-only">Paterno:</label>
                                <input id="paterno" name="paterno" type="text" placeholder="Paterno" data-validation="required" class="form-control" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="materno" class="sr-only">Materno:</label>
                                <input id="materno" name="materno" type="text" placeholder="Materno" data-validation="required" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label for="ci" class="sr-only">N° C.I.:</label>
                                <input id="ci" name="ci" type="text" placeholder="N° C.I." class="form-control"
                                       data-validation="required number server"
                                       data-validation-url="modulo/empleado/validateCI.php"/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="dep" class="sr-only">Lugar Exp.:</label>
                                <select id="dep" name="dep" class="form-control" data-validation="required">
                                    <option value="" disabled selected hidden>Lugar Exp.</option>
                                    <option value="lp">La Paz</option>
                                    <option value="cbb">Cochabamba</option>
                                    <option value="sz">Santa Cruz</option>
                                    <option value="bn">Beni</option>
                                    <option value="tr">Tarija</option>
                                    <option value="pt">Potosi</option>
                                    <option value="or">Oruro</option>
                                    <option value="pd">Pando</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="dateNac" class="sr-only">Fecha de Nacimiento:</label>
                                <input id="dateNac" name="dateNac" type="text" placeholder="Fecha Nac." class="form-control" data-validation="date" data-validation-format="yyyy-mm-dd"/>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="fono" class="sr-only">Telefono:</label>
                                <input id="fono" name="fono" type="text" placeholder="Telefono" class="form-control" data-validation="number" data-validation-optional-if-answered="celular"/>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="celular" class="sr-only">Celular:</label>
                                <input id="celular" name="celular" type="text" placeholder="Celular" class="form-control" data-validation="number" data-validation-optional-if-answered="fono"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" align="center">
                        <div id="foto" class="form-group">
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="email" class="sr-only">Correo Electronico:</label>
                        <input id="email" name="email" type="text" placeholder="Correo Electronico" value="" class="form-control" data-validation="email"/>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="cargo" class="sr-only">Cargo:</label>
                        <select id="cargo" name="cargo" class="form-control" data-validation="required">
                            <option value="" disabled selected hidden>Cargo</option>
                            <option value="adm">Administrador</option>
                            <option value="alm">Almacen</option>
                            <option value="con">Contador</option>
                            <option value="pre">Preventista</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="codUser" class="sr-only">Usuario:</label>
                        <input id="codUser" name="codUser" type="text" placeholder="Usuario" class="form-control"
                               data-validation="required server"
                               data-validation-url="modulo/empleado/validateUser.php"/>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="password" class="sr-only">Contraseña:</label>
                        <input id="password" name="password" type="text" placeholder="Contraseña" value="" class="form-control" data-validation="required"/>
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" id="genera" class="btn btn-primary" onclick="generaPass('password');">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <span>Generar</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="addres" class="sr-only"></label>
                        <input id="addres" name="addres" type="text" placeholder="Direcci&oacute;n" class="form-control" data-validation="required"/>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="Nro" class="sr-only"></label>
                        <input id="Nro" name="Nro" type="text" placeholder="N° de domicilio" class="form-control" data-validation="required number"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5" align="center">
                        <div id="mapa" class="form-group"></div><!--End mapa-->
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-9 form-group">
                                <input id="buscar" name="buscar" type="text" placeholder="Buscar en Google Maps" value="" class="form-control" autocomplete="off"/>
                            </div>
                            <div class="col-md-3  form-group">
                                <button type="button" id="search" class="btn btn-primary" >
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input id="cx" name="cx" type="text" placeholder="Latitud" value="" readonly class="form-control" data-validation="required"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input id="cy" name="cy" type="text" placeholder="Longitud" value="" readonly class="form-control" data-validation="required"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="checkbox">
                                <label><input id="checksEmail" name="checksEmail" type="checkbox" checked/> Enviar datos por E-mail</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <button type="button"  class="btn btn-primary btn-sm" onclick="initMap();" >
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                    <span>Cargar Mapa</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="obser" class="sr-only"></label>
                        <p id="maxText"><span id="max-length-element">200</span> caracteres restantes</p>
                        <textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <button type="button" id="capturar" class="btn btn-primary" onclick="openWebCam()">
                            <i class="fa fa-camera" aria-hidden="true"></i>
                            <span>Capturar Foto</span>
                        </button>
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" id="subir" class="btn btn-primary" ">
                            <i class="fa fa-upload" aria-hidden="true"></i>
                            <span>Subir Foto</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="idealWrap uploadShow" style="display:none;">
                            <div id="some_file_queue"></div>
                            <div id="buttonFile">
                                <input type="file" name="file_upload" id="file_upload" />
                                <button type="button" id="upload" class="btn btn-success" onclick="$('#file_upload').uploadify('upload')">Subir Foto</button>
                            </div>
                            <div class="clearfix"></div>
                        </div><!--End idealWrap-->
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-close" aria-hidden="true"></i>
                    <span>Cancelar</span>
                </button>
                <button type="submit" id="save" class="btn btn-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <span>Agregar Empleado</span>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<div id="camera">
    <span class="tooltip"></span>
    <span class="camTop"></span>

    <div id="screen"></div>
    <div id="buttons">
        <div class="buttonPane">
            <a id="closeButton" onclick="closeWebcam()" class="btn btn-danger">Cerrar</a>
            <a id="shootButton" href="" class="btn btn-primary">Capturar!</a>
        </div>
        <div class="buttonPane" style="display: none">
            <a id="cancelButton" href="" class="btn btn-danger">Cancelar</a>
            <a id="uploadButton" href="" class="btn btn-primary">Subir!</a>
        </div>
    </div>

    <span class="settings"></span>
</div>