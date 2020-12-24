<?php

class KomojuApi
{
    public function __construct($secretKey)
    {
        $this->endpoint = 'https://komoju.com';
        $this->secretKey = $secretKey;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function paymentMethods()
    {
        return $this->get('/api/v1/payment_methods');
    }

    public function createSession($payload)
    {
        return $this->post('/api/v1/sessions', $payload);
    }

    public function session($sessionUuid)
    {
        return $this->get('/api/v1/sessions/' . $sessionUuid);
    }

    private function get($uri)
    {
        $ch = curl_init($this->endpoint . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new KomojuExceptionBadServer($error);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            $komojuException = new KomojuExceptionBadServer($result);
            $komojuException->httpCode = $http_code;
            throw $komojuException;
        }

        curl_close($ch);

        $decoded = json_decode($result);
        if ($decoded === null) {
            throw new KomojuExceptionBadJson($result);
        }

        return $decoded;
    }

    // e.g. $payload = array(
    //     'foo' => 'bar'
    // );
    private function post($uri, $payload)
    {
        $ch = curl_init($this->endpoint . $uri);
        $data_json = json_encode($payload);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new KomojuExceptionBadServer($error);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            $komojuException = new KomojuExceptionBadServer($result);
            $komojuException->httpCode = $http_code;
            throw $komojuException;
        }

        curl_close($ch);

        $decoded = json_decode($result);
        if ($decoded === null) {
            throw new KomojuExceptionBadJson($result);
        }

        return $decoded;
    }
}
