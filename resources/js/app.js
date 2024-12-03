import './bootstrap';
//import * as boostrap from 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import jQuery from 'jquery';
//import DataTable from 'datatables.net-dt';

window.$ = jQuery;

import { Calendar } from '@fullcalendar/core'
import interactionPlugin from '@fullcalendar/interaction'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGrid from '@fullcalendar/timegrid'
import list from '@fullcalendar/list'
import multiMonth from '@fullcalendar/multimonth'
import esLocale from '@fullcalendar/core/locales/es'

$(document).ready(function () {
    setTimeout(function () { $("#mensajeresult").hide("slow") }, 1000);
    if ($("#calendar").length > 0) {
        inicializaCalendario();
    }

    if ($("#total_pagar").length > 0) {
        actualizaTotalReservacion();
    }

    if($("#resultadoconsulta").length>0){
        getReport();
    }

    if($("#message").length>0){
        $("#message").fadeIn('');
        setTimeout(function(){$("#message").fadeOut('')},3000);
    }


    //$("#tableHoteles").DataTable();
});
window.inicializaCalendario = function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [
            interactionPlugin,
            dayGridPlugin,
            timeGrid,
            multiMonth,
            list,
        ],
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia',
            list: 'Agenda'
        },
        initialDate: new Date(),
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectMirror: true,
        locale: esLocale,
        select: function (arg) {
            var title = prompt('Event Title:');
            if (title) {
                calendar.addEvent({
                    title: title,
                    start: arg.start,
                    end: arg.end,
                    allDay: arg.allDay
                })
            }
            calendar.unselect()
        },
        eventClick: function (arg) {
            /*if (confirm('Are you sure you want to delete this event?')) {
                arg.event.remove()
            }*/
            $("#btnNuevaReservacion").click();
            getDataReservacion(arg.event.id);

        },
        editable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        events: 'reservaciones/all',
        //eventColor: 'orange'
    });

    calendar.setOption('locale', 'es');
    calendar.render();
}


//function para hoteles
/*
[{
                 title: 'All Day Event',
                 start: '2023-01-01'
             },
             {
                 title: 'Long Event',
                 start: '2023-01-07',
                 end: '2023-01-10'
             },
         ]


window.hotelSubmit = function(){
    var form = $("#formHotel");
    swal({
        title: "Are you sure you want to delete this record?",
        text: "If you delete this, it will be gone forever.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancel","Yes!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((willDelete) => {
        if (willDelete) {
            form.submit();
        }
    });
    //alert($("#mensajeresult").length);
}*/

window.deleteHotel = function (hotel, nombre) {
    swal({
        title: "Está seguro de querer eliminar el registro del hotel: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este hotel se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/hoteles/destroy",
                data: {
                    id: hotel,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnEliminahotel" + hotel).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#hotel" + hotel).hide('slow');
                    $("#hotel" + hotel).remove();
                    window.location.replace("/hoteles/listado");
                }

            }).fail(function (data) {

            })

        }
    });
}

window.deleteUsuario = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar al usuario: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este usuario se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/usuarios/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnEliminaUsuario" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#usuario" + id).hide('slow');
                    $("#usuario" + id).remove();
                    window.location.replace("/usuarios/list");
                }

            }).fail(function (data) {

            })

        }
    });
}

window.updateStatusUsuario = function (id, event) {
    var status = $("#usuariostatus" + id).prop("checked") ? 1 : 0;

    $.ajax({
        type: 'POST',
        url: "/usuarios/updatestatus",
        data: {
            id: id,
            _token: $("input[name=_token]").val(),
            status: status

        },
        beforeSend: function () {

        }
    }).done(function (response) {
        if (response.result == "error") {
            $("#usuariostatus" + id).prop("checked", status == '0' ? true : false);
        }
    }).fail(function (data) {

    })
}

//Funciones para niveles

window.addNivel = function () {
    var nombre = $("#nombre").val().trim();
    var id = $("#id").val();
    if (nombre.length > 0) {
        $.ajax({
            type: 'POST',
            url: "/niveles/add",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                nombre: nombre

            },
            beforeSend: function () {
                $("#nombre").removeClass("is-invalid");
                $("#btnAlmacenaNivel").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaNivel").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/niveles");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar el nivel, intente más tarde." + response.result,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaNivel").html('Almacenar Nivel');
                $("#btnAlmacenaNivel").prop("disabled", false);
            }
        }).fail(function (data) {

        })

    } else {
        $("#nombre").addClass("is-invalid");
    }
}

window.setDataNivel = function (id, nombre) {
    clearDataNivel();
    $("#id").val(id);
    $("#nombre").val(nombre);
}

window.clearDataNivel = function () {
    $("#id").val("");
    $("#nombre").val("");
    $("#nombre").removeClass("is-invalid");

}

window.deleteNivel = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar el nivel: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este nivel se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/niveles/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteNivel" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#nivel" + id).hide('slow');
                    $("#nivel" + id).remove();
                    window.location.replace("/niveles");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Categorias
window.addCategoria = function () {
    var nombre = $("#nombre").val().trim();
    var id = $("#id").val();
    if (nombre.length > 0) {
        $.ajax({
            type: 'POST',
            url: "/categorias/add",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                nombre: nombre

            },
            beforeSend: function () {
                $("#nombre").removeClass("is-invalid");
                $("#btnAlmacenaCategoria").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaCategoria").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/categorias");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar la categoría, intente más tarde." + response.result,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaCategoria").html('Almacenar Categoria');
                $("#btnAlmacenaCategoria").prop("disabled", false);
            }
        }).fail(function (data) {

        })

    } else {
        $("#nombre").addClass("is-invalid");
    }
}

window.setDataCategoria = function (id, nombre) {
    clearDataCategoria();
    $("#id").val(id);
    $("#nombre").val(nombre);
}

window.clearDataCategoria = function () {
    $("#id").val("");
    $("#nombre").val("");
    $("#nombre").removeClass("is-invalid");

}

window.deleteCategoria = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar la categoría: " + nombre + "?",
        text: "Si se elimina, los registros asociados a esta categoría se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/categorias/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteCategoria" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#categoria" + id).hide('slow');
                    $("#categoria" + id).remove();
                    window.location.replace("/categorias");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Habitaciones
window.validaHabitacion = function () {
    var valid = true;
    var inputs = [
        "nombre",
        "precio"
    ];
    var selects = [
        "niveles_id",
        "categorias_id"
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    return valid;
}

window.addHabitacion = function () {
    var nombre = $("#nombre").val().trim();
    var precio = $("#precio").val().trim();
    var tarifa = $("#tarifa").val().trim();
    var detalles = $("#detalles").val().trim();
    var niveles_id = $("#niveles_id").val();
    var categorias_id = $("#categorias_id").val();
    var id = $("#id").val();
    if (validaHabitacion()) {
        $.ajax({
            type: 'POST',
            url: "/habitaciones/store",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                nombre: nombre,
                precio: precio,
                tarifa: tarifa,
                detalles: detalles,
                niveles_id: niveles_id,
                categorias_id: categorias_id
            },
            beforeSend: function () {
                $("#btnAlmacenaHabitacion").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaHabitacion").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/habitaciones");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar la habitación, intente más tarde." + response.result,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaHabitacion").html('Almacenar Habitación');
                $("#btnAlmacenaHabitacion").prop("disabled", false);
            }
        }).fail(function (data) {

        })
    }


}

window.setDataHabitacion = function (id) {
    clearDataHabitacion();
    var nombre = $("#habitacion" + id).find("td").eq(2).html().trim();
    var niveles_id = $("#habitacion" + id).find("td").eq(3).html().trim();
    var categorias_id = $("#habitacion" + id).find("td").eq(5).html().trim();
    var precio = $("#habitacion" + id).find("td").eq(7).html().trim();
    var tarifa = $("#habitacion" + id).find("td").eq(8).html().trim();
    var detalles = $("#habitacion" + id).find("td").eq(9).html().trim();
    $("#id").val(id);
    $("#nombre").val(nombre);
    $("#niveles_id").val(niveles_id);
    $("#categorias_id").val(categorias_id);
    $("#precio").val(precio);
    $("#tarifa").val(tarifa);
    $("#detalles").val(detalles);
}

window.clearDataHabitacion = function () {
    $("#id").val("");
    $("#nombre").val("");
    $("#precio").val("");
    $("#tarifa").val("");
    $("#detalles").val("");
    $("#niveles_id").val("");
    $("#categorias_id").val("");
    $("#nombre").removeClass("is-invalid");
    $("#precio").removeClass("is-invalid");
    $("#niveles_id").removeClass("is-invalid");
    $("#categorias_id").removeClass("is-invalid");

}

window.deleteHabitacion = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar la habitación: " + nombre + "?",
        text: "Si se elimina, los registros asociados a esta habitación se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/habitaciones/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteHabitación" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#habitacion" + id).hide('slow');
                    $("#habitacion" + id).remove();
                    window.location.replace("/habitaciones");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Productos

window.validaProducto = function () {
    var valid = true;
    var inputs = [
        "nombre",
        "precio_unitario"
    ];
    var selects = [
        "tipo",
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    return valid;
}

window.addProducto = function () {
    var nombre = $("#nombre").val().trim();
    var precio_unitario = $("#precio_unitario").val().trim();
    var tipo = $("#tipo").val().trim();
    var id = $("#id").val();
    if (validaProducto()) {
        $.ajax({
            type: 'POST',
            url: "/productos/store",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                nombre: nombre,
                precio_unitario: precio_unitario,
                tipo: tipo
            },
            beforeSend: function () {
                $("#btnAlmacenaProducto").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaProducto").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/productos");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar el producto, intente más tarde." + response.result,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaProducto").html('Almacenar Habitación');
                $("#btnAlmacenaProducto").prop("disabled", false);
            }
        }).fail(function (data) {

        })
    }


}

window.setDataProducto = function (id) {
    clearDataProducto();
    var nombre = $("#producto" + id).find("td").eq(2).html().trim();
    var tipo = $("#producto" + id).find("td").eq(3).html().trim();
    var precio_unitario = $("#producto" + id).find("td").eq(4).html().trim();
    $("#id").val(id);
    $("#nombre").val(nombre);
    $("#tipo").val(tipo);
    $("#precio_unitario").val(precio_unitario);
}

window.clearDataProducto = function () {
    $("#id").val("");
    $("#nombre").val("");
    $("#tipo").val("");
    $("#precio_unitario").val("");
    $("#nombre").removeClass("is-invalid");
    $("#tipo").removeClass("is-invalid");
    $("#precio_unitario").removeClass("is-invalid");

}

window.deleteProducto = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar el producto: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este producto se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/productos/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteProducto" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#producto" + id).hide('slow');
                    $("#producto" + id).remove();
                    window.location.replace("/productos");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Ventas
window.getProductosByNombre = function () {

    if ($("#busca_producto").val().length > 1) {
        var busca = $("#busca_producto").val();
        $.ajax({
            type: 'GET',
            url: "/productos/" + busca,
            data: {
                nombre: busca,
            },
            beforeSend: function () {
                $("#procesando").html('<svg aria-hidden="true" class="w-3 h-3 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
            }
        }).done(function (response) {
            $("#procesando").html('');
            $("#productosList").html(response);
            $("#productosList").show("fast");
        }).fail(function (data) {
        })
    } else {
        $("#productosList").hide("slow");
        $("#productosList").html('');
    }
}

window.getRowProducto = function (id) {
    $("#busca_producto").val('');
    $.ajax({
        type: 'GET',
        url: "/producto/" + id,
        data: {
            id: id,
        },
        beforeSend: function () {
            $("#procesando").html('<svg aria-hidden="true" class="w-3 h-3 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
        }
    }).done(function (response) {
        $("#procesando").html('');
        //$("#productosTable").append(response);
        $("#productosList").hide("fast");
        $("#productosList").html("");
        if (response.result == "ok") {
            var id = response.producto.id;
            var nombre = response.producto.nombre;
            var tipo = response.producto.tipo;
            var precio_unitario = response.producto.precio_unitario;
            if ($("#producto" + id).length > 0) {
                var cantidad = parseFloat($("#producto" + id).find(".cantidad-p").eq(0).val());
                cantidad += 1;
                cantidad = $("#producto" + id).find(".cantidad-p").eq(0).val(cantidad);
            } else {
                var row = '<tr id="producto' + id + '"' +
                    'class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">' +
                    '<td class="w-4 p-4 hidden">' +
                    '<div class="flex items-center">' +
                    '<input id="checkbox-table-search-1" type="checkbox"' +
                    'class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">' +
                    '<label for="checkbox-table-search-1" class="sr-only">checkbox</label>' +
                    '</div>' +
                    '</td>' +
                    '<td scope="row"' +
                    'class="hidden px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white id-p">' +
                    id +
                    '</td>' +
                    '<td scope="row"' +
                    'class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white nombre-p" >' +
                    nombre +
                    '</td>' +
                    '<td class="px-6 py-4 tipo-p">' +
                    tipo +
                    '</td>' +
                    '<td class="px-6 py-4 precio_unitario-p  text-gray-900 text-right">' +
                    precio_unitario.toFixed(2) +
                    '</td>' +
                    '<td class="px-6 py-4">' +
                    ' <input type="number" onchange="actualizaDataVenta()" class="cantidad-p text-right w-20  text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" value="1"/>' +
                    '</td>' +
                    '<td class="px-6 py-4 total-p  text-gray-900 text-right">' +

                    '</td>' +
                    '<td class="items-center px-6 py-4 mr-2">' +
                    '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"' +
                    'onclick="deleteProductoV(' + id + ',\'' + nombre + '\')"' +
                    '>Eliminar</button>' +
                    '</td>' +
                    '</tr>';
                $("#productosTable").append(row);

            }
            actualizaDataVenta();
            $("#busca_producto").focus();
        }
    }).fail(function (data) {
    })
}

window.actualizaDataVenta = function () {

    var precio_unitario = 0;
    var cantidad = 0;
    var total = 0;
    var x = -1;
    var total_global = 0;
    $(".precio_unitario-p").each(function () {
        x++;
        precio_unitario = parseFloat($(this).html());
        cantidad = parseFloat($(".cantidad-p").eq(x).val());
        total = precio_unitario * cantidad;
        total_global += total;
        $(".total-p").eq(x).html(total.toFixed(2));
    })
    $("#total_global").html(total_global.toFixed(2));
}

window.deleteProductoV = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar el producto: " + nombre + " del listado de venta?",
        text: "El producto ya no será vendido.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {
            $("#producto" + id).remove();
            actualizaDataVenta();
        }
    });
}
window.cierraVenta = function () {
    $("#total_general_box").html($("#total_global").html().trim());
    setTimeout(() => {
        $("#pago").select();
    }, 200);
}

window.setPagoV = function () {
    var pago = parseFloat($("#pago").val());
    var total_global = parseFloat($("#total_general_box").html().trim());
    var cambio = pago - total_global;
    $("#cambio_general_box").html(cambio);
}

window.almacenaVenta = function () {
    var pago = parseFloat($("#pago").val());
    var total_global = parseFloat($("#total_general_box").html().trim());
    var cambio = pago - total_global;

    if (pago >= total_global) {
        $("#pago").removeClass("is-invalid");
        if ($(".id-p").length > 0) {
            //procedemos a almacenar la venta registrada
            var conta = -1;
            var ids = "";
            var precios = "";
            var cantidades = "";
            var totales = "";
            var cliente = $("#cliente").val();
            var metodo_pago = $("#metodo_pago").val();
            $(".id-p").each(function () {
                conta++;
                ids += $(this).html().trim() + "|";
                precios += $(".precio_unitario-p").eq(conta).html().trim() + "|";
                cantidades += $(".cantidad-p").eq(conta).val().trim() + "|";
                totales += $(".total-p").eq(conta).html().trim() + "|";
            });
            var total_global = $("#total_global").html().trim();

            $.ajax({
                type: 'POST',
                url: "/ventas/store",
                data: {
                    ids: ids,
                    precios: precios,
                    cantidades: cantidades,
                    totales: totales,
                    total_global: total_global,
                    cliente: cliente,
                    metodo_pago: metodo_pago,
                    pago: pago,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnAlmacenaVenta").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                    $("#btnAlmacenaVenta").prop("disabled", true);
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    swal({
                        title: "Venta Exitosa",
                        text: "Se ha almacenado correctamente la venta.",
                        icon: "success",
                        type: "success",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((willDelete) => {
                        window.open('/ventas/imprimeticket/' + response.id, '_blank');
                        window.location.replace("/ventas");
                    });
                    $("#btnAlmacenaVenta").html('Almacenar Venta');
                    $("#btnAlmacenaVenta").prop("disabled", false);
                } else {
                    swal({
                        title: "Error de Almacenamiento",
                        text: "Ocurrió un error al tratar de almacenar la venta, intente más tarde." + response.result,
                        icon: "error",
                        type: "error",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((willDelete) => {
                    });
                    $("#btnAlmacenaVenta").html('Almacenar Venta');
                    $("#btnAlmacenaVenta").prop("disabled", false);
                }
            }).fail(function (data) {
            })

        } else {
            swal({
                title: "Venta vacía",
                text: "Indicar productos a vender",
                icon: "info",
                type: "info",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok!'
            }).then((willDelete) => {
                if (willDelete) {
                }
            });
        }
    } else {
        $("#pago").addClass("is-invalid");
    }
}

//Funciones para reservaciones

window.addReservacion = function () {
    if (validaReservacion()) {
        $.ajax({
            type: 'POST',
            url: "/reservaciones/store",
            data: {
                id: $("#id").val(),
                _token: $("input[name=_token]").val(),
                nombre: $("#nombre").val(),
                tipo_documento: $("#tipo_documento").val(),
                documento: $("#documento").val(),
                rfc: $("#rfc").val(),
                razon_social: $("#razon_social").val(),
                email: $("#email").val(),
                telefono: $("#telefono").val(),
                habitaciones_id: $("#habitaciones_id").val(),
                fecha_hora_entrada: $("#fecha_hora_entrada").val(),
                fecha_hora_salida: $("#fecha_hora_salida").val(),
                precio_hospedaje: $("#precio_hospedaje").val(),
                cobro_extra: $("#cobro_extra").val() == "" ? 0 : $("#cobro_extra").val(),
                descuento: $("#descuento").val() == "" ? 0 : $("#descuento").val(),
                adelanto: $("#adelanto").val() == "" ? 0 : $("#adelanto").val(),
                metodo_pago: $("#metodo_pago").val(),
                observaciones: $("#observaciones").val(),
                estado: $("#estado").val(),
                clientes_id: $("#clientes_id").val(),
                total_pagar: $("#total_pagar").html().trim()
            },
            beforeSend: function () {
                //$("#btnAlmacenaReservacion").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                //$("#btnAlmacenaReservacion").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {

                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/reservaciones");
                });
            } else {
                swal({
                    title: "Error de Reservaciones",
                    text: "Ocurrió un error al tratar de almacenar la reservación, " + response.message,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaReservacion").html('Almacenar Reservación');
                $("#btnAlmacenaReservacion").prop("disabled", false);
            }
        }).fail(function (data) {

        })
    }

}

window.validaReservacion = function () {
    var valid = true;
    var inputs = [
        "fecha_hora_entrada",
        "fecha_hora_salida",
        "nombre",
        "documento",
        "email",
        "telefono",
    ];
    var selects = [
        "tipo_documento",
        "estado"
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    if (valid) {
        //Aqui validamos que las fechas sean correctas
        var fecha_hora_entrada = new Date($("#fecha_hora_entrada").val());
        var fecha_hora_salida = new Date($("#fecha_hora_salida").val());
        if (fecha_hora_entrada >= fecha_hora_salida) {
            swal({
                title: "Resultado de Validación",
                text: "Las fecha de reservación de entrada no puede ser igual o mayor que la fecha de salida",
                icon: "error",
                type: "error",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            }).then((willDelete) => {
            });
            valid = false;
        }
    } else {
        swal({
            title: "Resultado de Validación",
            text: "Favor de completar los campos obligatorios",
            icon: "error",
            type: "error",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        }).then((willDelete) => {
        });
    }

    return valid;
}

window.clearReservacion = function () {
    $("#id").val('');
    $("#nombre").val('');
    $("#tipo_documento").val('');
    $("#documento").val('');
    $("#rfc").val('');
    $("#razon_social").val('');
    $("#email").val('');
    $("#telefono").val('');
    $("#habitaciones_id").val('');
    $("#fecha_hora_entrada").val('');
    $("#fecha_hora_salida").val('');
    $("#precio_hospedaje").val('');
    $("#cobro_extra").val('');
    $("#descuento").val('');
    $("#adelanto").val('');
    $("#metodo_pago").val('');
    $("#observaciones").val('');
    $("#estado").val('');
    $("#clientes_id").val('');
    $("#total_pagar").html("0.00");
    $("#btnAlmacenaReservacion").show('fast');
    $("#btnCancelar").html('Cancelar');
    $("#btnDeleteReservacion").hide("fast");

    $("#nombre").removeClass('is-invalid');
    $("#tipo_documento").removeClass('is-invalid');
    $("#documento").removeClass('is-invalid');
    $("#email").removeClass('is-invalid');
    $("#telefono").removeClass('is-invalid');
    $("#habitaciones_id").removeClass('is-invalid');
    $("#fecha_hora_entrada").removeClass('is-invalid');
    $("#fecha_hora_salida").removeClass('is-invalid');
    $("#estado").removeClass('is-invalid');

    $("#titulo").html("Registra Reservación");
    $("#btnAlmacenaReservacion").html("Almacenar Reservación");

}

window.setCosto = function () {
    var habitacion = $("#habitaciones_id").val();
    var costo = 0;
    if (habitacion != "") {
        costo = $("#habitaciones_id option:selected").attr("costo");
    }
    $("#precio_hospedaje").val(parseFloat(costo));
    actualizaTotalReservacion();
}

window.actualizaTotalReservacion = function () {
    //Verificamos que los campos de fecha_entrada y fecha_salida ya fueron indicadas

    var fecha_hora_entrada = $("#fecha_hora_entrada").val();
    var fecha_hora_salida = $("#fecha_hora_salida").val();
    var dias = 1;

    if (fecha_hora_entrada.length > 0 && fecha_hora_salida.length > 0) {
        var fechaInicio = new Date(fecha_hora_entrada).getTime();
        var fechaFin = new Date(fecha_hora_salida).getTime();

        var diff = fechaFin - fechaInicio;

        dias = diff / (1000 * 60 * 60 * 24);
    }
    var precio = parseFloat($("#precio_hospedaje").val()) * dias;
    var cobro_extra = $("#cobro_extra").val() == "" ? 0 : parseFloat($("#cobro_extra").val());
    var descuento = $("#descuento").val() == "" ? 0 : parseFloat($("#descuento").val());
    var adelanto = $("#adelanto").val() == "" ? 0 : parseFloat($("#adelanto").val());
    var total = parseFloat(((precio + cobro_extra) - descuento) - adelanto);
    $("#total_pagar").html(total.toFixed(2));
}

window.getDataReservacion = function (id) {
    $.ajax({
        type: 'GET',
        url: "/reservaciones/getinfo/" + id,
        data: {
        },
        beforeSend: function () {
            //$("#btnAlmacenaReservacion").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
            //$("#btnAlmacenaReservacion").prop("disabled", true);
        }
    }).done(function (response) {
        if (response.result == "ok") {
            setDataReservacion(response.reservacion);
        }
    }).fail(function (data) {

    })
}

window.setDataReservacion = function (data) {
    $("#id").val(data.reservacion);
    $("#nombre").val(data.cliente);
    $("#tipo_documento").val(data.tipo_documento);
    $("#documento").val(data.documento);
    $("#rfc").val(data.rfc);
    $("#razon_social").val(data.razon_social);
    $("#email").val(data.email);
    $("#telefono").val(data.telefono);
    $("#habitaciones_id").val(data.habitaciones_id);
    $("#fecha_hora_entrada").val(data.fecha_hora_entrada.split(" ")[0]);
    $("#fecha_hora_salida").val(data.fecha_hora_salida.split(" ")[0]);
    $("#precio_hospedaje").val(data.precio);
    $("#cobro_extra").val(data.cobro_extra);
    $("#descuento").val(data.descuento);
    $("#adelanto").val(data.adelanto);
    $("#metodo_pago").val(data.metodo_pago);
    $("#observaciones").val(data.observaciones);
    $("#estado").val(data.estado_reservacion);
    if(data.estado_reservacion == "terminada")
        $("#estado").prop("disabled",true);
    $("#clientes_id").val(data.clientes_id);
    $("#cliente").val(data.clientes_id);
    $("#titulo").html("Actualiza Reservación");
    $("#btnAlmacenaReservacion").html("Actualiza Reservación");
    if(data.estado_reservacion != "terminada" && data.estado_reservacion != "confirmada" && data.estado_reservacion != "ingreso")
        $('#btnDeleteReservacion').show('');
    if(data.estado_reservacion == "terminada"){
        $("#btnAlmacenaReservacion").hide('');
        $("#btnCancelar").html('Cerrar ventana');
    }else{
        $("#btnAlmacenaReservacion").show('');
        $("#btnCancelar").html('Cancelar');
    }

    actualizaTotalReservacion();
}

//Funciones para Clientes
window.validaCliente = function () {
    var valid = true;
    var inputs = [
        "nombre",
        "documento",
        "email",
        "telefono"
    ];
    var selects = [
        "tipo_documento",
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    return valid;
}

window.addCliente = function () {
    var nombre = $("#nombre").val().trim();
    var tipo_documento = $("#tipo_documento").val();
    var documento = $("#documento").val().trim();
    var email = $("#email").val().trim();
    var telefono = $("#telefono").val().trim();
    var rfc = $("#rfc").val().trim();
    var razon_social = $("#razon_social").val();
    var id = $("#id").val();
    if (validaCliente()) {
        $.ajax({
            type: 'POST',
            url: "/clientes/store",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                nombre: nombre,
                tipo_documento: tipo_documento,
                documento: documento,
                email: email,
                telefono: telefono,
                rfc: rfc,
                razon_social: razon_social
            },
            beforeSend: function () {
                $("#btnAlmacenaCliente").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaCliente").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/clientes");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar el cliente, intente más tarde.",
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaCliente").html('Almacenar Cliente');
                $("#btnAlmacenaCliente").prop("disabled", false);
            }
        }).fail(function (data) {

        })
    }


}

window.setDataCliente = function (id) {
    clearDataCliente();
    var nombre = $("#cliente" + id).find("td").eq(2).html().trim();
    var tipo_documento = $("#cliente" + id).find("td").eq(3).html().trim();
    var documento = $("#cliente" + id).find("td").eq(4).html().trim();
    var email = $("#cliente" + id).find("td").eq(5).html().trim();
    var telefono = $("#cliente" + id).find("td").eq(6).html().trim();
    var rfc = $("#cliente" + id).find("td").eq(7).html().trim();
    var razon_social = $("#cliente" + id).find("td").eq(8).html().trim();

    $("#id").val(id);
    $("#nombre").val(nombre);
    $("#tipo_documento").val(tipo_documento);
    $("#documento").val(documento);
    $("#email").val(email);
    $("#telefono").val(telefono);
    $("#rfc").val(rfc);
    $("#razon_social").val(razon_social);
}

window.clearDataCliente = function () {
    $("#id").val("");
    $("#nombre").val("");
    $("#tipo_documento").val("");
    $("#documento").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#rfc").val("");
    $("#razon_social").val("");


    $("#nombre").removeClass("is-invalid");
    $("#tipo_documento").removeClass("is-invalid");
    $("#documento").removeClass("is-invalid");
    $("#email").removeClass("is-invalid");
    $("#telefono").removeClass("is-invalid");

}

window.deleteCliente = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar el Cliente: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este cliente se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/clientes/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteCliente" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#cliente" + id).hide('slow');
                    $("#cliente" + id).remove();
                    window.location.replace("/clientes");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Usuarios Internos
window.validaUsuario = function () {
    var valid = true;
    var inputs = [
        "name",
        "email",
        "password",
        "telefono",
        "cuenta"
    ];
    var selects = [
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    return valid;
}

window.addUsuario = function () {
    var name = $("#name").val().trim();
    var email = $("#email").val();
    var telefono = $("#telefono").val().trim();
    var password = $("#password").val().trim();
    var cuenta = $("#cuenta").val().trim();
    var id = $("#id").val();
    if (validaUsuario()) {
        $.ajax({
            type: 'POST',
            url: "/usuariosinternos/store",
            data: {
                id: id,
                _token: $("input[name=_token]").val(),
                name: name,
                email: email,
                telefono: telefono,
                password: password,
                cuenta: cuenta
            },
            beforeSend: function () {
                $("#btnAlmacenaUsuario").html('<svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
                $("#btnAlmacenaUsuario").prop("disabled", true);
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Éxito en el Almacenamiento",
                    text: response.message,
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/usuariosinternos");
                });

            } else {
                swal({
                    title: "Error de Almacenamiento",
                    text: "Ocurrió un error al tratar de almacenar el usuario, intente más tarde.",
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
                $("#btnAlmacenaUsuario").html('Almacenar Cliente');
                $("#btnAlmacenaUsuario").prop("disabled", false);
            }
        }).fail(function (data) {

        })
    }


}

window.setDataUsuario = function (id) {
    clearDataUsuario();
    var name = $("#usuario" + id).find("td").eq(1).html().trim();
    var email = $("#usuario" + id).find("td").eq(2).html().trim();
    var cuenta = $("#usuario" + id).find("td").eq(3).html().trim();
    var telefono = $("#usuario" + id).find("td").eq(4).html().trim();
    var password = $("#usuario" + id).find("td").eq(6).html().trim();

    $("#id").val(id);
    $("#name").val(name);
    $("#email").val(email);
    $("#telefono").val(telefono);
    $("#cuenta").val(cuenta);
    $("#password").val(password);
}

window.clearDataUsuario = function () {
    $("#id").val("");
    $("#name").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#cuenta").val("SIIN." + Math.random().toString(36).slice(2, 6));
    $("#password").val(Math.random().toString(36).slice(2, 12));



    $("#name").removeClass("is-invalid");
    $("#email").removeClass("is-invalid");
    $("#telefono").removeClass("is-invalid");
    $("#cuenta").removeClass("is-invalid");
    $("#password").removeClass("is-invalid");

}

window.deleteUsuarioInt = function (id, nombre) {
    swal({
        title: "Está seguro de querer eliminar el Usuario: " + nombre + "?",
        text: "Si se elimina, los registros asociados a este usuario se perderán.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/usuariosinternos/destroy",
                data: {
                    id: id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteUsuario" + id).html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    $("#usuario" + id).hide('slow');
                    $("#usuario" + id).remove();
                    window.location.replace("/usuariosinternos");
                }

            }).fail(function (data) {

            })

        }
    });
}

//Funciones para Recepciones

window.addRecepcion = function () {
    if (validaRecepcion()) {
        var data = $("#formRecepcion").serialize();
        data = data + "&total=" + $("#total_pagar").html();
        $.ajax({
            type: 'POST',
            url: "/recepciones/store",
            data: data,
            beforeSend: function () {
                $("#btnAlmacenaAlojamiento").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
            }
        }).done(function (response) {
            if (response.result == "ok") {
                swal({
                    title: "Resultado de registro de Alojamiento",
                    text: "Se ha Registrado Satisfactoriamente el Alojamiento",
                    icon: "success",
                    type: "success",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                    window.location.replace("/recepciones");
                });
            } else {
                swal({
                    title: "Resultado de registro de Alojamiento",
                    text: response.message,
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((willDelete) => {
                });
            }
            $("#btnAlmacenaAlojamiento").html('Registrar alojamiento');
        }).fail(function (data) {

        })

    }
}

window.validaRecepcion = function () {
    var valid = true;
    var inputs = [
        "nombre",
        "documento",
        "email",
        "telefono",
        "fecha_hora_entrada",
        "fecha_hora_salida",
    ];
    var selects = [
        "tipo_documento",
        "cliente"
    ];
    valid = true;

    for (var x = 0; x < inputs.length; x++) {
        if ($("#" + inputs[x]).val().trim().length == 0) {
            $("#" + inputs[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + inputs[x]).removeClass("is-invalid");
        }
    }

    for (var x = 0; x < selects.length; x++) {
        if ($("#" + selects[x]).val() == '') {
            $("#" + selects[x]).addClass("is-invalid");
            valid = false;
        } else {
            $("#" + selects[x]).removeClass("is-invalid");
        }
    }

    if (valid) {
        //Aqui validamos que las fechas sean correctas
        var fecha_hora_entrada = new Date($("#fecha_hora_entrada").val());
        var fecha_hora_salida = new Date($("#fecha_hora_salida").val());
        if (fecha_hora_entrada >= fecha_hora_salida) {
            swal({
                title: "Resultado de Validación",
                text: "Las fecha de entrada no puede ser igual o mayor que la fecha de salida",
                icon: "error",
                type: "error",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            }).then((willDelete) => {
            });
            valid = false;
        }
    } else {
        swal({
            title: "Resultado de Validación",
            text: "Favor de completar los campos obligatorios",
            icon: "error",
            type: "error",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        }).then((willDelete) => {
        });
    }

    return valid;
}

window.setCliente = function () {

    var id = $("#cliente").val();
    if (id != "" && id != 0) {
        $.ajax({
            type: 'GET',
            url: "/clientes/getinfo/" + id,
            data: {},
            beforeSend: function () {
                $("#loadingcliente").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
            }
        }).done(function (response) {
            if (response.result == "ok") {
                //clearDataCliente();
                $("#clientes_id").val(response.cliente.id);
                $("#nombre").val(response.cliente.nombre);
                $("#tipo_documento").val(response.cliente.tipo_documento);
                $("#documento").val(response.cliente.documento);
                $("#email").val(response.cliente.email);
                $("#telefono").val(response.cliente.telefono);
                $("#rfc").val(response.cliente.rfc);
                $("#razon_social").val(response.cliente.razon_social);
                $("#loadingcliente").html('');
            }
        }).fail(function (data) {

        })
    } else {
        clearClienteRecepcion();
    }
}

window.clearClienteRecepcion = function () {
    $("#clientes_id").val("");
    $("#nombre").val("");
    $("#tipo_documento").val("");
    $("#documento").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#rfc").val("");
    $("#razon_social").val("");
    $("#nombre").removeClass("is-invalid");
    $("#tipo_documento").removeClass("is-invalid");
    $("#documento").removeClass("is-invalid");
    $("#email").removeClass("is-invalid");
    $("#telefono").removeClass("is-invalid");
}

window.showinforeservacion = function (id) {
    $.ajax({
        type: 'GET',
        url: "/reservaciones/showinfo/" + id,
        data: {},
        beforeSend: function () {
            $("#inforeservacion").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
        }
    }).done(function (response) {
        $("#inforeservacion").html(response);
    }).fail(function (data) {

    })
}

// Funciones para Salidas
window.setPago = function(){
    var total_pagar = parseFloat($("#total_pagar_").html().trim().replace(",",""));
    var mora = parseFloat($("#mora").val()!=""?$("#mora").val():0);
    var pago = total_pagar + mora;
    $("#pago").val(pago.toFixed(2));
}

window.terminaAlojamiento = function (){
    var total_pagar = parseFloat($("#total_pagar_").html().trim().replace(",",""));
    var mora = parseFloat($("#mora").val()!=""?$("#mora").val():0);
    var pago = total_pagar + mora;
    var id = $("#id").val();
    var _token = $("input[name=_token]").val();
    var metodo_pago = $("#metodo_pago").val();

    $.ajax({
        type: 'POST',
        url: "/salida/terminar",
        data: {id:id,mora:mora,pago:pago,_token:_token,metodo_pago:metodo_pago},
        beforeSend: function () {
            $("#btnTerminarAlojamiento").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
        }
    }).done(function (response) {
        if (response.result == "ok") {
            swal({
                title: "Alojamiento Concluido",
                text: response.message,
                icon: "success",
                type: "success",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            }).then((willDelete) => {
                window.open('/salida/imprime/' + response.id, '_blank');
                window.location.replace("/salidas");
            });

        } else {
            swal({
                title: "Error al tratar de culminar alojamiento",
                text: "Ocurrió un error al tratar de concluir el alojamiento.",
                icon: "error",
                type: "error",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            }).then((willDelete) => {
            });
            $("#btnTerminaAlojamiento").html('Terminar Alojamiento y Limpiar Habitación');
        }
    }).fail(function (data) {

    })
}
//Functiones para Reportes
window.getReport = function (){
    var fecha_inicial = $("#fecha_inicial").val();
    var fecha_final = $("#fecha_final").val();
    var responsable = $("#responsable").val();
    var _token = $("input[name=_token]").val();
    $.ajax({
        type: 'POST',
        url: "/reportes/filtering",
        data: {fecha_inicial:fecha_inicial,fecha_final:fecha_final,responsable:responsable,_token:_token},
        beforeSend: function () {
            $("#resultadoconsulta").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
        }
    }).done(function (response) {
        $("#resultadoconsulta").html(response)
    }).fail(function (data) {
        $("#resultadoconsulta").html("")
    })
}

window.showModalInfo = function(id){

    $.ajax({
        type: 'GET',
        url: "/reportes/getinfoalojamiento/"+id,
        data: {},
        beforeSend: function () {
            $("#infoalojamiento").html('<svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin  fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>');
        }
    }).done(function (response) {
        $("#infoalojamiento").html(response)
    }).fail(function (data) {
        $("#infoalojamiento").html("")
    })

    $("#btnShowModal").click();

}

window.deleteReservacion = function (){
   var  id = $("#id").val();
    swal({
        title: "Está seguro de querer eliminar esta Reservación:",
        text: "Si se elimina, los datos de la reservación serán eliminados totalmente.",
        icon: "warning",
        type: "warning",
        buttons: ["Cancelar", "Si!"],
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((willDelete) => {
        if (willDelete) {

            $.ajax({
                type: 'POST',
                url: "/reservaciones/destroy",
                data: {
                    id:id,
                    _token: $("input[name=_token]").val()
                },
                beforeSend: function () {
                    $("#btnDeleteReservacion").html(
                        'Eliminando...');
                }
            }).done(function (response) {
                if (response.result == "ok") {
                    swal({
                        title: "Reservación eliminada!",
                        text: response.message,
                        icon: "success",
                        type: "success",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((confirm) => {
                        window.location.replace("/reservaciones");
                    });
                }

            }).fail(function (data) {
                $("#btnDeleteReservacion").html(
                    'Eliminar Reservación');
                swal({
                    title: "Ocurrió un error!",
                    text: "No se pudo eliminar la reservación correspondiente!",
                    icon: "error",
                    type: "error",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((confirm) => {

                });
            })

        }
    });
}
/**/


