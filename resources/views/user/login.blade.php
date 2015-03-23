@extends('template.master')

@section('title')用户登录@stop

@section('content')
<div class="page-title">用户登录</div>
<div class="center">
	<ol class="left ilb" style="max-width:400px">
		<li>请使用教务处账号登录</li>
		<li>请使用IE9以上的浏览器</li>
	</ol>
</div>
<div class="rs-form-outer">
	<form action="#" method="post" class="rs-form center">
		<div class="iconed-text">
			<i class="fa fa-user"></i>
			<input type="text" name="username" placeholder="用户名">
		</div>
		<div class="iconed-text">
			<i class="fa fa-lock"></i>
			<input type="password" name="password" placeholder="密码">
		</div>
		<div class="form-btns">
			<input type="submit" class="btn-success">
		</div>
		<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	</form>
</div>
@stop