<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'Auth\\LoginController@login');
    $router->post('/register', 'Auth\\RegisterController@register');

    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/', 'CategoriesController@index');
        $router->get('/htmltree', 'CategoriesController@getCategoryHtmlTree');
        $router->get('/{id}', 'CategoriesController@show');
    });

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/profile', 'Auth\\LoginController@userDetails');
        $router->get('/logout', 'Auth\\LoginController@logout');
        $router->get('/check-login', 'Auth\\LoginController@checkLogin');

        $router->group(['prefix' => 'category'], function () use ($router) {
            $router->post('/', 'CategoriesController@store');
            $router->put('/{id}', 'CategoriesController@update');
            $router->delete('/{id}', 'CategoriesController@destroy');
        });
    });
});
