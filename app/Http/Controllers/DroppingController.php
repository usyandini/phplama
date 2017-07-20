<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dropping;
use Auth;

class DroppingController extends Controller
{
    public function index()
    {
        return view('dropping.index');
    }
    public function tarik()
    {
        return view('dropping.tarik');
    }
    public function tambah()
    {
        return view('dropping.tambah');
    }
    public function tambahpost(Request $r)
    {
        $drop = new Dropping;
        $drop->tanggal      = date('d-m-Y');
        $drop->id_user      = Auth::user()->id;
        $drop->akun_bank    = $r->akun;
        $drop->jumlah       = $r->jumlah;
        $drop->status       = 'Penambahan';
        $drop->save();

        return redirect()->back()->with('success','Berhasil menambahkan dropping');
    }
    public function pengembalian()
    {
        return view('dropping.pengembalian');
    }
    public function pengembalianpost(Request $r)
    {
        $drop = new Dropping;
        $drop->tanggal      = date('d-m-Y');
        $drop->id_user      = Auth::user()->id;
        $drop->akun_bank    = $r->akun;
        $drop->jumlah       = $r->jumlah;
        $drop->status       = 'Pengembalian';
        $drop->save();

        return redirect()->back()->with('success','Berhasil menambahkan pengembalian dropping');
    }
    public function history()
    {
        $drop = Dropping::where('id_user',Auth::user()->id)->get();
        return view('dropping.history',compact('drop'));
    }
}
