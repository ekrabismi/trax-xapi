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


Route::middleware(['web', 'auth', 'locale'])->namespace('Trax\XapiServer\Http\Controllers')->group(function () {
    
    /**
     * Views routes.
     */

    // Settings
    Route::get('trax/ui/xapi-server/settings', 'XapiServerViewsController@settings')->name('trax.ui.xapi-server.settings');

    // Statements
    if (config('trax-xapi-server.services.statements')) {
        Route::get('trax/ui/xapi-server/statements', 'XapiServerViewsController@statements')->name('trax.ui.xapi-server.statements');
    }

    // Activities
    if (config('trax-xapi-server.services.activities')) {
        Route::get('trax/ui/xapi-server/activities', 'XapiServerViewsController@activities')->name('trax.ui.xapi-server.activities');
    }

    // Agents
    if (config('trax-xapi-server.services.agents')) {
        Route::get('trax/ui/xapi-server/agents', 'XapiServerViewsController@agents')->name('trax.ui.xapi-server.agents');
    }

});
