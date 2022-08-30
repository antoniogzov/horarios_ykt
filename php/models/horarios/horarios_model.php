<?php

class Horarios
{
    public function getAllFamilies()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_sites = "SELECT fam.*
        FROM families_ykt.families AS fam
        ORDER BY family_name ASC
        LIMIT 10
        ";

        $getSites = $queries->getData($sql_sites);

        return ($getSites);
    }
}