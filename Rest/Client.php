<?php

namespace Micro\ClientBundle\Rest;

use Httpful\Request;

class Client {

    private $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function callRestfulApi($method, $path, $data = null) {
        $uri = $this->url . $path;
        switch ($method) {
            case "POST":
                $request = Request::post($uri, $data);
                break;
            case "PUT":
                $request = Request::put($uri, $data);
                break;
            case "GET":
                $request = Request::get($uri);
                break;
            default:
                throw new Exception("Unsupported method $method");
        }
        $response = $request->send();
        return $response->body;
    }

}
