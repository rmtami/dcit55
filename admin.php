<?php
require_once 'db.php';

class Admin {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        session_start();
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM admins WHERE username = :username AND password = :password";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $password]);

        if ($stmt->rowCount() === 1) {
            $_SESSION['admin'] = $username;
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['admin']);
    }

    public function logout() {
        session_destroy();
    }

    public function create($username, $password, $role_id) {
        $check = $this->conn->prepare("SELECT id FROM admins WHERE username = :username");
        $check->execute(['username' => $username]);

        if ($check->rowCount() > 0) return "exists";

        $sql = "INSERT INTO admins (username, password, role_id) VALUES (:username, :password, :role_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['username' => $username, 'password' => $password, 'role_id' => $role_id]);
    }

    public function read() {
        $sql = "SELECT a.id, a.username, a.role_id, r.role_name 
                FROM admins a 
                LEFT JOIN roles r ON a.role_id = r.role_id";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoles() {
        $sql = "SELECT role_id, role_name FROM roles";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $username, $password, $role_id) {
        if (!empty($password)) {
            $sql = "UPDATE admins SET username = :username, password = :password, role_id = :role_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'username' => $username, 'password' => $password, 'role_id' => $role_id]);
        } else {
            $sql = "UPDATE admins SET username = :username, role_id = :role_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'username' => $username, 'role_id' => $role_id]);
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
