<?php

class JustInApi {

    public $auth_login = 'Exchange';
    public $auth_password = 'Exchange';

    public $client_login = '';
    public $client_password = '';

    public $language = 'RU';

    public $url = "http://195.201.72.186/justin_pms/hs/v2/runRequest";

    public $test_mode = "not_test";

    function __construct($client_login, $client_password, $language = 'RU', $test_mode = 'not_test') {
        $this->client_login = $client_login;
        $this->client_password = $client_password;
        $this->language = $language;
        if($test_mode != 'not_test'){
            $this->url = "http://195.201.72.186/justin_pms_test/hs/v2/runRequest";
            $this->test_mode = $test_mode;
        }

        return $this;
    }

    // Отримання даних відділень
    public function get_departments($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'request',
            "name" => 'req_DepartmentsLang',
            "params" => array(
                "language" => $this->language
            )
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання даних вулиць
    public function get_streets($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'catalog',
            "name" => 'cat_cityStreets',
            "language" => $this->language
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання даних районів міст
    public function get_city_regions($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'catalog',
            "name" => 'cat_cityRegions',
            "language" => $this->language
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання даних населених пунктів
    public function get_cities($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'catalog',
            "name" => 'cat_Cities',
            "language" => $this->language
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання даних обласних районів
    public function get_region_areas($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'catalog',
            "name" => 'cat_areasRegion',
            "language" => $this->language
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання даних областей
    public function get_regions($filter = array()){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'catalog',
            "name" => 'cat_Region',
            "language" => $this->language
        );

        if($filter != array()){
            $json_value['filter'] = $filter;
        }

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Отримання UUID
    public function get_uuid(){

        $json_value = array(
            "keyAccount" => $this->client_login,
            "sign" => $this->forming_sign(),
            "request" => 'getData',
            "type" => 'infoData',
            "name" => 'getSenderUUID',
            "filter" => array(
                0 => array(

                    "name" => 'login',
                    "comparison" => 'equal',
                    "leftValue" => $this->client_login
                )
            )
        );

        $return = $this->send_request($json_value);

        if($return['response']['status'] == 1){
            return $return['data'];
        }else{ // Якщо у відповідь отримали помилку - повертаємо її
            return $return['response']['message'];
        }
    }

    // Запит доставки
    public function request_delivery($api_key, $data){

        $json_value = array(
            "api_key" => $api_key,
            "data" => $data
        );

        if($this->test_mode != 'not_test'){
            $return = $this->send_request($json_value, '195.201.72.186/api_test/hs/api/v1/documents/orders');
        }else{
            $return = $this->send_request($json_value, '195.201.72.186/api_pms/hs/api/v1/documents/orders');
        }

        // Якщо у відповідь отримали помилки - повертаємо їх
        if($return['result'] == 'error'){
            return $return['errors'];
        }else{
            return $return['data'];
        }
    }

    // Відправлення запитів
    private function send_request($json_value, $standart_url = 1){

        // Якщо функція отримала URL - беремо його
        if($standart_url == 1){
            $url = $this->url;
        }else{
            $url = $standart_url;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', "Authorization: Basic " . base64_encode("$this->auth_login:$this->auth_password")));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING , "gzip");
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

    // Формування підпису
    private function forming_sign(){
        return sha1($this->client_password . ':' . date('Y-m-d'));
    }















}