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

Route::get('/', 'ImeiController@home')->name('home');

Route::post('upload-excel', 'ImeiController@uploadExcel')->name('upload_excel');
Route::get('download-excel/template', 'ImeiController@downloadExcelTemplate')->name('download_excel.template');
Route::get('duplicate-excel', 'ImeiController@deleteDuplicates')->name('delete_duplicates');
