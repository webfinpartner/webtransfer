<?php


require_once( "hybridauth/Hybrid/Auth.php" );


class HAuth extends Hybrid_Auth
{
    public function __construct($config = FALSE ) {
        if($config === FALSE){
            $config = APPPATH. 'config/social_config.php';            
        }
        
        parent::__construct($config);
    }       
    
    private function _getAuthenticate($authenticate){
        try{
           return $this->authenticate( $authenticate);

        }catch( Exception $e ){
            echo "Ooophs, we got an error: " . $e->getMessage();
        }
    }




    public function getOK(){
        return $this->_getAuthenticate('Odnoklassniki');
    }
    
    
    public function isAuthOk(){
        return $this->isConnectedWith( 'Odnoklassniki' );
    }
    
    
    public function getGoogle(){
        return $this->_getAuthenticate('Google');
    }
    
    
    public function isAuthGoogle(){
        return $this->isConnectedWith( 'Google' );
    }
    
    
    
    public function getTwitter(){
        return $this->_getAuthenticate('Twitter');
    }
    
    
    public function isAuthTwitter(){
        return $this->isConnectedWith( 'Twitter' );
    }
    
    
    public function getFacebook(){
        return $this->_getAuthenticate('Facebook');
    }
    
    
    public function isAuthFacebook(){
        return $this->isConnectedWith( 'Facebook' );
    }
    
    
    
    public function getVkontakte(){
        return $this->_getAuthenticate('Vkontakte');
    }
    
    
    public function isAuthVkontakte(){
        return $this->isConnectedWith('Vkontakte');
    }
    
    
    public function getMailru(){
        return $this->_getAuthenticate('Mailru');
    }
    
    
    public function isAuthMailru(){
        return $this->isConnectedWith('Mailru');
    }


   
}