@extends('layout.bts')
@section('content')
    <form class="form-inline">
        <!-- @csrf -->
        <table>
           <tr>
               <!-- <td>id</td> -->
               <td>商品名称</td>
               <td>数量</td>
               <td>价格</td>
               <td>操作</td>
           </tr>
           @foreach($data as $v)
           <tr>
               <!-- <td>{{$v['goods_id']}}</td> -->
               <td>{{$v['goods_name']}}</td>
               <td>{{$v['store']}}</td>
               <td>{{$v['price']}}</td>
               <td><a href="/goodsadd/{{$v['goods_id']}}">进入详情页</a></td>
           </tr>
           @endforeach
           {{$data->links()}}
        </table>
    </form>
@endsection