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

        if (data.response == true) {
          var html = "";
          var contador = 0;
          var dias = ["", "lun", "mar", "mie", "jue", "vie", "sab", "dom"];

          html += '<table class="table">';
          html += "<thead>";
          html += "<tr>";
          html += '<th scope="col"></th>';
          html += '<th scope="col">CÓD. ALUMNO</th>';
          html += '<th scope="col">NOMBRE</th>';
          html += '<th scope="col">CÓD. iTEACH</th>';
          for (let da = 1; da <= 7; da++) {
            html += '<th scope="col">' + dias[da].toUpperCase() + "</th>";
          }
          html += "</tr>";
          html += "</thead>";
          html += "<tbody>";
          var family_name = data.data[0].family_name;
          var family_address = data.data[0].family_address;
          $("#address_family")
            .empty()
            .text("DIRECCIÓN PRINCIPAL: " + family_address.toUpperCase());
          $("#family_name")
            .empty()
            .text("FAMILIA: " + family_name);
          for (let s = 0; s < data.data.length; s++) {
            const student_code = data.data[s].student_code;
            const student_name =
              data.data[s].name + " " + data.data[s].lastname;
            const id_student = data.data[s].id_student;
            const group_code = data.data[s].group_code;
            html += "<tr>";
            html +=
              '<th scope="row"><input class="generalSchStudent" data-student-code="' +
              student_code +
              '" data-id-student="' +
              id_student +
              '" type="time" id="time" value="00:00"></th>';
            html += "<td>" + student_code + "</td>";
            html += "<td>" + student_name + "</td>";
            html += "<td>" + group_code + "</td>";

            for (let h = 0; h < data.data[s].schedule_student.length; h++) {
              var id_day = h + 1;
              if (id_day != 6) {
                if (data.data[s].schedule_student[h].schedule != undefined) {
                  schendule_ar =
                    data.data[s].schedule_student[h].schedule.split(":");
                  schendule = schendule_ar[0] + ":" + schendule_ar[1];

                  html +=
                    '<td class="td-edit-day-schendule schStudent' +
                    id_student +
                    '" id="td-schendule_day' +
                    id_day +
                    "_student" +
                    id_student +
                    '" data-id-day="' +
                    id_day +
                    '" data-id-student="' +
                    data.data[s].id_student +
                    '" data-student-code="' +
                    data.data[s].student_code +
                    '" contenteditable="true">' +
                    schendule +
                    "</td>";
                } else {
                  html +=
                    '<td class="td-edit-day-schendule schStudent' +
                    id_student +
                    '" id="td-schendule_day' +
                    id_day +
                    "_student" +
                    id_student +
                    '" data-id-day="' +
                    id_day +
                    '" data-id-student="' +
                    data.data[s].id_student +
                    '" data-student-code="' +
                    data.data[s].student_code +
                    '" contenteditable="true"></td>';
                }
              } else {
                html +=
                  '<td class="td-edit-day-schendule" style="background-color: rgba(87, 87, 87,0.3)">N/A</td>';
              }
            }
            html += "</tr>";
          }
          $("#accordionHijosActivos").empty().append(html);

          } else {
         
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
  $(document).on("click", ".btnDesgloseAlumno", function () {
    var id_student = $(this).attr("data-id-student");
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "getSchedulesByStudent",
        id_student: id_student,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var html = "";
          var contador = 0;
          var dias = ["", "lun", "mar", "mie", "jue", "vie", "sab", "dom"];

          html += '<table class="table">';
          html += "<thead>";
          html += "<tr>";
          html += '<th scope="col"></th>';
          html += '<th scope="col">CÓD. ALUMNO</th>';
          html += '<th scope="col">NOMBRE</th>';
          html += '<th scope="col">CÓD. iTEACH</th>';
          for (let da = 1; da <= 7; da++) {
            html += '<th scope="col">' + dias[da].toUpperCase() + "</th>";
          }
          html += "</tr>";
          html += "</thead>";
          html += "<tbody>";
          var name_student = data.data[0].name_student;
          var family_address = data.data[0].family_address;
          $("#address_family")
            .empty()
            .text("DIRECCIÓN PRINCIPAL: " + family_address.toUpperCase());
          $("#student_name")
            .empty()
            .text("FAMILIA: " + name_student);
          for (let s = 0; s < data.data.length; s++) {
            const student_code = data.data[s].student_code;
            const student_name =
              data.data[s].name + " " + data.data[s].lastname;
            const id_student = data.data[s].id_student;
            const group_code = data.data[s].group_code;
            html += "<tr>";
            html +=
              '<th scope="row"><input class="generalSchStudent" data-student-code="' +
              student_code +
              '" data-id-student="' +
              id_student +
              '" type="time" id="time" value="00:00"></th>';
            html += "<td>" + student_code + "</td>";
            html += "<td>" + student_name + "</td>";
            html += "<td>" + group_code + "</td>";

            for (let h = 0; h < data.data[s].schedule_student.length; h++) {
              var id_day = h + 1;
              if (id_day != 6) {
                if (data.data[s].schedule_student[h].schedule != undefined) {
                  schendule_ar =
                    data.data[s].schedule_student[h].schedule.split(":");
                  schendule = schendule_ar[0] + ":" + schendule_ar[1];

                  html +=
                    '<td class="td-edit-day-schendule schStudent' +
                    id_student +
                    '" id="td-schendule_day' +
                    id_day +
                    "_student" +
                    id_student +
                    '" data-id-day="' +
                    id_day +
                    '" data-id-student="' +
                    data.data[s].id_student +
                    '" data-student-code="' +
                    data.data[s].student_code +
                    '" contenteditable="true">' +
                    schendule +
                    "</td>";
                } else {
                  html +=
                    '<td class="td-edit-day-schendule schStudent' +
                    id_student +
                    '" id="td-schendule_day' +
                    id_day +
                    "_student" +
                    id_student +
                    '" data-id-day="' +
                    id_day +
                    '" data-id-student="' +
                    data.data[s].id_student +
                    '" data-student-code="' +
                    data.data[s].student_code +
                    '" contenteditable="true"></td>';
                }
              } else {
                html +=
                  '<td class="td-edit-day-schendule" style="background-color: rgba(87, 87, 87,0.3)">N/A</td>';
              }
            }
            html += "</tr>";
          }
          $("#accordionAlumno").empty().append(html);

          } else {
         
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
    var hora = $(this).text();
    var id_student = $(this).attr("data-id-student");
    var id_day = $(this).attr("data-id-day");
    var student_code = $(this).attr("data-student-code");

    if (hora.length > 1 || hora.length == 0) {
      $(this).html(
        ' <input type="time" class="time_day" data-id_day="' +
          id_day +
          '" data-id-student="' +
          id_student +
          '" data-student-code="' +
          student_code +
          '" id="time_day' +
          id_day +
          "_student" +
          id_student +
          '" value="' +
          hora +
          '"></input>'
      );
      /* $(this).append(' <button type="button" class="btn btn-primary btn-sm">Small button</button>'); */
    } else {
      var hora = $("#time_day" + id_day + "_student" + id_student).val();
      $("#time_day" + id_day + "_student" + id_student).val(hora);
    }
  });

  $(document).on("focusout", ".time_day", function () {
    //--- --- ---//
    var id_student = $(this).attr("data-id-student");
    var id_day = $(this).attr("data-id_day");
    var student_code = $(this).attr("data-student-code");

    var hora = $(this).val();

    $("#td-schendule_day" + id_day + "_student" + id_student)
      .empty()
      .text(hora);
    //--- --- ---//
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "updateStudentSchendule",
        schendule: hora,
        id_student: id_student,
        id_day: id_day,
        student_code: student_code,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);

        if (data.response == true) {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        } else {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        Swal.close();
        var myToast = Toastify({
          text: data.message,
          duration: 3000,
        });
        myToast.showToast();
      });
  });
  /* $(document).on("change", ".time_day", function () {
    //--- --- ---//
    var id_student = $(this).attr("data-id-student");
    var id_day = $(this).attr("data-id_day");
    var student_code = $(this).attr("data-student-code");

    var hora = $(this).val();
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "updateStudentSchendule",
        schendule: hora,
        id_student: id_student,
        id_day: id_day,
        student_code: student_code,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);

        if (data.response == true) {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        } else {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        Swal.close();
        var myToast = Toastify({
          text: data.message,
          duration: 3000,
        });
        myToast.showToast();
      });
  }); */

  $(document).on("focusout", ".generalSchStudent", function () {
    //--- --- ---//
    var id_student = $(this).attr("data-id-student");
    var student_code = $(this).attr("data-student-code");

    var hora = $(this).val();

    $(".schStudent" + id_student)
      .empty()
      .text(hora);
    //--- --- ---//
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "updateStudentSchenduleGeneral",
        schendule: hora,
        id_student: id_student,
        student_code: student_code,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);

        if (data.response == true) {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        } else {
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        Swal.close();
        var myToast = Toastify({
          text: data.message,
          duration: 3000,
        });
        myToast.showToast();
      });
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
