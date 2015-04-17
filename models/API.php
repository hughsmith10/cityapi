<?php

namespace app\models;

class API

{

        public static $baseUrl = "http://cityapi.com-secrets.us";

        public static function curl($path)

        {

        $options = [

                CURLOPT_URL => self::$baseUrl . $path,

                CURLOPT_RETURNTRANSFER => true,

                ];

                $ch = curl_init();

                curl_setopt_array($ch, $options);

                $rawResponse = curl_exec($ch);

                $response = json_decode($rawResponse, true);

                return $response;

        }

}

