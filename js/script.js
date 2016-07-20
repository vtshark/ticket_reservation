var ROOT = "/";

//функция выделяющая зарезервированные места в секторе после загрузки страницы
function showReservSeats() {
    var sector = $("#sector").val();
    var url = ROOT + "main/getReservSeats/";
    var counter = 0; //счетчик кол-ва отмеченных мест
     $.post(url, {sector: sector},
     function (result) {
        if (result) {
            var obj = JSON.parse(result);
            $.each(obj, function (i, row) {
                $.each(row, function (j, val) {
                    counter++;
                    $("#" + i + "-" + j).css({"background-color": '#8888DD'});
                });
            });
            $("#freeSeats").val( $("#countSeats").val() - counter);
        }
     });
}

//функция выделяющая выбранные пользоваетелем места в секторе после загрузки страницы
function showSelectSeats() {
    var sector = $("#sector").val();
    var url = ROOT + "main/getSelectSeats/";
    $.post(url, {sector: sector},
        function (result) {
            if (result) {
                var obj = JSON.parse(result);
                $.each(obj, function (i, row) {
                    $.each(row, function (j, val) {
                        $("#" + i + "-" + j).css({"background-color": '#AADDAA'});
                    });
                });
            }
        });
}


$(document).ready(function() {

    showSelectSeats();
    showReservSeats();

    // выбор места в секторе
    $(".seat").on("click", function (e) {
        var id = e.target.id;
        var arr = id.split("-");
        var seat = arr[1];
        var row = arr[0];
        var url = ROOT + "main/selectSeat/";
        var sector = $("#sector").val();

        var color = $("#"+id).css("background-color");

        $.post(url, {sector: sector, row: row, seat: seat},
            function (result) {

                switch (+result) {
                    case 0:
                        $("#"+id).css({ "background-color": '' }); //отмена выбора
                        break;
                    case 1:
                        $("#"+id).css({ "background-color": '#AADDAA' }); //выбор места
                        break;
                    case 2:
                        alert("Извините, место уже забронировано!");
                        break;
                }

            });


    });

    //бронирование выделенных мест
    $("#reserv-btn").on("click", function () {

        var sector = $("#sector").val();
        var url = ROOT + "main/reservation/";
        $.post(url, {sector: sector},
            function (result) {
                if (result) {
                    alert(result);
                } else {
                    showReservSeats();
                }
            });

    });
});