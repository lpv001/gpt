<?php

namespace App\Frontend\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Lang;

class ClientService
{

    protected $client;
    protected $message;
    protected $statCode;
    protected $status;
    protected $data;

    /**
     *
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     *
     */
    public static function getBaseUri()
    {
        return env('API_URL');
    }

    /**
     *
     */
    public static function getHeaders()
    {
        return [
            'Content-Type'        => 'application/json',
            'Content-Language'  =>  Lang::getLocale(),
            'Authorization' => "Bearer " . session()->get('token') ?? ''
        ];
    }

    /**
     *
     */
    public function get($method, $url, $param)
    {
        try {
            $client = $this->client->request('GET', $url, [
                'headers'  => self::getHeaders(),
                'verify' => false
            ]);
        } catch (BadResponseException $error) {
        }
    }
}
