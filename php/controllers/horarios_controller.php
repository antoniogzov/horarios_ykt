<?php
include_once dirname(__DIR__ . '', 1) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function getStudentsActiveByFamily()
{
    $id_family = $_POST['id_family'];

    $queries = new Queries;

    $stmt = "SELECT std.*, gps.group_code, UPPER(fam.family_name) AS family_name,
   UPPER( CONCAT
            (addr.street,' ',
            addr.ext_number,
                (CASE
                WHEN addr.int_number IS NOT NULL THEN CONCAT(' Int: ', addr.int_number)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.colony IS NOT NULL THEN CONCAT(', ', addr.colony)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.delegation IS NOT NULL THEN CONCAT(', ', addr.delegation)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.postal_code IS NOT NULL THEN CONCAT(', ', addr.postal_code)
                ELSE ''
                END
                )
             )
            )
        AS family_address
    FROM school_control_ykt.students  AS std
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = std.group_id
    INNER JOIN families_ykt.families AS fam  ON fam.id_family = std.id_family
    INNER JOIN families_ykt.addresses_families AS addr  ON fam.id_family = addr.id_family
    WHERE std.id_family = $id_family AND std.status = '1'
    ORDER BY gps.id_level_grade ASC";

    //echo $stmt;

    $getInfoRequest = $queries->getData($stmt);
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getInfoRequest)) {

        for ($s = 0; $s < count($getInfoRequest); $s++) {
            $student_schendules = array();

            $student_code = $getInfoRequest[$s]->student_code;

            for ($d = 1; $d <= 7; $d++) {

                $sql_search_student_schendule = "SELECT * FROM transport.service_schedules
            WHERE student_code = '$student_code' AND id_day = '$d'";
                $getSSchendule =  $queries->getData($sql_search_student_schendule);
                if (!empty($getSSchendule)) {
                    $student_schendules[] = $getSSchendule[0];
                } else {
                    $student_schendules[] = [];
                }

                $getInfoRequest[$s]->schedule_student = $student_schendules;
            }
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getInfoRequest
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getTrustedContactsFamily()
{
    $id_family = $_POST['id_family'];

    $queries = new Queries;

    $stmt = "SELECT *,
     UPPER( CONCAT
            (street,' ',
            external_number,
                (CASE
                WHEN internal_number IS NOT NULL THEN CONCAT(' Int: ', internal_number)
                ELSE ''
                END
                ),
                (CASE
                WHEN colony IS NOT NULL THEN CONCAT(', ', colony)
                ELSE ''
                END
                ),
                (CASE
                WHEN delegation IS NOT NULL THEN CONCAT(', ', delegation)
                ELSE ''
                END
                ),
                (CASE
                WHEN postal_code IS NOT NULL THEN CONCAT(', C.P. ', postal_code)
                ELSE ''
                END
                )
             )
            )
        AS family_address
    FROM families_ykt.trusted_contacts  
    WHERE id_family = $id_family";

    //echo $stmt;

    $getInfoRequest = $queries->getData($stmt);
    //$last_id = $getInfoRequest['last_id'];

    $html = '';
    if (count($getInfoRequest) == 0) {
        $html .= '<div class="card" id="newTrusted">
        <div class="card-body">
          <h5 class="card-title">Agregar contacto</h5>
          <br>
          <button type="button" class="btn btn-sm btn-primary addNewTrusted" data-id="' . $id_family . '"><i class="fas fa-plus"></i></button>
        </div>
      </div><br>';

      $data = array(
        'response' => true,
        'html'                => $html
    );
    } else {
        if (!empty($getInfoRequest)) {
            $html = '';
            for ($s = 0; $s < count($getInfoRequest); $s++) {

                $html .= '<div class="card" id="card' . $getInfoRequest[$s]->trusted_contact_id . '">
                <div class="card-body">
                  <h5 class="card-title">' . $getInfoRequest[$s]->contact_full_name . '</h5>
                  <h6 class="card-subtitle mb-2 text-body-secondary">' . $getInfoRequest[$s]->relationship . '</h6>
                  <p class="card-text">' . $getInfoRequest[$s]->family_address . '</p>
                  <a href="#" class="card-link">' . $getInfoRequest[$s]->cell_phone . '</a>
                  <br>
                  <button type="button" class="btn btn-sm btn-primary editContactInfo" data-id="' . $getInfoRequest[$s]->trusted_contact_id . '"><i class="fas fa-edit"></i></button>
                </div>
              </div><br>';
            }

            if (count($getInfoRequest) < 4) {
                $html .= '<div class="card" id="newTrusted">
            <div class="card-body">
              <h5 class="card-title">Agregar contacto</h5>
              <br>
              <button type="button" class="btn btn-sm btn-primary addNewTrusted" data-id="' . $id_family . '"><i class="fas fa-plus"></i></button>
            </div>
          </div><br>';
            }

            //--- --- ---//
            $data = array(
                'response' => true,
                'html'                => $html
            );
            //--- --- ---//
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'message'                => ''
            );
            //--- --- ---//
        }
    }


    echo json_encode($data);
}

function getTrustedContactsFamilyForm()
{
    $id_contact = $_POST['id_contact'];

    $queries = new Queries;

    $stmt = "SELECT *,
     UPPER( CONCAT
            (street,' ',
            external_number,
                (CASE
                WHEN internal_number IS NOT NULL THEN CONCAT(' Int: ', internal_number)
                ELSE ''
                END
                ),
                (CASE
                WHEN colony IS NOT NULL THEN CONCAT(', ', colony)
                ELSE ''
                END
                ),
                (CASE
                WHEN delegation IS NOT NULL THEN CONCAT(', ', delegation)
                ELSE ''
                END
                ),
                (CASE
                WHEN postal_code IS NOT NULL THEN CONCAT(', C.P. ', postal_code)
                ELSE ''
                END
                )
             )
            )
        AS family_address
    FROM families_ykt.trusted_contacts  
    WHERE trusted_contact_id = $id_contact";

    //echo $stmt;

    $getInfoRequest = $queries->getData($stmt);

    $stmtFamilRel = "SELECT *
   FROM families_ykt.family_relationship";

    //echo $stmtFamilRel;

    $getInfoRequestRel = $queries->getData($stmtFamilRel);

    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getInfoRequest)) {
        $html = '';
        for ($s = 0; $s < count($getInfoRequest); $s++) {
            $options = '';

            $in_list = 0;
            for ($r = 0; $r < count($getInfoRequestRel); $r++) {
                if (mb_strtoupper($getInfoRequestRel[$r]->relationship_description) == mb_strtoupper($getInfoRequest[$s]->relationship)) {
                    $in_list = 1;
                    $options .= '<option selected value="' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '">' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '</option>';
                } else {
                    if ($getInfoRequestRel[$r]->id_family_relationship == 11 && !$in_list) {
                        $options .= '<option selected value="' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '">' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '</option>';
                    } else {
                        $options .= '<option value="' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '">' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '</option>';
                    }
                }
            }
            $manual_rel = '';
            if (!$in_list) {
                $manual_rel = '<label class="col-md-2 col-form-label form-control-label">
                
            </label><div class="col-md-10">
                <input type="text" name="manual_relationship" id="manual_relationship" placeholder="Parentesco" class="form-control" value="' . $getInfoRequest[$s]->relationship . '" required="">
            </div>';
            }


            $html .= '<div class="card" id="card' . $getInfoRequest[$s]->trusted_contact_id . '">
            <div class="card-body">
            <div class="card-body">
            <div class="card-header">
                <h3 class="mb-0">Contacto: ' . $getInfoRequest[$s]->contact_full_name . '</h3>
            </div>
            <form id="form-contact-' . $id_contact . '" class="mb-3">
                <div class="form-group row mt-4">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Nombre completo:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="contact_full_name" placeholder="Nombre completo" class="form-control" value="' . $getInfoRequest[$s]->contact_full_name . '" required="">
                    </div>
                </div>
                <div class="form-group row">
                <label class="col-md-2 col-form-label form-control-label">
                    * Parentesco:
                </label>
                <div class="col-md-10">
                <select id="relationship" name="relationship" required class="form-select" aria-label="Seleccione una opción">
                <option selected disabled>Seleccione una opción</option>
                ' . $options . '
                </select>
                </div>
                ' . $manual_rel . '
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Celular:
                    </label>
                    <div class="col-md-10">
                        <input type="number" name="cell_phone" placeholder="Celular" class="form-control" value="' . $getInfoRequest[$s]->cell_phone . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Calle:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="street" placeholder="Calle" class="form-control" value="' . $getInfoRequest[$s]->street . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * No. externo:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="external_number" placeholder="No. externo" class="form-control" value="' . $getInfoRequest[$s]->external_number . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * No. interno:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="internal_number" placeholder="No. interno" class="form-control" value="' . $getInfoRequest[$s]->internal_number . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Colonia:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="colony" placeholder="Colonia" class="form-control" value="' . $getInfoRequest[$s]->colony . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Delegación:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="delegation" placeholder="Delegación" class="form-control" value="' . $getInfoRequest[$s]->delegation . '" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Código postal:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="postal_code" placeholder="Código postal" class="form-control" value="' . $getInfoRequest[$s]->postal_code . '" required="">
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-success col mt-4" onclick="updateContact(' . $getInfoRequest[$s]->trusted_contact_id . ', ' . $getInfoRequest[$s]->id_family . ')">Actualizar</button>
        </div>
            </div>
          </div><br>';
        }

        //--- --- ---//
        $data = array(
            'response' => true,
            'html'                => $html
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getNewTrustedContactsFamilyForm()
{
    $id_family = $_POST['id_family'];

    $queries = new Queries;



    $stmtFamilRel = "SELECT *
   FROM families_ykt.family_relationship";

    //echo $stmtFamilRel;

    $getInfoRequestRel = $queries->getData($stmtFamilRel);
    $html = '';
    //$last_id = $getInfoRequest['last_id'];
    $options = '';

    if (!empty($getInfoRequestRel)) {
        for ($r = 0; $r < count($getInfoRequestRel); $r++) {
            $options .= '<option value="' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '">' . mb_strtoupper($getInfoRequestRel[$r]->relationship_description) . '</option>';
        }

        $html .= '<div class="card" id="cardNewTrusted">
            <div class="card-body">
            <div class="card-body">
            <div class="card-header">
                <h3 class="mb-0">Nuevo contacto</h3>
            </div>
            <form id="form-contact-1" class="mb-3">
                <div class="form-group row mt-4">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Nombre completo:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="contact_full_name" placeholder="Nombre completo" class="form-control" value="" required="">
                    </div>
                </div>
                <div class="form-group row">
                <label class="col-md-2 col-form-label form-control-label">
                    * Parentesco:
                </label>
                <div class="col-md-10">
                <select id="relationship" name="relationship" required class="form-select" aria-label="Seleccione una opción">
                <option selected disabled>Seleccione una opción</option>
                ' . $options . '
                </select>
                </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Celular:
                    </label>
                    <div class="col-md-10">
                        <input type="number" name="cell_phone" placeholder="Celular" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Calle:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="street" placeholder="Calle" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * No. externo:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="external_number" placeholder="No. externo" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * No. interno:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="internal_number" placeholder="No. interno" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Colonia:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="colony" placeholder="Colonia" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Delegación:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="delegation" placeholder="Delegación" class="form-control" value="" required="">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label form-control-label">
                        * Código postal:
                    </label>
                    <div class="col-md-10">
                        <input type="text" name="postal_code" placeholder="Código postal" class="form-control" value="" required="">
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-success col mt-4" onclick="addContacts(' . $id_family . ')">Guardar</button>
        </div>
            </div>
          </div><br>';

        //--- --- ---//
        $data = array(
            'response' => true,
            'html'                => $html
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}


function SaveNewContacts()
{
    $data_contact_1 = json_decode($_POST['obj_contact_1']);
    $id_family = $_POST['id_family'];

    $response = true;


    $queries = new Queries;
    //--- CONTACT 1 ---//

    $length_contact_1 = count((array)$data_contact_1);
    $count_contact_1 = 0;

    if ($length_contact_1 > 0) {
        $arr_values_contact_1 = array($id_family);
        $sql_contact_1 = 'INSERT INTO families_ykt.trusted_contacts (id_family, ';
        $sql_values_contact_1 = 'VALUES (?, ';
        foreach ($data_contact_1 as $key => $value) {
            $sql_contact_1 .= $key;
            $sql_values_contact_1 .= '?';
            if (($count_contact_1 + 1) < $length_contact_1) {
                $sql_contact_1 .= ', ';
                $sql_values_contact_1 .= ', ';
            }
            array_push($arr_values_contact_1, $value);
            $count_contact_1++;
        }

        $sql_contact_1 .= ')';
        $sql_values_contact_1 .= ')';
        $sql_contact_1 .= $sql_values_contact_1;

        if (!Queries::getInstance()->updateInfo($sql_contact_1, $arr_values_contact_1)) {
            $response = false;
        }
    }

    //--- GENERAL DATA ---//


    if ($response) {
        Queries::getInstance()->updateAdvance('secondary_contacts', $id_family);
    }

    $result = array('response' => $response);

    echo json_encode($result);
}

function UpdateContacts()
{
    $data_contact = json_decode($_POST['obj_contact_1']);
    $trusted_contact_id = $_POST['trusted_contact_id'];
    $id_family = $_POST['id_family'];

    $response = true;
    $queries = new Queries;

    //--- GENERAL DATA ---//
    $length_data_general = count((array)$data_contact);
    $count_data_general = 0;

    if ($length_data_general > 0) {
        $arr_values_data_general = array();
        $sql_data_general = 'UPDATE families_ykt.trusted_contacts SET ';
        foreach ($data_contact as $key => $value) {
            $sql_data_general .= $key . ' = ? ';
            if (($count_data_general + 1) < $length_data_general) {
                $sql_data_general .= ', ';
            }
            array_push($arr_values_data_general, $value);
            $count_data_general++;
        }

        $sql_data_general .= 'WHERE trusted_contact_id = ?';
        array_push($arr_values_data_general, $trusted_contact_id);

        if (!Queries::getInstance()->updateInfo($sql_data_general, $arr_values_data_general)) {
            $response = false;
        }
    }

    if ($response) {
        Queries::getInstance()->updateAdvance('secondary_contacts', $id_family);
    }

    $result = array('response' => $response);

    echo json_encode($result);
}
function getSchedulesByStudent()
{
    $id_student = $_POST['id_student'];

    $queries = new Queries;

    $stmt = "SELECT std.*, gps.group_code, UPPER(fam.family_name) AS family_name, UPPER(CONCAT(std.lastname,' ', std.name)) AS name_student,
    CONCAT
            (addr.street,' ',
            addr.ext_number,
                (CASE
                WHEN addr.int_number IS NOT NULL THEN CONCAT(' Int: ', addr.int_number)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.colony IS NOT NULL THEN CONCAT(', ', addr.colony)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.delegation IS NOT NULL THEN CONCAT(', ', addr.delegation)
                ELSE ''
                END
                ),
                (CASE
                WHEN addr.postal_code IS NOT NULL THEN CONCAT(', ', addr.postal_code)
                ELSE ''
                END
                )
             )
        AS family_address
    FROM school_control_ykt.students  AS std
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = std.group_id
    INNER JOIN families_ykt.families AS fam  ON fam.id_family = std.id_family
    INNER JOIN families_ykt.addresses_families AS addr  ON fam.id_family = addr.id_family
    WHERE std.id_student = $id_student AND std.status = '1'
    ORDER BY gps.id_level_grade ASC";

    $getInfoRequest = $queries->getData($stmt);
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getInfoRequest)) {

        for ($s = 0; $s < count($getInfoRequest); $s++) {
            $student_schendules = array();

            $student_code = $getInfoRequest[$s]->student_code;

            for ($d = 1; $d <= 7; $d++) {

                $sql_search_student_schendule = "SELECT * FROM transport.service_schedules
            WHERE student_code = '$student_code' AND id_day = '$d'";
                $getSSchendule =  $queries->getData($sql_search_student_schendule);
                if (!empty($getSSchendule)) {
                    $student_schendules[] = $getSSchendule[0];
                } else {
                    $student_schendules[] = [];
                }

                $getInfoRequest[$s]->schedule_student = $student_schendules;
            }
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getInfoRequest
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function updateStudentSchendule()
{
    $id_student = $_POST['id_student'];
    $id_day = $_POST['id_day'];
    $student_code = $_POST['student_code'];
    $schendule = $_POST['schendule'] . ":00";

    $queries = new Queries;

    $stmt = "SELECT *
    FROM transport.service_schedules
    WHERE student_code = '$student_code' AND id_day = '$id_day'";

    $getInfoRequest = $queries->getData($stmt);
    //$last_id = $getInfoRequest['last_id'];
    if (empty($getInfoRequest)) {

        $stmt = "INSERT INTO transport.service_schedules(
            id_student,
            student_code,
            id_day,
            schedule,
            search_order,
            id_type_service,
            id_route,
            route,
            prefect_name,
            service
            )
        VALUES (
            '$id_student', 
            '$student_code', 
            '$id_day',
            '$schendule', 
            '1',
            '1',
            '1',
            '-',
            '-',
            '-'
            )";

        if ($queries->insertData($stmt)) {
            $data = array(
                'response' => true,
                'data'                => $getInfoRequest,
                'message' => "Se reigstró correctamente el nuevo horario"
            );
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'message' => "Ocurrió un error al insertar el horario"
            );
        }
    } else {


        $stmt = "UPDATE transport.service_schedules SET schedule = '$schendule' 
        WHERE student_code = '$student_code' AND id_day = '$id_day'";
        if ($queries->insertData($stmt)) {
            $data = array(
                'response' => true,
                'data'                => $getInfoRequest,
                'message' => "Se actualizó correctamente el horario"
            );
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'message' => "Ocurrió un error al actualizar el horario"
            );
        }
    }

    echo json_encode($data);
}

function updateColab()
{
    $no_colaborador = $_POST['no_colaborador'];
    $value = $_POST['value'];

    $queries = new Queries;

    $stmt2 = "SELECT colab.*, UPPER(CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador)) AS colab_name
    FROM colaboradores_ykt.colaboradores AS colab
    WHERE no_colaborador = '$no_colaborador'";


    $stmt = "UPDATE colaboradores_ykt.relation_colaborator_ccostos SET routes_supervisor = $value WHERE no_colaborador = $no_colaborador";

    //$last_id = $getInfoRequest['last_id'];
    if ($queries->insertData($stmt)) {
        $getTeacherData = $queries->getData($stmt2);
        $data = array(
            'response' => true,
            'teacher_data' => $getTeacherData,
            'message' => "Proceso realizado con éxito!!"
        );
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message' => "Ocurrió un error en el proceso!!"
        );
    }


    echo json_encode($data);
}
function updateStudentSchenduleGeneral()
{
    $id_student = $_POST['id_student'];
    $student_code = $_POST['student_code'];
    $schendule = $_POST['schendule'] . ":00";

    $queries = new Queries;
    for ($id_day = 1; $id_day <= 7; $id_day++) {
        if ($id_day != 6) {
            $stmt = "SELECT *
            FROM transport.service_schedules
            WHERE student_code = '$student_code' AND id_day = '$id_day'";
            $getInfoRequest = $queries->getData($stmt);
            if (empty($getInfoRequest)) {

                $stmt = "INSERT INTO transport.service_schedules(
                        id_student,
                        student_code,
                        id_day,
                        schedule,
                        search_order,
                        id_type_service,
                        id_route,
                        route,
                        prefect_name,
                        service
                        )
                    VALUES (
                        '$id_student', 
                        '$student_code', 
                        '$id_day',
                        '$schendule', 
                        '1',
                        '1',
                        '1',
                        '-',
                        '-',
                        '-'
                        )";

                if ($queries->insertData($stmt)) {
                    $data = array(
                        'response' => true,
                        'data'                => $getInfoRequest,
                        'message' => "Se reigstró correctamente el nuevo horario"
                    );
                } else {
                    //--- --- ---//
                    $data = array(
                        'response' => false,
                        'message' => "Ocurrió un error al insertar el horario"
                    );
                }
            } else {


                $stmt = "UPDATE transport.service_schedules SET schedule = '$schendule' 
                    WHERE student_code = '$student_code' AND id_day = '$id_day'";
                if ($queries->insertData($stmt)) {
                    $data = array(
                        'response' => true,
                        'data'                => $getInfoRequest,
                        'message' => "Se actualizó correctamente el horario"
                    );
                } else {
                    //--- --- ---//
                    $data = array(
                        'response' => false,
                        'message' => "Ocurrió un error al actualizar el horario"
                    );
                }
            }
        }
    }


    echo json_encode($data);
}

/* function getProspectAddress()
{
    $id_student = $_POST['id_student'];

    $queries = new Queries;

    $stmt = "SELECT addr.* FROM prospectos.address AS addr
    INNER JOIN prospectos.students AS std  ON std.id_prospect = addr.id_prospect
     WHERE id_student='$id_student' LIMIT 1";

    $getInfoRequest = $queries->getData($stmt);
    //$last_id = $getInfoRequest['last_id'];
    if (!empty($getInfoRequest)) {
        $infoAddress = $getInfoRequest[0];
        
        $calle = $infoAddress->street;
        $num_ext = $infoAddress->n_exterior;
        $num_int = $infoAddress->n_interior;
        $colonia = $infoAddress->colony;
        $localidad = $infoAddress->delegation;
        $codigo_postal = $infoAddress->postal_code;
        $entre_calles = $infoAddress->between_streets;

        $html = '';

        <h1>Dirección de Familia</h1>
        <h3><strong>Calle: </strong> </h3>
        <h3><strong>N° Ext.: </strong> </h3>
        <h3><strong>N° Int.: </strong> </h3>
        <h3><strong>Colonia: </strong> </h3>
        <h3><strong>Municipio / Alcaldía: </strong> </h3>
        <h3><strong>Calle: </strong> </h3>
        <h3><strong>Calle: </strong> </h3>
        <h4><strong>Pertenece a: </strong> </h4>


        for ($s = 0; $s < count($getInfoRequest); $s++) {
            $student_schendules = array();

            $student_code = $getInfoRequest[$s]->student_code;

            for ($d = 1; $d <= 7; $d++) {

                $sql_search_student_schendule = "SELECT * FROM transport.service_schedules
            WHERE student_code = '$student_code' AND id_day = '$d'";
                $getSSchendule =  $queries->getData($sql_search_student_schendule);
                if (!empty($getSSchendule)) {
                    $student_schendules[] = $getSSchendule[0];
                } else {
                    $student_schendules[] = [];
                }

                $getInfoRequest[$s]->schedule_student = $student_schendules;
            }
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getInfoRequest
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
} */
