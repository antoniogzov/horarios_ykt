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
        $sql_sites = "SELECT stud.*, CONCAT(stud.lastname,' ', stud.name) AS name_student, gps.group_code, fam.status AS status_familia
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        INNER JOIN families_ykt.families AS fam  ON fam.id_family = stud.id_family
        WHERE  stud.status = 1
        ORDER BY name_student ASC";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }
}
