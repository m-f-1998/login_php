<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/dbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!verifyRequiredParams(array('username', 'password', 'email', 'name'))) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $name = $_POST['name'];
 
        $db = new DbOperation();
 
        $result = $db->createUser($username, $password, $email, $name);
 
        if ($result == USER_CREATED) {
            
            $response['error'] = false;
            $response['message'] = 'User Created Successfully';
            
        } elseif ($result == USER_ALREADY_EXIST) {
            
            $response['error'] = true;
            $response['message'] = 'User Already Exist';
            
        } elseif ($result == USER_NOT_CREATED) {
            
            $response['error'] = true;
            $response['message'] = 'Some Error Occurred';
            
        }
        
    } else {
        
        $response['error'] = true;
        $response['message'] = 'All Fields are Required to Register';
        
    }
    
} else {
    
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
    
}
 
function verifyRequiredParams($required_fields) {
 
    $request_params = $_REQUEST;
 
    foreach ($required_fields as $field) {
        
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) { 
            
            return true;
            
        }
        
    }
    
    return false;
    
}
 
echo json_encode($response);

?>