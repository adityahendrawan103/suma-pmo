<?php

namespace App\Http\Controllers\App\Profile;

use App\Http\Controllers\Controller;
use App\Helpers\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(Request $request) {
        $responseApi = ApiService::AccountProfile(strtoupper(trim($request->session()->get('app_user_id'))),
                    strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            $data = json_decode($responseApi)->data;

            return view('layouts.profile.account.account', [
                'title_menu'    => 'My Account',
                'user_id'       => trim($data->user_id),
                'name'          => trim($data->name),
                'email'         => trim($data->email),
                'name'          => trim($data->name),
                'telepon'       => trim($data->telepon),
                'photo'         => trim($data->photo),
            ]);
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function saveAccount(Request $request) {
        $photo = '';
        $image_file = $request->file('photo');
        if($image_file) {
            $extension = $image_file->getClientOriginalExtension();
            $rename_file = strtoupper(trim($request->get('user_id'))).'.'.$extension;
            $path = trim(url('/')).'assets/images/profile/'.$rename_file;

            if(File::exists(trim(url('/')).'assets/images/profile/'.$rename_file)){
                File::delete(trim(url('/')).'assets/images/profile/'.$rename_file);
            }

            $image_file->move('assets/images/profile', $rename_file);
            $photo = $path;
        }


        $responseApi = ApiService::AccountProfileSimpan(strtoupper(trim($request->session()->get('app_user_id'))),
                    $request->get('name'), $request->get('email'), $request->get('telepon'), $photo,
                    strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            session()->flash('success', $messageApi);
            return redirect()->route('profile.account-profile');
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }
    }

    public function changePassword() {
        return view('layouts.profile.account.accountchangepassword', [
            'title_menu'    => 'Change Password',
        ]);
    }

    public function saveChangePassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'old_password'  => 'required|string',
            'password'      => 'required|string|confirmed',
        ]);

        if($validate->fails()) {
            return redirect()->back()->withInput()->with('failed', 'Kolom password dan password konfirmasi tidak boleh kosong dan harus sesuai');
        }

        $responseApi = ApiService::AccountChangePassword(strtoupper(trim($request->session()->get('app_user_id'))), trim($request->session()->get('app_user_email')),
                    $request->get('old_password'), $request->get('password'), strtoupper(trim($request->session()->get('app_user_company_id'))));
        $statusApi = json_decode($responseApi)->status;
        $messageApi =  json_decode($responseApi)->message;

        if($statusApi == 1) {
            session()->flash('success', $messageApi);
            return redirect()->route('profile.account-change-password-profile');
        } else {
            return redirect()->back()->withInput()->with('failed', $messageApi);
        }

    }
}
