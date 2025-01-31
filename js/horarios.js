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
            html += "<td>" + student_code.toUpperCase() + "</td>";
            html += "<td>" + student_name.toUpperCase() + "</td>";
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
  $(document).on("click", ".btnTrustedInfo", function () {
    var id_family = $(this).attr("data-id-family");
    var family_name = $(this).closest('tr').find("td:eq(0)").text();


    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "getTrustedContactsFamily",
        id_family: id_family
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        
        if (data.response == true) {
          $("#additionalContactInfoModalLabel").text("DIRECTORIO DE FAMILIA "+family_name);
          $("#conctactInfoDiv").html(data.html);
          Swal.close();
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
  $(document).on("click", ".addNewTrusted", function () {
    var id_family = $(this).attr("data-id");
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "getNewTrustedContactsFamilyForm",
        id_family: id_family,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        
        if (data.response == true) {
          console.log("here");
          $("#newTrusted").html(data.html);
          Swal.close();
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
  
  $(document).on("click", ".editContactInfo", function () {
    var id_contact = $(this).attr("data-id");
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "getTrustedContactsFamilyForm",
        id_contact: id_contact,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        
        if (data.response == true) {
          $("#card" + id_contact).html(data.html);
          Swal.close();
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
  $(document).on("change", "#relationship", function () {
    var id_relationship = $(this).val();
    if (id_relationship == 'OTRO') {
      $(this)
        .closest("div")
        .append(
          '<label class="col-md-2 col-form-label form-control-label"></label><div class="col-md-10"><input type="text" name="manual_relationship" id="manual_relationship" placeholder="Parentezco" class="form-control" value="" required=""></div>'
        );
    } else {
      $("#manual_relationship").closest("div").remove();
    }
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

  $(document).on("click", ".saveNewRouteTeacherRel", function () {
    var no_colaborador = $("#select_colaborator option:selected").val();
    var value = 1;
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "updateColab",
        no_colaborador: no_colaborador,
        value: value,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var no_colab = data.teacher_data[0].no_colaborador;
          var mail = data.teacher_data[0].correo_institucional;
          var contrasena_general = data.teacher_data[0].contrasena_general;
          var colab_name = data.teacher_data[0].colab_name;

          html = "";
          html += "<tr>";
          html += '<th title="' + no_colaborador + '" >' + colab_name + "</td>";
          html += '<td scope="row">' + mail + "</td>";
          html += '<td scope="row">' + contrasena_general + "</td>";
          html += "<td>";
          html +=
            '    <button data-no-colaborador="' +
            no_colab +
            '"  class="btn btn-danger updateColab" type="submit">';
          html +=
            '        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">';
          html +=
            '            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />';
          html +=
            '            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />';
          html += "        </svg>";
          html += "    </button>";
          html += "</td>";
          html += "</tr>";
          $("#tablaColabTransp tr:last").after(html);
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

  $(document).on("click", ".updateColab", function () {
    var no_colaborador = $(this).attr("data-no-colaborador");
    $(this).closest("tr").remove();
    var value = 0;
    loading();
    $.ajax({
      url: "php/controllers/horarios_controller.php",
      method: "POST",
      data: {
        mod: "updateColab",
        no_colaborador: no_colaborador,
        value: value,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
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

  $(document).on("click", ".btnFamilyAddress", function () {
    Swal.fire({
      icon: "info",
      html: "<strong>HOLA</strong>",
    });

    /*  var id_student = $(this).attr("data-id-student");
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
      }); */
  });
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

  
  $('#select_colaborator').select2();

});

function addContacts(id_family) {
  //--- CONTACTO 1 ---//
  var form_data_contact_1 = document.querySelector('#form-contact-1');
  var elements_contact_1 = form_data_contact_1.elements;
  //--- --- ---//
  let data_contact_1_complete = true;
  let obj_contact_1 = {};
  Array.from(elements_contact_1).forEach(element => {
    if (element.id == 'relationship' && element.value == 'OTRO') {
      element = $('#manual_relationship');
  }
      if (!element.disabled) {
          if (element.required) {
              if (element.value == '' || element.value == null) {
                  data_contact_1_complete = false;
                  element.classList.add('is-invalid');
                  element.focus();
              }
          }
          //--- --- ---//
          var el_name = element.name;
          if (element.id == 'manual_relationship') {
            el_name = 'relationship';
          }
          obj_contact_1[el_name] = element.value;
      }
  });
  //--- --- ---//
  if (data_contact_1_complete) {
      saveContactsDB(obj_contact_1, id_family);
  }
}

function updateContact(trusted_contact_id, id_family) {
  //--- CONTACTO ---//
  var form_data_contact_1 = document.querySelector('#form-contact-' + trusted_contact_id);
  var elements_contact_1 = form_data_contact_1.elements;
  //--- --- ---//
  let data_contact_1_complete = true;
  let obj_contact_1 = {};
  Array.from(elements_contact_1).forEach(element => {

    if (element.id == 'relationship' && element.value == 'OTRO') {
        element = $('#manual_relationship');
    }
      if (!element.disabled) {
          if (element.required) {
              if (element.value == '' || element.value == null) {
                  data_contact_1_complete = false;
                  element.classList.add('is-invalid');
                  element.focus();
              }
          }
          //--- --- ---//
          
          let valueElement = element.value;
          var el_name = element.name;
          if (element.id == 'manual_relationship') {
            el_name = 'relationship';
          }
          obj_contact_1[el_name] = valueElement;
      }
  });
  //--- --- ---//
  if (data_contact_1_complete) {
    
      updateContactsDB(obj_contact_1, trusted_contact_id, id_family);
  }
  //--- --- ---//
}
function saveContactsDB(obj_contact_1, id_family) {
  loading();
  const data = new FormData();
  data.append('mod', 'SaveNewContacts');
  data.append('obj_contact_1', JSON.stringify(obj_contact_1));
  data.append('id_family', id_family);
  fetch('php/controllers/horarios_controller.php', {
      method: 'POST',
      body: data
  }).then(function(response) {
      if (response.ok) {
          return response.json()
      } else {
          console.log(response);
          Swal.fire('Error', 'Ocurrió un error al intentar conectarse a la base de datos :[', 'error');
          throw new "Error en la llamada Ajax";
      }
  }).then(function(data) {
      if (data.response) {
          Swal.fire({
              title: 'Listo',
              text: 'Se actualizaron correctamente los datos',
              icon: 'success'
          }).then((result) => {
            loading();
              window.location.reload();
          })
          //Swal.fire('Listo!', 'Se actualizaron correctamente los datos', 'success');
      }
  }).catch(function(err) {
      Swal.fire('Atención!', 'Ocurrió un error al intentar procesar su petición, intento nuevamente porfavor', 'info');
      console.log(err);
  });
}
function updateContactsDB(obj_contact_1, trusted_contact_id, id_family) {
  loading();
  const data = new FormData();
  data.append('mod', 'UpdateContacts');
  data.append('obj_contact_1', JSON.stringify(obj_contact_1));
  data.append('trusted_contact_id', trusted_contact_id);
  data.append('id_family', id_family);
  
  fetch('php/controllers/horarios_controller.php', {
      method: 'POST',
      body: data
  }).then(function(response) {
      if (response.ok) {
          return response.json()
      } else {
          console.log(response);
          Swal.fire('Error', 'Ocurrió un error al intentar conectarse a la base de datos :[', 'error');
          throw new "Error en la llamada Ajax";
      }
  }).then(function(data) {
      if (data.response) {
          Swal.fire('Listo!', 'Se actualizaron correctamente los datos', 'success');
          $(".closeTrustContact").trigger('click');
          $("#btnTrustedInfo").trigger('click');
      }
  })
  /*.catch(function(err) {
          Swal.fire('Atención!', 'Ocurrió un error al intentar procesar su petición, intento nuevamente porfavor', 'info');
          console.log(err);
      })*/
  ;
}

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
