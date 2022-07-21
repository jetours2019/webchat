$(document).ready(function () {
    $("input[name='calificacion']").change(function () {
        $("#submit").removeAttr("disabled").removeClass("noHover");
    });

    if(saved == 1){
        Swal.fire({
            title: 'Muchas gracias por tu calificación',
            text: "Tu opinion es muy importante para nosotros",
            icon: 'success',
            showCancelButton: false,
            showDenyButton: false,
            showConfirmButton: false,
            timer: 2000
        })
    }else if(error == 1){
        Swal.fire({
            title: 'Oops...',
            text: "Algo salió mal, intenta de nuevo",
            icon: 'error',
            showCancelButton: false,
            showDenyButton: false,
            showConfirmButton: false,
            timer: 2000
        })
    }
});
