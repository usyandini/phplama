@extends('layouts.master')
@section('css')
  <link href="{{asset('recources/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('recources/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style>
  .form-horizontal .radio{padding-top: 0px;}
</style>
<div class="page-content">
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('beranda')}}">Beranda</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit User</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"><b>Edit User</b></h3>
    <br>
    <div class="row">
        <div class="col-md-12">
           <form class="form-horizontal" style="overflow: hidden; clear: both;">
              <div class="form-group">
                <label class="col-sm-2 control-label text-left"><b>Nama Pengguna</b></label>
                <div class="col-sm-10">
                  <select class="select2 form-control" multiple="multiple" style="width: 100%!important;">
                    <option value="Admin">Admin</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-7 text-right">                  
                    <input type="radio" id="radio1" class="form-control">
                    <label for="radio1">
                        <span class="box"></span> Daftar dengan akun Aktif Direktori
                    </label>
                
                    <input type="radio" id="radio2" class="form-control">
                    <label for="radio2">
                        <span class="box"></span> Daftar dengan akun Biasa 
                    </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label text-left"><b>Nama Lengkap</b></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Nama Lengkap">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label text-left"><b>Kata Sandi</b></label>
                <div class="col-sm-10">
                  <input type="password" id="password" class="form-control" placeholder="Kata Sandi">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-4 text-right" style="margin-left: 10px;">
                  <input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Munculkan Password
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label text-left"><b>Email</b></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Email">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label text-left"><b>Role Pengguna</b></label>
                <div class="col-sm-10">
                  <select class="select2 form-control" multiple="multiple" style="width: 100%;">
                    <option value="Admin">Admin</option>
                    <option value="Cabang">Cabang</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('recources/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script>
  $(document).ready(function() {
    $(".select2").select2();
  });
</script>
@endsection