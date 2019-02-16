@extends('layout.bts')
@section('content')
    @foreach($data as $k=>$v)
        @if($v==1)
            <button class="btn-default btn-danger" value="1"> 座位{{ $k }} </button>  <br>
        @else
            <button class="btn-default btn-info" value="0"> 座位{{ $k }} </button>  <br>
        @endif
    @endforeach
@endsection
@section('foot')
    @parent
    <script src="{{URL::asset('/js/goods/Rediszuo.js')}}"></script>
@endsection
