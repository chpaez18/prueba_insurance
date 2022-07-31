@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table id="users_table" class="display" style="width:100%">
                
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_modificar" tabindex="-1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_label">Modificar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="formulario_registro" method="POST" >
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="form-group row">
                                    <label for="info_name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="id_user" type="hidden">
                                        <input id="info_name" type="text" class="form-control @error('info_name') is-invalid @enderror" name="info_name" value="{{ old('info_name') }}" required autocomplete="name" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="info_telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="info_telefono" type="text" class="form-control @error('info_telefono') is-invalid @enderror" maxlength="10" name="info_telefono" value="{{ old('info_telefono') }}"  autofocus>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="info_cedula" class="col-md-4 col-form-label text-md-right">{{ __('Cédula') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="info_cedula" disabled type="text" class="form-control @error('cedula') is-invalid @enderror" maxlength="11" name="cedula" value="{{ old('cedula') }}"  autofocus>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="info_fecha_nacimiento" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="info_fecha_nacimiento" type="text" class="form-control @error('info_fecha_nacimiento') is-invalid @enderror"  name="info_fecha_nacimiento" value="{{ old('info_fecha_nacimiento') }}"  autofocus>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="info_pais" class="col-md-4 col-form-label text-md-right">{{ __('Ciudad') }}</label>
        
                                    <div class="col-md-3">
                                        <select id="info_pais" class="form-control" required>
                                            <option value="0">Seleccione</option>
                                                @foreach ($paises as $pais)
                                                    <option value="{{ $pais->codigo }}">{{ $pais->pais }}</option>
                                                @endforeach
                        
                                        </select>
                                    </div>
        
                                    <div class="col-md-3">
                                        <select id="info_id_ciudad" class="form-control" name="info_id_ciudad" required>
                                            <option value="0">Select</option>
        
                                        </select>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="info_email" disabled type="email" class="form-control @error('email') is-invalid @enderror" name="info_email" value="{{ old('info_email') }}" required autocomplete="email">
        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button id="btn_close_modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btn_guardar" type="button" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>



<script>
    var nombre_ciudad = '';
    var user_table 
    //creamos el datatable con la informacion de los usuarios registrados
    user_table = $('#users_table').DataTable({
        searching       : true,
        retrieve        : true,
        rowReorder      : true,
        responsive      : true,
        ajax           : {
            "type": "GET",
            "url"  : "/prueba_insurance/public/getusers",
        },
        'columns': [
            {'title': 'Nombre', data:'name'},
            {'title': 'Teléfono', data:"telefono"},
            {'title': 'Cédula', data:"cedula"},
            {'title': 'Fecha de Nacimiento', data:function(val){
                var now = moment(val.fecha_nacimiento);
                return now.format("DD-MM-Y");
            }},
            {'title': 'Ciudad', data:"ciudad"},
            {'title': 'Correo', data:"email"},
            {'title': 'Acciones', data:function(row){
                return `
                    <button data-user-id="${row.id}" id="edit-user" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_modificar">Modificar</button>
                    <button data-user-id="${row.id}" id="delete-user" type="button" class="btn btn-danger" data-toggle="modal">Eliminar</button>
                `
            }},
        ],
        'columnDefs': [
            {'targets': [4], 'width': "15%"},
            {'targets': [0,1,2,3,4,5], 'orderable': true },
            {className: "text-center", "targets":[0,1,2,3,4,5]}
        ],
    });

    //inicializamos el datepicker en el formulario de edición
    $('#info_fecha_nacimiento').datepicker({
        changeMonth: true,
        changeYear: true
    },"option", "dateFormat",'dd/mm/yy');

$(document).ready(function(){

/********************************************************
*      Funcionamiento de Botones Modificar/Eliminar      *
********************************************************/

    //al hacer click en el boton modificar, obtenemos la informacion del usuario en especifico
    $(document).on('click', '#edit-user', function(){
        let user_id = $(this).attr("data-user-id")
        $.ajax({
            async: true,
            type: "GET",
            url: "/prueba_insurance/public/getuser/"+`${user_id}`,
            datatype: "json",
            success: function(response){ 
                let user = response.data
                var now = moment(user.fecha_nacimiento);
                $("#id_user").val(user.id)
                $("#info_name").val(user.name)
                $("#info_telefono").val(user.telefono)
                $("#info_cedula").val(user.cedula)
                $("#info_pais").val(user.ciudad.codigo_pais)
                nombre_ciudad =  user.ciudad.ciudad
                $("#info_pais").change()
                $("#info_fecha_nacimiento").val(now.format("MM/DD/Y"))
                $("#info_email").val(user.email)
                
            },  
            error: function(xhr) {
                console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
            }
        });
    })

    //eliminamos un usuario en especifico
    $(document).on('click', '#delete-user', function(){
        let user_id = $(this).attr("data-user-id")
        $.ajax({
            async: true,
            type: "POST",
            url: "/prueba_insurance/public/deleteuser/"+`${user_id}`,
            datatype: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){ 
                alert("Usuario Eliminado")
                user_table.ajax.reload();
                
            },  
            error: function(xhr) {
                console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
            }
        });
    })

// END: Funcionamiento de Botones Modificar/Eliminar


/********************************************************
*      Funcionamiento Boton guardar cambios              *
********************************************************/

    $(document).on('click', "#btn_guardar", function(){
        let user_id = $("#id_user").val()
        $.ajax({
            type: "PUT",
              url: "/prueba_insurance/public/updateuser/"+`${user_id}`,
              datatype: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
              data: {
                    "name" : $("#info_name").val(),
                    "telefono" : $("#info_telefono").val(),
                    "ciudad" : $("#info_id_ciudad").val(),
                    "fecha_nacimiento" : $("#info_fecha_nacimiento").val(),
              },
            success: function(response){
                alert("Usuario Actualizado")
                $("#info_name").val('')
                $("#info_telefono").val('')
                $("#info_id_ciudad").val('')
                $("#info_fecha_nacimiento").val('')
                $("#btn_close_modal").click();
                user_table.ajax.reload();
                
            },  
            error: function(xhr) {
                console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
            }
        });

    })

// END: Funcionamiento Boton guardar cambios
     

    //escuchamos el evento change en el campo pais
    $("#info_pais").on('change', function () {
        let id_pais = $(this).val()

        //obtenemos las ciudades relacionadas con el pais seleccionado
        setTimeout(function() { 
            getCitiesByCountry(id_pais);
        }, 500);
    });


})


/********************************************************
 *               Function getCitiesByCountry              *
 ********************************************************/

 function getCitiesByCountry(id_pais){
     $.ajax({
         async: true,
         type: "GET",
         url: "/prueba_insurance/public/api/getcitiesbycountry/"+`${id_pais}`,
         datatype: "json",
         success: function(response){ 
             $('#info_id_ciudad').empty();
             $('#info_id_ciudad').append(`<option value="0">Select</option>`);

                 let cities = response.data;
                 
                 cities.forEach(function(city) {

                    if(nombre_ciudad == city.ciudad){
                        $('#info_id_ciudad').append(`<option selected value="${city.id}">${city.ciudad}</option>`);
                    }else{
                        $('#info_id_ciudad').append(`<option value="${city.id}">${city.ciudad}</option>`);
                    }
                     
                 });
                 
             
         },  
         error: function(xhr) {
             console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
         }
     });
 } // END: Function getCitiesByCountry



</script>

@endsection
