<?php

class Horarios
{
    public function getAllFamilies()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT fam.*, fam.status AS status_familia
        FROM families_ykt.families AS fam
        INNER JOIN school_control_ykt.students AS stud 
            ON stud.id_student = (SELECT id_student FROM school_control_ykt.students AS stud WHERE stud.id_family = fam.id_family AND stud.status = 1 LIMIT 1)
        ORDER BY family_name ASC
        ";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }
    public function getAllStudents()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT stud.*, CONCAT(stud.lastname,' ', stud.name) AS name_student, gps.group_code, fam.status AS status_familia,
        CASE 
            WHEN sch.id_service_schedules IS NULL THEN 'ffa3a3' 
            ELSE ''
        END
        AS color_html
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        INNER JOIN families_ykt.families AS fam  ON fam.id_family = stud.id_family
        LEFT JOIN transport.service_schedules AS sch ON sch.id_service_schedules = (SELECT id_service_schedules FROM transport.service_schedules WHERE id_student = stud.id_student LIMIT 1)
        WHERE  stud.status = 1
        ORDER BY name_student ASC";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }

    public function getAllPrefectas()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT colab.no_colaborador, colab.no_colaborador, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS colab_name,
        contrasena_general, correo_institucional
        FROM colaboradores_ykt.colaboradores AS colab
        INNER JOIN colaboradores_ykt.relation_colaborator_ccostos AS rcc ON rcc.no_colaborador = colab.no_colaborador
        WHERE  colab.status = 1 AND rcc.routes_supervisor = 1
        ORDER BY colab_name  ASC";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }


    public function getAllColabs()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT DISTINCT colab.no_colaborador,
        UPPER(CONCAT(colab.no_colaborador, ' | ', colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador)) AS colab_name,
        contrasena_general, correo_institucional
        FROM colaboradores_ykt.colaboradores AS colab
        LEFT JOIN colaboradores_ykt.relation_colaborator_ccostos AS rcc ON rcc.no_colaborador = colab.no_colaborador
        WHERE  colab.status = 1 AND rcc.routes_supervisor != 1 AND id_area_colaborador = 5
        ORDER BY colab_name  ASC";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }

    
    public function getAllStudentsAdmisions()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT 
        t1.id_student, 
        CONCAT(t1.lastname,' ',t1.name) as 'name_student',
        t1.curp as 'curp', 
        t1.birthday as 'birthday', 
        t1.id_campus,
        t1.gender,
        t1.age,
        t1.iTeach_code,
        t1.ciclo_escolar,
        t1.campus_name as 'campus',
        t1.id_level,
        t1.date_in, 
        t1.degree as 'academic_grade',
        t1.id_status,
        t2.estado,
        t2.class 
        FROM 
        prospectos.students as t1 
        INNER JOIN 
        prospectos.status as t2 on t1.id_status = t2.id_status
        ORDER BY id_student DESC";


        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }

    public function getAllStudentAddress($id_student)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT addr.* FROM prospectos.address AS addr
        INNER JOIN prospectos.students AS std  ON std.id_prospect = addr.id_prospect
         WHERE id_student='$id_student' LIMIT 1";

        $getAddress = $queries->getData($sql_sites);
        if (!empty($getAddress)) {
            $infoAddress = $getAddress[0];

            $calle = $infoAddress->street;
            $num_ext = $infoAddress->n_exterior;
            $num_int = $infoAddress->n_interior;
            $colonia = $infoAddress->colony;
            $localidad = $infoAddress->delegation;
            $codigo_postal = $infoAddress->postal_code;
            $entre_calles = $infoAddress->between_streets;
            if ($num_int == 0 || $num_int == '') {
                $num_int = '';
            } else {
                $num_int =  ', int. ' . $num_int . ', ';
            }
            $direccion ="<strong>". $calle . ' ' . $num_ext . $num_int . ', ' . $colonia . '. </strong> <br> Entre calles: ' . $entre_calles;




            return ($direccion);
        } else {
            $direccion = "No se encontró una dirección para este registro";
            return ($direccion);
        }
    }
}
