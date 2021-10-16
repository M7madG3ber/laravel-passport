<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'api'
    ],
    function () {

        Route::post(
            '/login',
            [
                AuthController::class, 'login'
            ]
        );
        Route::post(
            '/register',
            [
                AuthController::class, 'register'
            ]
        );

        Route::group(
            [
                'middleware' => 'auth:api'
            ],
            function () {

                Route::post(
                    '/logout',
                    [
                        AuthController::class, 'logout'
                    ]
                );

                Route::get(
                    '/posts',
                    [
                        PostController::class, 'index'
                    ]
                );
            }
        );
    }
);