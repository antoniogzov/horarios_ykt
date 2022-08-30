$(document).ready(function () {
  $(document).on("click", ".btnDesgloseHijos", function () {
    var id_family = $(this).attr("data-id-family");
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "getStudentsActiveByFamily",
        id_family: id_family,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);

        if (data.response == true) {
          var html = "";
          var contador = 0;
          html += '<table class="table">';
          html += "<thead>";
          html += "<tr>";
          html += '<th scope="col"></th>';
          html += '<th scope="col">CÓD. ALUMNO</th>';
          html += '<th scope="col">NOMBRE</th>';
          for (let da = 1; da <= 7; da++) {
            html += '<th scope="col">' + da + "</th>";
          }
          html += "</tr>";
          html += "</thead>";
          html += "<tbody>";

          for (let s = 0; s < data.data.length; s++) {
            const student_code = data.data[s].student_code;
            const student_name =
              data.data[s].name + " " + data.data[s].lastname;
            const id_student = data.data[s].id_student;
            html += "<tr>";
            html +=
              '<th scope="row"><input type="time" id="time" value="07:30"></th>';
            html += "<td>" + student_code + "</td>";
            html += "<td>" + student_name + "</td>";

            for (let h = 0; h < data.data[s].schedule_student.length; h++) {
              var id_day = h + 1;
              if (data.data[s].schedule_student[h].schedule != undefined) {
                console.log(data.data[s].schedule_student[h].schedule);
                html +=
                  '<td class="td-edit-day-schendule" id="td-schendule_day' +
                  id_day +
                  "_student" +
                  id_student +
                  '" data-id-day="' +
                  id_day +
                  '" data-id-student="' +
                  data.data[s].id_student +
                  '" contenteditable="true">' +
                  data.data[s].schedule_student[h].schedule +
                  "</td>";
              } else {
                html +=
                  '<td class="td-edit-day-schendule" id="td-schendule_day' +
                  id_day +
                  "_student" +
                  id_student +
                  '" data-id-day="' +
                  id_day +
                  '" data-id-student="' +
                  data.data[s].id_student +
                  '" contenteditable="true"></td>';
              }
            }
            html += "</tr>";
          }
          $("#accordionHijosActivos").empty().append(html);

          /* 
          $("#id_zona").empty();
          $("#id_zona").append(
            '<option value="" disabled selected>Elija una zona*</option><optgroup label="Zonas">'
          );
          for (var i = 0; i < data.data.length; i++) {
            $("#id_zona").append(
              '<option value="' +
                data.data[i].id_zonas_central +
                '">' +
                data.data[i].descripcion +
                "</option>"
            );
          }
          $("#id_zona").append("</optgroup>");
          $("#id_zona").prop("disabled", false);
         */
        } else {
          $("#id_zona").prop("disabled", true);
          $.NotificationApp.send(
            "Al parecer ésta central no tiene zonas asignadas",
            "",
            "top-right",
            "#06996f",
            "warning"
          );
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });

  $(document).on("focusin", ".td-edit-day-schendule", function () {
    console.log("td-editable");
    var hora = $(this).text();
    var id_student = $(this).attr("data-id-student");
    var id_day = $(this).attr("data-id-day");
    console.log(hora.length);
    if (hora.length > 1 || hora.length == 0) {
      $(this).html(
        ' <input type="time" class="time_day" data-id_day="' +
          id_day +
          '" data-id-student="' +
          id_student +
          '" id="time_day' +
          id_day +
          "_student" +
          id_student +
          '" value="' +
          hora +
          '"></input>'
      );
    } else {
      var hora = $(
        "#time_day" + id_day + "_student" + id_student
      ).val();
      $("#time_day" + id_day + "_student" + id_student).val(hora);
    }
  });

  $(document).on("focusout", ".time_day", function () {
    //--- --- ---//
    var id_student = $(this).attr("data-id-student");
    var id_day = $(this).attr("data-id_day");

    var hora = $(this).val();
    console.log("#td-schendule_day" + id_day + "_student" + id_student);
    $("#td-schendule_day" + id_day + "_student" + id_student).empty().text(hora);
    //--- --- ---//
  });

  $(document).on("focusin", ".td-grade-evaluation", function () {
    //--- --- ---//
    var grade = $(this).text().trim();
    value_before = grade;
    //--- --- ---//
  });
});

function loading() {
  Swal.fire({
    title: "Cargando...",
    html: '<img src="img/loading.gif" width="300" height="300">',
    allowOutsideClick: false,
    allowEscapeKey: false,
    showCloseButton: false,
    showCancelButton: false,
    showConfirmButton: false,
  });
}
