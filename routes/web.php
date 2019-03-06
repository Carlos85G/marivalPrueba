<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Entrar directamente a la ventana de autenticación */
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Rutas de backend: usuarios */
Route::get('usuarios', 'UsuariosController@index')->name('usuarios');
Route::get('usuarios/nuevo', 'UsuariosController@create')->name('usuarios.nuevo');
Route::post('usuarios', 'UsuariosController@store')->name('usuarios.guardar');
Route::get('usuarios/{id}', 'UsuariosController@show')->name('usuarios.ver');
Route::get('usuarios/{id}/foto', 'UsuariosController@avatar')->name('usuarios.foto');
Route::get('usuarios/{id}/editar', 'UsuariosController@edit')->name('usuarios.editar');
Route::patch('usuarios/{id}', 'UsuariosController@update')->name('usuarios.actualizar');
Route::delete('usuarios/{id}/eliminar', 'UsuariosController@destroy')->name('usuarios.eliminar');
Route::patch('usuarios/{id}/eliminar', 'UsuariosController@dismiss')->name('usuarios.retirar');
