<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    function returnMessage($message, $status='success')
    {
        $data['message'] = $message;
        if ( $status == 'error') $data['error'] = $message;
        returnJson( $data, $status );
    }    
    
    function returnError($error)
    {
        if ( is_array($error) )
            $error = implode(',', $error);
        $data['error'] = $error;
        $data['message'] = $error;
        returnJson( $data, 'error' );
    }
    
    function returnJson($data, $status = 'success'){
        $data['status'] = $status;    
        $result = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo $result;
        exit();
    }