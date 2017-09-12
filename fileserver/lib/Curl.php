<?php

class Curl
{
    function __construct($url,$method='POST')
    {
        $this->url = $url;
        $this->method = $method;
        $this->data = array();
        $this->headers = array();
    }

    function appendField($field,$value)
    {
        $this->data[$field]=$value;
    }

    function appendHeader($header)
    {
        $this->headers[] = $header;
    }

    function send()
    {
        $conn = curl_init($this->url);
        
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, $this->method);
        if(count($this->data)>0)
            curl_setopt($conn, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $this->headers);
        
        $res = curl_exec($conn);
        curl_close($conn);

        return $res;
    }
}

$g = new Curl('www.google.com','GET');
print_r($g->send());