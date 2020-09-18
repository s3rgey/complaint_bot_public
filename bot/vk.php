<?php

class VK
{
    private $version = '5.92';
    private $url = 'https://api.vk.com/method/';
    private $token = '28e1c0e97e75dc24361893d16eb84efbb6b8346207d4d7142b27045ee0a26793b9905822f8b226dd6675e';
    private $key = 'a6e3036f';
    public $data = '';

    public function __construct() {
        $this->data = json_decode(file_get_contents('php://input'), true);
        if(isset($this->data['type']))
        {
            if ($this->data['type'] == 'confirmation') exit($this->key);
            else echo('ok');
        }
        else echo('ok');
    }

    public function call($method, $params = []) {
        $params['access_token'] = $this->token;
        $params['v'] = $this->version;

        $url = $this->url.$method.'?'.http_build_query($params);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($json, true);
        return $response['response'];
    }
    public function send($peer_id, $message, $attachments = []) {
        return $this->call('messages.send', [
            'random_id' => rand(),
            'peer_id' => $peer_id,
            'disable_mentions' => 1,
            'dont_parse_links' => 1,
            'message' => $message,
            'read_state' => 1,
            'attachment' => $attachments,
        ]);
    }

    public function edit($peer_id, $message_id,  $message = []) {
        return $this->call('messages.edit', [
            'peer_id' => $peer_id,
            'message_id' => $message_id,
            'message' => $message,
        ]);
    }

    public function request($method,$params=array()){
        $url = 'https://api.vk.com/method/'.$method;
        $params['access_token']= $this->token;
        $params['v']= "5.81";
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type:multipart/form-data"
            ));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = json_decode(curl_exec($ch), True);
            curl_close($ch);
        } else {
            $result = json_decode(file_get_contents($url, true, stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($params)
                )
            ))), true);
        }
        if (isset($result['response']))
            return $result['response'];
        else
            return $result;
    }
}