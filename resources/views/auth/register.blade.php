@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id="formulario_registro" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

                            <div class="col-md-6">
                                <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" maxlength="10" name="telefono" value="{{ old('telefono') }}"  autofocus>

                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cedula" class="col-md-4 col-form-label text-md-right">{{ __('Cédula') }}</label>

                            <div class="col-md-6">
                                <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" maxlength="11" name="cedula" value="{{ old('cedula') }}"  autofocus>

                                @error('cedula')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha_nacimiento" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento') }}</label>

                            <div class="col-md-6">
                                <input id="fecha_nacimiento" type="text" class="form-control @error('fecha_nacimiento') is-invalid @enderror"  name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"  autofocus>

                                @error('fecha_nacimiento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pais" class="col-md-4 col-form-label text-md-right">{{ __('Ciudad') }}</label>

                            <div class="col-md-3">
                                <select id="pais" class="form-control" required>
                                    <option value="0">Seleccione</option>
                                        @foreach ($paises as $pais)
                                            <option value="{{ $pais->codigo }}">{{ $pais->pais }}</option>
                                        @endforeach
                
                                </select>

                                @error('pais')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <select id="id_ciudad" class="form-control" name="id_ciudad" required>
                                    <option value="0">Select</option>

                                </select>
                                @error('id_ciudad')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-0 form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//inicializamos el datepicker
$('#fecha_nacimiento').datepicker({
    changeMonth: true,
    changeYear: true
}, "option", "showAnim", 'fadeIn' );

$(document).ready(function(){

    
  

 //escuchamos el evento change en el campo pais
 $("#pais").on('change', function () {
     let id_pais = $(this).val()

    //obtenemos las ciudades relacionadas con el pais seleccionado
    setTimeout(function() { 
        getCitiesByCountry(id_pais);
    }, 500);
     
 });

 $("#formulario_registro").submit(function(e){
    //validamos que se haya seleccionado una ciudad
    if($('#id_ciudad').val() == 0){
        e.preventDefault()
        alert("Porfavor seleccione una ciudad")
    }
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
             $('#id_ciudad').empty();
             $('#id_ciudad').append(`<option value="0">Select</option>`);

                 let cities = response.data;
                 
                 cities.forEach(function(city) {
                     $('#id_ciudad').append(`<option value="${city.id}">${city.ciudad}</option>`);
                 });
                 
             
         },  
         error: function(xhr) {
             console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
         }
     });
 } // END: Function getCitiesByCountry


})
</script>
@endsection
