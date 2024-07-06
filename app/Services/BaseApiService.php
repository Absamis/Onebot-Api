<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class BaseApiService
{
    protected $rawRequest;
    private $errorResponse = null;
    protected $apiKey;
    protected $apiSecret;
    protected $apiToken;
    protected $defaultToken;

    public function __construct($baseurl, $token = null, $apiKey = null, $apiSecret = null)
    {
        $this->rawRequest = Http::baseUrl($baseurl);
        // $token ? $this->rawRequest->withToken($token) : null;
        // $apiKey ? $this->rawRequest->withBasicAuth($apiKey, $apiSecret) : null;
        $this->apiToken = $token;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function apiRequest($token = null)
    {
        if ($token)
            $this->rawRequest->withToken($token);
        return $this;
    }
    public function withToken($token = null)
    {
        $this->rawRequest->withToken($token ?? $this->apiToken);
        return $this;
    }


    public function withBasicAuth($key = null, $secret = null)
    {
        $key = $key ?? $this->apiKey;
        $secret = $secret ?? $this->apiSecret;
        $this->rawRequest->withBasicAuth($key, $secret);
        return $this;
    }

    public function withFiles($data = [])
    {
        $this->rawRequest->attach("ph", "fff");
        foreach ($data as $key => $item) {
            $this->rawRequest = $this->rawRequest->attach($item["key"], $item["value"], "file-$key.jpg");
        }
        return $this;
    }
    public function withFile($key, $value, $name = "file.jpg")
    {
        $this->rawRequest->attach("ph", "fff");
        if ($value)
            $this->rawRequest = $this->rawRequest->attach($key, $value, $name);
        return $this;
    }

    public function withHeaders($headers)
    {
        $this->rawRequest->withHeaders($headers);
        return $this;
    }
    public function asFormData()
    {
        $this->rawRequest->withHeaders([
            "Content-Type" => "multipart/form-data"
        ]);
        return $this;
    }

    public function asForm()
    {
        $this->rawRequest->asForm();
        return $this;
    }
    public function getRequest($url, $data = [])
    {
        $resp = $this->rawRequest
            ->get($url, $data);
        return $resp;
    }

    public function postRequest($url, $data = [])
    {
        // try {
        //return "FFFF";
        $resp = $this->rawRequest
            ->post($url, $data);
        return $resp;
        // } catch (Exception $ex) {
        //     return [];
        // }
    }

    public function putRequest($url, $data = [])
    {
        try {
            $resp = $this->rawRequest
                ->put($url, $data);
            return $resp;
        } catch (Exception $ex) {
            return [];
        }
    }

    public function deleteRequest($url)
    {
        try {
            $resp = $this->rawRequest
                ->delete($url);
            return $resp;
        } catch (Exception $ex) {
            return [];
        }
    }

    // protected function successResponse($data)
    // {
    //     $re = $this->response($data);
    //     $re = count($re)
    // }

    protected function response($data)
    {
        $re = $data ? $data->json() : [];
        return $re;
    }
}
