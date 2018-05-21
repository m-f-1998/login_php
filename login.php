<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/dbOperation.php';

$response = array ();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['username']) && isset($_POST['password'])) {

        $db = new DbOperation();

        if ($db->userLogin($_POST['username'], $_POST['password'])) {
            
            $response['error'] = false;
            $response['user'] = $db->getUserByUsername($_POST['username']);
            
        } else {
            
            $response['error'] = true;
            $response['message'] = 'Invalid Username or Password';
            
        }
        
    } else {
        
        $response['error'] = true;
        $response['message'] = 'All Fields are Required to Log In';
    
    }

} else {
    
    $response['error'] = true;
    $response['message'] = "Request Not Allowed";
    
}

echo json_encode($response);

?>