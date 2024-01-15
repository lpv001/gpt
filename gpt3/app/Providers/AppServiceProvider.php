<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        view()->composer('frontend::layouts.header', function ($view) {

            try {
                $base_url = env('API_URL');
                $headers = [
                    'Authorization' => 'Bearer ' . session()->get('token'),
                    'Content-Type'        => 'application/json',
                    'Content-Language'  =>  \App::getLocale()
                ];
                $client = new Client();
                $response = $client->request('GET', $base_url . '/notifications', [
                    'headers'  => $headers,
                    'verify' => false
                ]);

                $data = json_decode($response->getBody(), true);


                return $view->with('data', count($data['data']['notification']));
            } catch (\Exception $e) {
                $data = 'error';
                return $view->with('error', $data);
            }
        });
    }
}
