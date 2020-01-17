<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// API route group
$router->group(['prefix' => 'api','middleware'=>'core'], function () use ($router) {
    // Matches "/api/register
    $router->post('register', 'UserController@register');
    // Matches "/api/login
    $router->post('login', 'UserController@login');
    $router->get('profile', 'UserController@profile');
    $router->post('charge', 'UserController@charge');

    $router->group(['prefix' => 'admin'], function () use ($router) {

        //Category routes
        $router->get('category', 'CategoryController@index');
        $router->post('category/store', 'CategoryController@store');
        $router->delete('category/delete/{id}', 'CategoryController@destroy');

        //Meals routes
        $router->get('meal', 'MealController@index');
        $router->post('meal/store', 'MealController@store');
        $router->delete('meal/delete/{id}', 'MealController@destroy');

        //Orders routes
        $router->get('order', 'OrderController@index');
        $router->post('order/accept/{id}', 'OrderController@accept');
        $router->post('order/complete/{id}', 'OrderController@complete');
        $router->post('order/cancel/{id}', 'OrderController@cancel');
    });

    $router->group(['prefix' => 'customer'], function () use ($router) {

        //Category routes
        $router->get('category', 'CategoryController@index');

        //Meals routes
        $router->get('meal', 'MealController@index');

        //Orders routes
        $router->get('order', 'OrderController@index');
        $router->post('order/store', 'OrderController@store');
    });
});
