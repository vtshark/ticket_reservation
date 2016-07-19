var ROOT = "/";

///функция выделяющая отмеченные места в секторе после загрузки страницы
function selectSeats() {
    var sector = $("#sector").val();
    var ip = $("#ip").val();
    var arr;
    var url = ROOT + "main/getSelSeats/";
    var color = "";
    var counter = 0; //счетчик кол-ва отмеченных мест
     $.post(url, {sector: sector},
     function (result) {
        if (result) {
            //console.log(result);
            var obj = JSON.parse(result);

            $.each(obj, function (i, row) {

                $.each(row, function (j, val) {
                    arr = val.split("#");
                    //console.log(val);
                    counter++;

                    if (arr[0] == 1) {
                        color = '#8888DD';
                    } else {
                        if (arr[1] === ip) {
                            color = '#AADDAA'; //место, отмеченное пользователем
                        } else {
                            color = '#BBBBBB'; //место, отмеченное др. пользователем
                        }
                    }

                    $("#" + i + "-" + j).css({"background-color": color});

                });

            });
            $("#freeSeats").val( $("#countSeats").val() - counter);
        }
     });
}


$(document).ready(function() {

    selectSeats();

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
                //console.log(result);
                switch (+result) {
                    case 0:
                        $("#"+id).css({ "background-color": '' }); //отмена выбора
                        break;
                    case 1:
                        $("#"+id).css({ "background-color": '#AADDAA' }); //выбор места
                        break;
                    case 2:
                        alert("Извините, место уже бронируется другим пользователем!");
                        break;
                    case 3:
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
                //console.log(result);
                selectSeats();
            });

    });
});