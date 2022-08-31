<?php

class Horarios
{
    public function getAllFamilies()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT fam.*
        FROM families_ykt.families AS fam
        INNER JOIN school_control_ykt.students AS stud 
            ON stud.id_student = (SELECT id_student FROM school_control_ykt.students AS stud WHERE stud.id_family = fam.id_family AND stud.status = 1 LIMIT 1)
        ORDER BY family_name ASC
        ";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }
}