@extends('layout.bts')
@section('content')
    <h2>首页</h2>
    <pre/>
    <h3>商品</h3>
    @foreach($goods as $k=>$v)
        <a href="/ce/goods_data?goods_lei_id={{$v['goods_lei_id']}}">{{$v['goods_lei_name']}}</a>
    @endforeach
@endsection