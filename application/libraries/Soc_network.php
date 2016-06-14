<? if(!defined('BASEPATH')) exit('No direct script access allowed');

class Soc_network
{
    const SECRET_KEY    = '';
    const URL           = 'https://wtsocial.net';
    const CONTENT_TYPE  = 'Content-type: application/json';
    const AUTH_TOKEN    = 'Authorization: Token token=""';
    const LOG_FILE_DIR  = 'log_api.txt';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_GET    = 'GET';

    public $log = [];

	public function  __construct() {
		$this->ci = &get_instance();
	}

	public function saveSoc($user, $login, $pass)
    {
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'email'             => $user->email,
            'encrypted_email'   => $login,
            'auth_token'        => md5($pass),
            'first_name'        => $user->name,
            'last_name'         => $user->sername,
            'id'                => $user->id_user,
            'phone'             => $user->phone,
            'birthday'          => '2002-12-31',
            'sponsor_id'        => 9, //TODO:id
            'friends'           => "[96013991,90837257,500150,500113]",
        ];
        return $this->processSend(self::URL . '/api/users/' , self::METHOD_POST, $data);
	}

    public function updateSoc($user, $login, $pass, $social_id)
    {
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'email'             => $user->email,
            'encrypted_email'   => $login,
            'auth_token'        => md5($pass),
            'first_name'        => $user->name,
            'last_name'         => $user->sername,
            'id'                => $user->id_user,
        ];
        return $this->processSend(self::URL . '/api/users/' . $user->id_user . '/', self::METHOD_PUT, $data);
    }

    public function get_network_id($webtransfer_id) // получить все из соц сети о пользователе
    {
        return $this->processSend(self::URL . '/api/users/' . $webtransfer_id, self::METHOD_GET);
    }

    public function messages($social_id)
    {
       //$social_id =  92156962;
        $data = [
            //'secret_key'        => self::SECRET_KEY,
            'user_id'                => $social_id,
        ];
        return $this->processSend(self::URL . '/api/messages?' . http_build_query($data), self::METHOD_GET);
    }


    public function accept_friendship($friend_id, $webtransfer_id)
    {
         //$webtransfer_id =  92156962;
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'user_id'                => $webtransfer_id,
            'friend_id'                => $friend_id,
        ];
        return $this->processSend(self::URL . '/api/friends/accept_friendship',  self::METHOD_POST, $data);

    }

    public function deny_friendship($friend_id, $webtransfer_id)
    {
        //$webtransfer_id =  92156962;
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'user_id'                => $webtransfer_id,
            'friend_id'                => $friend_id,
        ];
        return $this->processSend(self::URL . '/api/friends/deny_friendship',  self::METHOD_POST, $data);

    }





    public function pending_friends($social_id)
    {
        //$social_id =  92156962;
        $data = [
           // 'secret_key'        => self::SECRET_KEY,
           'user_id'                => $social_id,
        ];
        return $this->processSend(self::URL . '/api/friends/pending?' . http_build_query($data), self::METHOD_GET);
    }


    public function friends($social_id)
    {
        //$social_id =  92156962;
        $data = [
           // 'secret_key'        => self::SECRET_KEY,
           'user_id'                => $social_id,
        ];
        return $this->processSend(self::URL . '/api/friends?' . http_build_query($data), self::METHOD_GET);
    }

    public function notifications($social_id){
        $data = [
           // 'secret_key'        => self::SECRET_KEY,
           'user_id'                => $social_id,
        ];
        return $this->processSend(self::URL . '/api/notifications?' . http_build_query($data), self::METHOD_GET);


    }

    public function createMsg($id_from, $id_to, $body, $subject)
    {
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'id'                => $id_from,
            'to'                => $id_to,
            'body'              => (string)$body,
            'subject'           => (string)$subject,
        ];
        return $this->processSend(self::URL . '/api/messages/', self::METHOD_POST, $data);
    }

    public function checkMsg($social_id)
    {
        $data = [
            'secret_key'        => self::SECRET_KEY,
            'id'                => $social_id,
        ];
        return $this->processSend(self::URL . '/api/messages/unread_count_for?' . http_build_query($data), self::METHOD_GET);
    }

    public function processSend($url, $method, $data = false)
    {
        if($data)
            $post = json_encode($data);
        if($data)
            $this->log('REQUEST DATA', json_encode($data));
        $this->log('REQUEST URL', $url);
        $this->log('REQUEST METHOD', $method);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $http_header = [];
        $http_header[] = self::CONTENT_TYPE;
        if($method == self::METHOD_GET)
            $http_header[] = self::AUTH_TOKEN;
        $this->log('REQUEST HTTP HEADER', var_export($http_header, true));
        curl_setopt($ch, CURLOPT_HTTPHEADER,$http_header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if($data)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        $this->log('RESPONSE', $result);

        //var_dump([$url, $result,$data] );
        $this->logSend();
        curl_close($ch);
        $r = json_decode($result, true);
        if ( empty($r) ) $r = $result;
        return $r;
    }

    public function log($key, $value)
    {
        $this->log[$key] = $value;
    }

    public function logSend()
    {
		return;
        $content = '';

        foreach ($this->log as $key => $value)
        {
            $content .= $key;
            $content .= ': ';
            $content .= $value;
            $content .= "\n";
        }
        $content .= "TIME : " . date("Y-m-d H:i:s");
        $content .= "\n";
        $content .= "-------------------------------\n\n";
        file_put_contents(self::LOG_FILE_DIR, $content, FILE_APPEND | LOCK_EX);
        $this->log = [];
    }
}
