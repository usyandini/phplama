<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Auth;
use Flash;
use DB;
use Session;
use Hash;
use App\Repositories\AuditRepository as Audit;
use App\Models\SatkerMember;

class AuthController extends Controller
{

    public function postLogin(Request $request)
    {

        $this->validate($request, [
            'username' => 'required|min:3|max:255',
            'password' => 'required',
            ]);

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials, true)) {

            $user = Auth::user();
            
            // Allow only if user is root or enabled.
            if ( ('root' == $user->username) || ($user->enabled) )
            {
                Audit::log(Auth::user()->id, trans('general.audit-log.category-login'), trans('general.audit-log.msg-login-success', ['username' => $user->username]));
               //  $role = DB::table('role_user')->where('user_id', Auth::user()->id)->first();
                
               //  if (!$role) {
               //     Session::put('rolesOJK', 2);
               //  }else{
               //     Session::put('rolesOJK', $role->role_id);
               //     // dd(session('rolesOJK'));
               // }
                Flash::success("Welcome " . Auth::user()->first_name);
                
                return redirect()->intended($this->redirectPath());
            }
            else
            {

                Audit::log(null, trans('general.audit-log.category-login'), trans('general.audit-log.msg-forcing-logout', ['username' => $credentials['username']]));

                Auth::logout();
                return redirect(route('login'))
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => trans('admin/users/general.error.login-failed-user-disabled'),
                    ]);
            }
        }

        Audit::log(null, trans('general.audit-log.category-login'), trans('general.audit-log.msg-login-failed', ['username' => $credentials['username']]));

        return redirect($this->loginPath())
        ->withInput($request->only('username', 'remember'))
        ->withErrors([
            'username' => $this->getFailedLoginMessage(),
            ]);
    }

    public function newPostLogin(Request $request)
    {
        /*-------------
        | STEP 1
        | Validate the input
        */

        $this->validate($request, [
            'username' => 'required|min:3|max:255',
            'password' => 'required',
            ]);

        /*------------
        | STEP 2
        | Try to check the given input in local database record
        | If the is already registered, then redirect to dashboard
        | Otherwise, Step 3
        */

        $credentials = $request->only('username', 'password');
        // if (Auth::attempt($credentials, $request->has('remember'))) {
        Auth::attempt($credentials, $request->has('remember'));
        
        if (Auth::user()) {
            // $user = Auth::user();
            Flash::success("Welcome " . Auth::user()->first_name);
            return redirect()->intended($this->redirectPath());
        }else{

            $adServer = "ldap://corp.ojk.go.id";

            $ldap = @ldap_connect($adServer);
            if (!$ldap) {
                Audit::log(null, 'Can not connect to LDAP', ['username' => $credentials['username']]);

                Auth::logout();
                return redirect(route('login'))
                    ->withInput($request->only('username', 'remember'))
                    ->withErrors([
                        'username' => trans('Can not connect to LDAP'),
                        ]);
            }

            $ldaprdn = 'corp' . "\\" . $request->username;

            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($ldap, $ldaprdn, $request->password);
                // dd($bind);

            if ($bind) {
                $checkInside = User::where('username',$request->username)->first();
                if ($checkInside) {
                    Auth::login($checkInside);
                    return redirect()->intended($this->redirectPath());
                }

                $filter="(sAMAccountName=" . $request->username . ")";
                $result = ldap_search($ldap,"OU=User Accounts,OU=Users OJK,DC=corp,DC=ojk,DC=go,DC=id",$filter);
                $info = ldap_get_entries($ldap, $result);

                

                $user = new User();

                $user->status =  "Default Status User";
                $user->city =  "jakarta";
                $dname = @explode(' ',$info[0]['cn'][0]);
                $user->first_name =  $dname[0];
                $user->last_name =  (array_key_exists(1, $dname)) ? $dname[1] : '';
                $user->email =  $info[0]['mail'][0];
                $usrnm = explode('@', $info[0]['mail'][0]);
                $user->username = $usrnm[0];
                $user->password = Hash::make($request->password);
                $user->name = $info[0]['cn'][0];
                $user->last_login_date =  "2016-08-17 00:00:00";
                $user->enabled = 1;
                $user->auth_type= 'internal';


                $user->role =  2;

                $user->save();

                DB::table('role_user')->insert(['user_id'=>$user->id, 'role_id'=>'2']);

                        //UPDATE FROM SIMFOSIA
                $url = 'http://10.208.125.36/webservices/rest/?method=MasterDataPegawaiPOINTER&format=xml&pin=d2F5&email=' . $info[0]['mail'][0];
                $xml = simplexml_load_file($url) or die("Can not read simfosia feed");
                         // echo $xml->response->code;
                if($xml->response->total_seluruh_data < 1){
                    die('Kosong lho..');
                }

                $user = \App\User::findOrFail($user->id);
                $simDat = $xml->response->data->record[0];
                $user->email = $simDat->email;
                $user->nip = $simDat->nip;
                $user->name = $simDat->nm_peg;
                $user->nm_jabatan = $simDat->nm_jabatan;
                $user->designation = $simDat->nm_jabatan;
                $user->kd_unit_kerja = $simDat->kd_unit_kerja;
                $user->nm_unit_kerja = $simDat->nm_unit_kerja;
                $user->kd_bidang = $simDat->kd_bidang;
                $user->nm_bidang = $simDat->nm_bidang;
                $user->kd_deputi_komisioner = $simDat->kd_deputi_komisioner;
                $user->nm_deputi_komisioner = $simDat->nm_deputi_komisioner;
                $user->kd_deputi_direktur = $simDat->kd_deputi_direktur;
                $user->nm_deputi_direktur = $simDat->nm_deputi_direktur;
                $user->kd_bagian = $simDat->kd_bagian;
                $user->nm_sbagian = $simDat->nm_bagian;
                $user->kd_subbagian = $simDat->kd_subbagian;
                $user->nm_subbagian = $simDat->nm_subbagian;
                $user->kd_kantor = $simDat->kd_kantor;
                $user->nm_kantor = $simDat->nm_kantor;
                $user->phone_kantor = $simDat->phone_kantor;
                $user->phone_kantor_ext = $simDat->phone_kantor_ext;
                $user->dob = $simDat->tgl_lahir;
                $user->kd_departemen = $simDat->kd_departemen;
                $user->nm_departemen = $simDat->nm_departemen;

                $user->save();

                $satkerMember = new SatkerMember();
                $satkerMember->user_id = $user->id;
                $satkerMember->nama_departemen = $user->nm_departemen;
                $satkerMember->save();

                Auth::login($user);
                return redirect()->intended($this->redirectPath());


                @ldap_close($ldap);
        } else {
            Audit::log(null, 'Can not connect to LDAP', ['username' => $credentials['username']]);

            Auth::logout();
            return redirect(route('login'))
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => trans('Can not connect to LDAP'),
                ]);
        }
    }
}
}