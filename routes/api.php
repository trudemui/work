<?php

use App\Http\Controllers\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
});

Route::group([
    'prefix' => 'auth',
    'middleware' => 'auth:api'
], function () {
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

//growing_crops
Route::post('growing_crops/delete', 'GrowingCropController@delete')->name('growing_crop.delete');
Route::post('growing_crops/show', 'GrowingCropController@show')->name('growing_crops.show');
Route::get('growing_crops/get_list', 'GrowingCropController@get_list')->name('growing_crops.get_list');
Route::post('growing_crops/find_crop', 'GrowingCropController@find_crop')->name('growing_crops.find_crop');
Route::post('growing_crops/find_crops', 'GrowingCropController@find_crops')->name('growing_crops.find_crops');
Route::post('growing_crops/update', 'GrowingCropController@update')->name('growing_crops.update');
Route::post('growing_crops/search', 'GrowingCropController@search')->name('growing_crops.search');
Route::post('growing_crops/sort', 'GrowingCropController@sort')->name('growing_crops.sort');
Route::post('growing_crops/print', 'GrowingCropController@print')->name('growing_crops.print');
Route::post('growing_crops/nothing_print', 'GrowingCropController@nothing_print')->name('growing_crops.nothing_print');
Route::apiResource('/growing_crops', 'GrowingCropController', ['except' => ['show', 'destroy']]);

//productions
Route::post('productions/crop_index', 'ProductionControlController@crop_index')->name('productions.crop_index');
Route::post('productions/crop_show', 'ProductionControlController@crop_show')->name('productions.crop_show');
Route::get('productions/get_selectbox', 'ProductionControlController@get_selectbox')->name('productions.get_selectbox');
Route::post('productions/find_data', 'ProductionControlController@find_data')->name('productions.find_data');
Route::post('productions/update', 'ProductionControlController@update')->name('productions.update');
Route::post('productions/delete', 'ProductionControlController@delete')->name('productions.delete');
Route::post('productions/sort', 'ProductionControlController@sort')->name('productions.sort');
Route::apiResource('/productions', 'ProductionControlController', ['except' => ['destroy', 'index', 'show', 'update']]);

//jas_controls
Route::post('jas_controls/crop_index', 'JasControlController@crop_index')->name('jas_controls.crop_index');
Route::post('jas_controls/crop_show', 'JasControlController@crop_show')->name('jas_controls.crop_show');
Route::post('jas_controls/show', 'JasControlController@show')->name('jas_controls.show');
Route::post('jas_controls/search', 'JasControlController@search')->name('jas_controls.search');
Route::post('jas_controls/find_data', 'JasControlController@find_data')->name('jas_controls.find_data');
Route::post('jas_controls/update', 'JasControlController@update')->name('jas_controls.update');
Route::post('jas_controls/delete', 'JasControlController@delete')->name('jas_controls.delete');
Route::post('jas_controls/sort', 'JasControlController@sort')->name('jas_controls.sort');
Route::apiResource('/jas_controls', 'JasControlController', ['except' => ['index', 'show', 'update', 'destroy']]);

//templates
Route::post('templates/select_table', 'TemplateController@select_table')->name('templates.select_table');
Route::get('templates/show_table', 'TemplateController@show_table')->name('templates.show_table');
Route::post('templates/update', 'TemplateController@update')->name('templates.update');
Route::post('templates/delete', 'TemplateController@delete')->name('templates.delete');
Route::apiResource('/templates', 'TemplateController', ['except' => ['show', 'update']]);

//users
Route::apiResource('/users', 'UserController');

//crops
Route::apiResource('/crops', 'CropController');

//varieties
Route::post('varieties/find_varieties', 'VarietyController@find_varieties')->name('varieties.find_varieties');
Route::apiResource('/varieties', 'VarietyController');

//categories
Route::get('/categories', 'CategoryController@index')->name('categories.index');
