<!--
                    _ooOoo_
                   o8888888o
                   88" . "88
                   (| -_- |)
                   O\  =  /O
                ____/`---'\____
              .'  \\|     |//  `.
             /  \\|||  :  |||//  \
            /  _||||| -:- |||||-  \
            |   | \\\  -  /// |   |
            | \_|  ''\---/''  |   |
            \  .-\__  `-`  ___/-. /
          ___`. .'  /--.--\  `. . __
       ."" '<  `.___\_<|>_/___.'  >'"".
      | | :  `- \`.;`\ _ /`;.`/ - ` : | |
      \  \ `-.   \_ __\ /__ _/   .-` /  /
 ======`-.____`-.___\_____/___.-`____.-'======
                    `=---='
 ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            佛祖保佑       永无BUG
-->
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
	<link rel="stylesheet" href="{{ asset('/css/rscss.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/font-awesome.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/main.css') }}">
	<title>@yield('title') - {{ trans('app.nuaa').' '.trans('app.name') }}</title>
	<!--[if lt IE 9]>
	<script src="{{ asset('/js/html5.js') }}"></script>
	<script src="{{ asset('/js/ieBetter.js') }}"></script>
	<![endif]-->
</head>
<body>
	<!-- 页眉 -->
	<div class="rs-header">
		<div class="rs-container">
			<a href="{{ url('/') }}"><h1 class="fl pointer white">{{ trans('app.name') }}</h1></a>
			<nav id="nav-main" class="rs-nav fl">
				<ul class="rs-main-nav">
					<a href="{{ url('/') }}">
						<li id="tabMain">{{ trans('app.banner.recommend') }}</li>
					</a>
					<a href="{{ url('apply/apply') }}">
						<li id="tabApp">{{ trans('app.banner.apply') }}</li>
					</a>
					<a href="{{ url('user/recommendations') }}">
						<li id="tabRec">{{ trans('app.banner.recommendation') }}</li>
					</a>
					<a href="{{ url('/') . '?lang=' . Lang::get('app.lang.value') }}">
						<li id="tabRec">{{ Lang::get('app.lang.name') }}</li>
					</a>
				</ul>
			</nav>
			<nav id="nav-user" class="rs-nav fr">
				@if (Auth::check())
				<ul class="rs-user-nav user-logged" id="tabUsr">
					<li class="user-avatar-outer"><img src="{{ asset('/img/avatar-' . Auth::user()->avatar . '.png') }}" class="user-avatar" onclick="void(0)"></li>
					<a href="{{ url('user/update') }}"><li>{{ trans('app.banner.update') }}</li></a>
					<a href="{{ url('user/logout') }}"><li>{{ trans('app.banner.logout') }}</li></a>
				</ul>
				@else
				<ul class="rs-user-nav">
					<a href="{{ url('user/login') }}"><li>{{ trans('app.banner.login') }}</li></a>
				</ul>
				@endif
			</nav>
		</div>
	</div>
	<!--[if lt IE 9]>
	<div class="rs-message">
		<div class="rs-container">
			<div class="rs-msg rs-msg-warning">
				{{ trans('app.ie') }}<a href="http://browsehappy.com/" target="_blank">{{ trans('app.download') }}</a>
			</div>
		</div>
	</div>
	<![endif]-->
	<!-- 消息显示块 -->
	@if (Session::has('message'))
	<div class="rs-message">
		<div class="rs-container">
			<div class="rs-msg rs-msg-{{ session('message')['type'] }}">
				{{ session('message')['content'] }}
			</div>
		</div>
	</div>
	@endif
	<!-- 主体内容 -->
	<div class="rs-container">
		@yield('content')
	</div>
	<!-- 页脚 -->
	<div class="rs-footer center">
		<div class="rs-container">
			<div>{{ trans('app.powerby.0') }}</div>
			<div>『<a href="http://my.nuaa.edu.cn" target="_blank">{{ trans('app.powerby.1') }}</a>』</div>
			<div>{{ trans('app.powerby.2') }}</div>
			<div>{{ trans('app.mail') }}<a href="mailto:nuaatw@163.com">nuaatw@163.com</div>
		</div>
	</div>
	<script>
		@yield('scripts')
		(function(window){
			var url=window.location.href;
			if(url.indexOf("user/recommendations")>=0)document.getElementById("tabRec").className+=" rs-nav-selected";
			else if(url.indexOf("apply/apply")>=0)document.getElementById("tabApp").className+=" rs-nav-selected";
			else if(url.indexOf("user")>=0)document.getElementById("tabUsr").className+=" rs-nav-selected";
			else document.getElementById("tabMain").className+=" rs-nav-selected";
		})(window);
	</script>
</body>
</html>