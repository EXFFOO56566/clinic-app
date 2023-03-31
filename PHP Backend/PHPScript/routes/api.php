<?php

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

Route::get("adddoctosdetail","API\ApiController@adddoctosdetail");

Route::any("bookappointment","API\ApiController@bookappointment");
Route::any("userregister","API\ApiController@userregister");
Route::any("savetoken","API\ApiController@savetoken");
Route::any("addreview","API\ApiController@postreview");
Route::any("forgotpassword","API\ApiController@forgotpassword");
Route::any("listofdepartment","API\ApiController@listofdepartment"); //list of department
Route::any("departmentdetailbyid","API\ApiController@departmentdetailbyid");
Route::any("listoffacilities","API\ApiController@listoffacilities");
Route::any("listofgallerycategory","API\ApiController@listofgallerycategory");
Route::any("listofimagebycategoryid","API\ApiController@listofimagebycategoryid");
Route::any("getlistofappointment","API\ApiController@getlistofappointment");
Route::any("listofdoctorbydepartment","API\ApiController@listofdoctorbydepartment");
Route::any("userlogin","API\ApiController@showlogin");
Route::any("gethealthpackage","API\ApiController@gethealthpackage");
Route::any("getuserupconmingappointment","API\ApiController@getuserupconmingappointment");
Route::any("getuserpastappointment","API\ApiController@getuserpastappointment");
Route::any("gettoken","API\ApiController@gettokendata");
Route::any("chatuploadmedia","API\ApiController@chatuploadmedia");
route::any("healthpackage","API\ApiController@healthpackage");
Route::any("mysubscription","API\ApiController@mysubscription");
Route::any("addsubscription","API\ApiController@addsubscription");
route::any("editprofile","API\ApiController@editprofile");
Route::any("topdoctor","API\ApiController@topdoctor");
Route::any("getdoctorandservicebydeptid","API\ApiController@getdoctorandservicebydeptid");
Route::any("searchterm","API\ApiController@searchdoctor");
Route::any("contactus","API\ApiController@contactus");
Route::any("doctordetails","API\ApiController@doctordetails");
Route::any("reviewlistbydoctor","API\ApiController@reviewlistbydoctor");
Route::any("getdepartment","API\ApiController@getdepartment");
Route::post("mediaupload",[ApiController::class,"mediaupload"]);
Route::get("deletemedia",[ApiController::class,"deletemedia"]);