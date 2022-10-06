<?php

class Skroutz {
    private $token;

    function __construct($token = NULL) {
        $this->token = $token;
    }

    public function is_skroutz_order($order_id) {
        $url = "https://api.skroutz.gr/merchants/cps/orders/" . $order_id;
        $headers = array(
            "Accept: application/vnd.skroutz+json; version=3.0",
            "Authorization: Bearer " . $this->token,
        );

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYHOST => false,  //TODO: Only for debugging
            CURLOPT_SSL_VERIFYPEER => false   //
        ));

        curl_exec($curl);
        $responseCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        curl_close($curl);
        return $responseCode == 200;
    }

    public function reject_order($order_id, $reason) {
        $url = "https://api.skroutz.gr/merchants/cps/orders/" . $order_id . "/reject";

        $headers = array(
            "Accept: application/vnd.skroutz+json; version=3.0",
            "Authorization: Bearer " . $this->token,
        );

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"reason":{"name": "' . $reason . '"}}',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false  
        ));

        $resp = curl_exec($curl);
        echo $resp;
        curl_close($curl);
    }
}