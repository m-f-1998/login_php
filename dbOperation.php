<?php

class dbOperation {
    
    private $conn;
 
    function __construct () {
        
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/constants.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/dbConnect.php';
        
        $db = new DbConnect ();
        $this->conn = $db->connect ();
        
    }
 
    public function userLogin ($username, $pass) {
                
        $stmt = $this->conn->prepare ("SELECT password FROM userLogins WHERE username = ?;");
        $stmt->bind_param ("s", $username);
        $stmt->execute ();
        $stmt->bind_result ($password);
        $stmt->fetch();
        
        return password_verify($pass, $password);
    
    }

    public function getUserByUsername ($username) {
        
        $stmt = $this->conn->prepare ("SELECT id, username, email, fullName FROM userLogins WHERE username = ?");
        $stmt->bind_param ("s", $username);
        $stmt->execute();
        $stmt->bind_result ($id, $uname, $email, $name);
        $stmt->fetch();
        
        $user = array();
        $user['id'] = $id;
        $user['username'] = $uname;
        $user['email'] = $email;
        $user['fullName'] = $name;
        
        return $user;
        
    }
 
    public function createUser ($username, $pass, $email, $name) {
        
        if (!$this->isUserExist ($username, $email)) {
            
            $password = password_hash($pass, PASSWORD_DEFAULT);
            
            $stmt = $this->conn->prepare ("INSERT INTO userLogins (username, password, email, fullName) VALUES (?, ?, ?, ?);");
            $stmt->bind_param ("ssss", $username, $password, $email, $name) ;
            
            if ($stmt->execute ()) {
                
                sendEmail ($username, $email, $name) ;
                return USER_CREATED;
                
            } else {
                
                return USER_NOT_CREATED;
                
            }
            
        } else {
            
            return USER_ALREADY_EXIST;
            
        }
        
    }
    
    public function sendEmail ($username, $email, $name) {

        $msg = "";

        $msg = wordwrap($msg,70);

        mail($email,"",$msg);
        
    }
 
 
    private function isUserExist ($username, $email) {
        
        $stmt = $this->conn->prepare("SELECT id FROM userLogins WHERE username = ? OR email = ?;");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        return $stmt->num_rows > 0;
        
    }
 
}

?>