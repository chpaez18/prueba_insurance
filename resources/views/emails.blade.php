
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_email">Registrar Email</button>
            </div>
            <br>
            <br>
            <br>
            <table id="emails_table" class="display" style="width:100%">
                
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_email" tabindex="-1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_label">Registrar Email</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="asunto" class=" col-form-label text-md-left">{{ __('Asunto') }}</label>
                                        <input id="asunto" type="text" class="form-control @error('asunto') is-invalid @enderror" name="asunto" value="{{ old('asunto') }}" required autocomplete="asunto">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="destinatario" class=" col-form-label text-md-left">{{ __('Destinatario') }}</label>
                                        <input id="destinatario" type="email" class="form-control @error('destinatario') is-invalid @enderror" name="destinatario" value="{{ old('destinatario') }}" required autocomplete="destinatario">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="mensaje" class=" col-form-label text-md-left">{{ __('Mensaje') }}</label>
                                        <textarea id="mensaje" class="form-control @error('mensaje') is-invalid @enderror" name="mensaje" value="{{ old('mensaje') }}" required autocomplete="mensaje">
                                        </textarea>
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
          <button id="btn_guardar_email" type="button" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
</div>





<script>
    var emails_table;
    //creamos el datatable con la informacion de los usuarios registrados
    emails_table = $('#emails_table').DataTable({
        searching       : true,
        retrieve        : true,
        rowReorder      : true,
        responsive      : true,
        ajax           : {
            "type": "GET",
            "url"  : "/prueba_insurance/public/getemails",
        },
        'columns': [
            {'title': 'Asunto', data:'asunto'},
            {'title': 'Destinatario', data:"destinatario"},
            {'title': 'Mensaje', data:"mensaje"},
            {'title': 'status', data:function(row){
                if(row.status == 0){
                    return `<span class="badge badge-info">Pendiente de Enviar</span>`
                }else if(row.status == 1){
                    return `<span class="badge badge-primary">Enviado</span>`
                }
            }},
        ],
        'columnDefs': [
            {'targets': [3], 'width': "15%"},
            {'targets': [0,1,2,3], 'orderable': true },
            {className: "text-center", "targets":[0,1,2,3]}
        ],
    });


/********************************************************
*                 Registrar Email                        *
********************************************************/

$("#btn_guardar_email").on('click', function(){
    let asunto = $("#asunto").val()
    let destinatario = $("#destinatario").val()
    let mensaje = $("#mensaje").val()
    if(validateEmail(destinatario)){
        $.ajax({
            type: "POST",
            url: "/prueba_insurance/public/storemail",
            datatype: "json",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                    "asunto" : asunto,
                    "destinatario" : destinatario,
                    "mensaje" :mensaje,
            },
            success: function(response){
                alert("Email Registrado")
                $("#asunto").val('')
                $("#destinatario").val('')
                $("#mensaje").val('')
                $("#btn_close_modal").click();
                emails_table.ajax.reload();
                
                
            },  
            error: function(xhr) {
                console.log('Error Message: ' + xhr.responseJSON.message +' - Process: '+ xhr.responseJSON.proc);
            }
        });
    }else{
        alert("Porfavor introduzca un email valido")
    }
    

})

// END: Registrar Email


function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}
</script>

@endsection
