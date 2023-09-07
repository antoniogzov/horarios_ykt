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
