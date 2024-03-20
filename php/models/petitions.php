<?php
include_once 'Connection.php';
class Queries extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public static function getInstance()
    {
        // if (!self::$instance instanceof self) {
        //     self::$instance = new self();
        // }

        // return self::$instance;
        return new self();
    }
    public function getData($stmt)
    {
        $results = array();

        try {

            $query = $this->conn->query($stmt);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $query;
            var_dump($e->getMessage());
        }

        return $results;
    }
    public function InsertData($stmt)
    {
        $results = array();

        try {

            if ($this->conn->query($stmt)) {
                $last_id = $this->conn->lastInsertId();
                $results['status'] = 'success';
                $results['last_id'] = $last_id;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $stmt;
            var_dump($e->getMessage());
        }

        return $results;
    }

    public function updateInfo($sql, $values)
    {
        $result = false;

        $stmt = $this->conn->prepare($sql);
        if ($stmt->execute($values)) {
            $result = true;
        }

        return $result;
    }
    public function updateAdvance($column, $id_family)
    {

        $sql1 = "UPDATE campaigns.family_data_update_campaign SET $column=? WHERE id_family=?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([1, $id_family]);

        $this->checkFormCompleted($id_family);
    }

    public function checkStudentsCompleted($id_family)
    {

        $response = false;

        $stmt = $this->conn->prepare('SELECT prog.updated_data
                                FROM campaigns.student_progress_family_update AS prog
                                INNER JOIN school_control_ykt.students AS std ON prog.id_student = std.id_student
                                INNER JOIN families_ykt.families AS fml ON std.id_family = fml.id_family
                                WHERE fml.id_family = ?');
        $stmt->execute([$id_family]);

        $completed_form = true;
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            if ($row->updated_data == 0) {
                $completed_form = false;
            }
        }

        if ($completed_form) {
            $response = true;

            $sql1 = "UPDATE campaigns.family_data_update_campaign SET updated_students_data=? WHERE id_family=?";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([1, $id_family]);
        }

        $this->checkFormCompleted($id_family);

        return $response;
    }

    public function checkFormCompleted($id_family)
    {

        $select = $this->conn->prepare('SELECT 1 FROM campaigns.family_data_update_campaign WHERE updated_father_data = ? AND updated_mother_data = ? AND updated_students_data = ? AND secondary_contacts = ? AND id_family = ?');
        $select->execute([1, 1, 1, 1, $id_family]);
        if ($select->rowCount() > 0) {
            $today = date('Y-m-d H:i:s');
            $sql1 = "UPDATE campaigns.family_data_update_campaign SET finished_update_date=? WHERE id_family=?";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([$today, $id_family]);
        }
    }
}
