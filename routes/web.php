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

Route::get('/', 'UserController@login');
Route::get('ldap', function()
{
    $ldap_dn    = 'cn=read-only-admin,dc=example,dc=com';
    $ldap_pass  = 'password';
    $ldap_conn  = ldap_connect('ldap.forumsys.com');
    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

    if (ldap_bind($ldap_conn, $ldap_dn, $ldap_pass)) {
        echo "BERHASIL MASUK LDAP SERVER";
    }else{
        echo 'GAGAL CONNECT LDAP SERVER!';
    }

});

Route::get('phpinfo', function(){
 dd(\App\Coba::all());
});

Route::post('post-login', 'UserController@postlogin');
// Auth::routes();

Route::group(['middleware' => ['session']], function () {

    Route::get('logout', 'UserController@logout');

    //Dashboard
    Route::get('beranda', 'DashboardController@home');

    //Dropping
    Route::get('dropping', 'DroppingController@index');
    Route::get('tarik-dropping', 'DroppingController@tarik');
    Route::get('tambah-dropping', 'DroppingController@tambah');
    Route::post('tambah-dropping', 'DroppingController@tambahpost');
    Route::get('pengembalian-dropping', 'DroppingController@pengembalian');
    Route::post('pengembalian-dropping', 'DroppingController@pengembalianpost');
    Route::get('history-dropping', 'DroppingController@history');

    //Transaksi
    Route::get('transaksi', 'TransaksiController@index');
    Route::get('input-transaksi', 'TransaksiController@input');
    Route::get('confirm-transaksi', 'TransaksiController@confirm');
    Route::get('verifikasi-transaksi', 'TransaksiController@verifikasi');

    //Management
    Route::get('list-user', 'ManagementController@listuser');
    Route::get('tambah-user', 'ManagementController@tambahuser');
    Route::get('edit-user', 'ManagementController@edituser');
});
