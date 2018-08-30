<?php

Route::post('login', 'AuthController@login');
Route::post('refresh', 'AuthController@refresh');
Route::post('logout', 'AuthController@logout');