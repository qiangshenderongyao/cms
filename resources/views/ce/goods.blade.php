@extends('layout.bts')
@section('content')
    @foreach($goods_data as $k=>$v)
        {{$v['goods_name']}}&nbsp;&nbsp;&nbsp;
        {{$v['goods_price']}}&nbsp;&nbsp;&nbsp;
        {{$v['goods_num']}}<hr>
    @endforeach
@endsection