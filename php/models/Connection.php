<?php
class data_conn
{
    /* private $db_name = "iteach_academic";
    private $db_port = 3306;
    private $db_user = "root";
    private $db_pass = "";
    private $db_host = "localhost";


      private $db_user = "developer";
    private $db_pass = "Ykt2021a";
    private $db_host = "192.168.6.2:3307";


    private $db_user = "usuario";
    private $db_pass = "UsuarioRemoto2020";
    private $db_host = "192.168.6.2";

    private $db_conn; */

    private $db_name = "iteach_grades_quantitatives";
    private $db_port = 3306;
    private $db_user = "administrator";
    private $db_pass = 'aZ9dH3!sV8xR2$kL7wG';
    private $db_host = "database-ykt-1.c1uoa8yag1kb.us-west-1.rds.amazonaws.com";

    private $db_conn;

    public function dbConn()
    {
        try {
            $this->db_conn = new PDO("mysql:host={$this->db_host}; dbname={$this->db_name}; charset=utf8", $this->db_user, $this->db_pass);
            $this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected";
        } catch (PDOException $e) {
            echo "ERROR" . $e->getMessage();
        }
        return $this->db_conn;
    }
}

class Connect
{
    private $serverDB = "192.168.6.2";
    private $userDB = "usuario";
    private $nameDB = "familias_ykt";
    private $passDB = "UsuarioRemoto2020";

    /* private $serverDB = "localhost";
        private $userDB = "root";
        private $nameDB = "familias_ykt";
        private $passDB = ""; */

    public function doConnection()
    {
        $connection = mysqli_connect(
            $this->serverDB,
            $this->userDB,
            $this->passDB,
            $this->nameDB
        );
        $connection->set_charset("utf8");
        return $connection;
    }
}
