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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', 'PagesController@index');
Route::get('/home', function() {
    return redirect('/', 301);
});

Route::get('/contact', 'PagesController@contact')->name('contact');
Route::post('/contact', 'PagesController@sendContactEmail');
Route::get('/cec', 'PagesController@cec')->name('cec');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

if(count(App\User::all()) == 0) {
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Auth\RegisterController@register');
}

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/profile', 'UsersController@index')->name('profile');
Route::get('/profile/{id}', 'UsersController@showProfile')->name('profile.view')->where('id', '[0-9]+');
Route::post('/profile/info/update', 'UsersController@updateInfo')->name('profile.updateInfo');
Route::post('/profile/passwd', 'UsersController@passwd')->name('profile.passwd');
Route::post('/profile/updatepp', 'UsersController@updateProfilePicture')->name('profile.updatepp');

Route::get('/admin', 'AdminController@index')->name('admin');
Route::post('/admin/user/edit', 'AdminController@editUser')->name('admin.user.edit');
Route::post('/admin/user/create', 'AdminController@addUser')->name('admin.user.edit');
Route::post('/admin/user/delete', 'AdminController@delete')->name('admin.user.delete');

Route::get('/news', 'NewsController@index')->name('news');
Route::get('/news/create', 'NewsController@create')->name('news.create');
Route::post('/news/upload', 'NewsController@upload')->name('news.upload');
Route::post('/news/store', 'NewsController@store')->name('news.store');
Route::get('/news/v/{id}', 'NewsController@view')->name('news.view');
Route::get('/news/e/{id}', 'NewsController@edit')->name('news.edit');
Route::post('/news/update', 'NewsController@update')->name('news.update');
Route::post('/news/hide', 'NewsController@hide')->name('news.hide');
Route::post('/news/unhide', 'NewsController@unhide')->name('news.unhide');
Route::post('/news/delete', 'NewsController@delete')->name('news.delete');

Route::get('/events', 'EventsController@index')->name('events');
Route::get('/events/create', 'EventsController@create')->name('events.create');
Route::post('/events/upload', 'EventsController@upload')->name('events.upload');
Route::post('/events/store', 'EventsController@store')->name('events.store');
Route::get('/events/v/{id}', 'EventsController@view')->name('events.view');
Route::get('/events/e/{id}', 'EventsController@edit')->name('events.edit');
Route::post('/events/update', 'EventsController@update')->name('events.update');
Route::post('/events/hide', 'EventsController@hide')->name('events.hide');
Route::post('/events/unhide', 'EventsController@unhide')->name('events.unhide');
Route::post('/events/delete', 'EventsController@delete')->name('events.delete');

Route::get('/files', 'FilesController@index')->name('files');
Route::get('/files/upload', 'FilesController@upload')->name('files.upload');
Route::post('/files/mkdir', 'FilesController@mkdir')->name('files.mkdir');
Route::post('/files/save', 'FilesController@save')->name('files.save');
Route::get('/files/download', 'FilesController@download')->name('files.download');
Route::get('/files/view', 'FilesController@responseFile')->name('files.view');
Route::post('/files/delete', 'FilesController@delete')->name('files.delete');

Route::get('/insert-role', function () {
    $roles = json_decode(file_get_contents(base_path('resources/data/roles.json')), true);
    DB::table('roles')->delete();
    DB::statement('ALTER TABLE `roles` AUTO_INCREMENT=1;');
    foreach($roles as $role) {
        DB::table('roles')->insert([
            'name' => $role['name'],
            'level' => $role['level'],
            'description' => $role['description'],
            'standalone' => $role['standalone'],
        ]);
    }
});
Route::get('/insert-committee', function () {
    $committees = json_decode(file_get_contents(base_path('resources/data/committees.json')), true);
    DB::table('committees')->delete();
    DB::statement('ALTER TABLE `committees` AUTO_INCREMENT=1;');
    foreach($committees as $committee) {
        DB::table('committees')->insert([
            'name' => $committee['name'],
            'description' => $committee['description'],
        ]);
    }
});
Route::get('/insert-club', function () {
    $committees = json_decode(file_get_contents(base_path('resources/data/clubs.json')), true);
    DB::table('clubs')->delete();
    DB::statement('ALTER TABLE `clubs` AUTO_INCREMENT=1;');
    foreach($committees as $committee) {
        DB::table('clubs')->insert([
            'name' => $committee['name'],
            'description' => $committee['description'],
        ]);
    }
});
