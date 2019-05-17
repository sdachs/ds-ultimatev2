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

Route::get('/', function () {
//    $flags = explode('|', env('DS_SERVER'));
//    return view('content.index', compact('flags'));
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/setlocale/{locale}',function($lang){
    $validLocale = in_array($lang, ['de', 'en']);
    if ($validLocale) {
        \Session::put('locale',$lang);
    }
    return redirect()->back();
})->name('locale');

Route::get('/php', function () {
    phpinfo();
});

Route::get('/test', 'Controller@test')->name('test');
Route::get('/server', 'DBController@getWorld');

Route::get('/serverDB', function (){
    $time = time();
    $world = new \App\World();
    $world->setTable(env('DB_DATABASE_MAIN').'.world');
    $worlds = $world->get();
    $db = new \App\Http\Controllers\DBController();
    foreach ($worlds as $world){
        $db->latestPlayer($world->name);
        echo'<br>';
        $db->latestAlly($world->name);
        echo'<br>';
        $db->latestVillages($world->name);
    }
    echo'<br>';
    echo'<br>';
    echo'<br>';
    echo time()-$time;
});

Route::get('/{server}', 'Controller@server')->name('server');

Route::get('/{server}/{world}', 'Controller@world')->name('world');

Route::get('/{server}/{world}/allys', 'Controller@allys')->name('worldAlly');
Route::get('/{server}/{world}/players/{page}', 'Controller@players')->name('worldPlayer');
Route::get('/{server}/{world}/player/{player}', 'PlayerController@player')->name('player');
Route::get('/{server}/{world}/ally/{ally}', 'AllyController@ally')->name('ally');
