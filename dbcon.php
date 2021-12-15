<?php
class Database{
    
    private $host = 'localhost'; #아이피
    private $db_name = 'view_matzip';  # DATABASE 이름
    private $username = 'pub_user'; # MySQL 계정 아이디
    private $password = 'root1234!'; # MySQL 계정 패스워드
    public $conn;
 
    public function getConnection(){
	$this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
	mysqli_query($this->conn,'SET NAMES utf8');

        return $this->conn;
    }
}
?>
