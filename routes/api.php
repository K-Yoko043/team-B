<?php

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy machine your API!
|
*/

Route::middleware('auth:api')->get('/user/info', function (Request $request) {
    return new UserResource($request->user());
});
Route::middleware(['auth:api'])->group(function () {
    
    // レポート
    Route::get('report', 'API\ReportController@index');
    Route::post('report', 'API\ReportController@store');
    Route::get('report/{report}', 'API\ReportController@show');
    Route::put('report/{report}', 'API\ReportController@update');
    Route::delete('report/{report}', 'API\ReportController@destroy');
    Route::put('report/sendback/{report}', 'API\ReportController@sendback');
    Route::post('report/download/', 'API\ReportController@download');
    Route::put('report/{report}/correct', 'API\ReportController@correct');

    Route::get('report/line/selector', 'API\ReportController@lineSelector');

    // 従業員
    Route::get('employee', 'API\EmployeeController@index');
    Route::post('employee', 'API\EmployeeController@store');
    Route::get('employee/{employee}', 'API\EmployeeController@show');
    Route::put('employee/{employee}', 'API\EmployeeController@update');
    Route::delete('employee/{employee}', 'API\EmployeeController@destroy');
    Route::get('employee/selector', 'API\EmployeeController@selector');
    Route::get('employee/full_name', 'API\EmployeeController@fullname');

    // 商品
    Route::get('item', 'API\ItemController@index');
    Route::post('item', 'API\ItemController@store');
    Route::get('item/{item}', 'API\ItemController@show');
    Route::put('item/{item}', 'API\ItemController@update');
    Route::delete('item/{item}', 'API\ItemController@destroy');
    Route::get('item/selector', 'API\ItemController@selector');

    Route::get('lesson/selector', 'API\LessonController@index');

    Route::get('customer', 'API\CustomerController@index');
    Route::post('customer', 'API\CustomerController@store');
    Route::get('customer/{customer}', 'API\CustomerController@show');
    Route::put('customer/{customer}', 'API\CustomerController@update');
    Route::delete('customer/{customer}', 'API\CustomerController@destroy');
    Route::get('customer/selector', 'API\CustomerController@selector');
    
    // ゴリラー
    Route::get('goriller', 'API\GorillerController@index');
    Route::post('goriller', 'API\GorillerController@store');
    Route::get('goriller/{goriller}', 'API\GorillerController@show');
    Route::put('goriller/{goriller}', 'API\GorillerController@update');
    Route::delete('goriller/{goriller}', 'API\GorillerController@destroy');
    Route::get('goriller/selector', 'API\GorillerController@selector');
    Route::get('goriller/full_name', 'API\GorillerController@fullname');

});
