<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Lening-@yield('title')</title>
	<link rel="stylesheet" href="{{URL::asset('/css/test.css')}}">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<link rel="stylesheet" href="{{URL::asset('/bootstrap/css/bootstrap.min.css')}}">
</head>
<body>
<div class="container">
    @yield('content')

</div>

@section('foot')
    <script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="https://s23.cnzz.com/z_stat.php?id=1276680346&web_id=1276680346"></script>
@show
</body>
</html>