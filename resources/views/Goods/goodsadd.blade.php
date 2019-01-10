@extends('layout.main')
@section('content')
    <form class="form-inline">
        <!-- @csrf -->
        
        <table>
            <tr>
                <td><h1>{{$data->goods_name}}</h1></td>
                <td></td>
            </tr>
            <tr>
                <td>价格：</td>
                <td>{{$data->price / 100}}</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" id="goods_num" value="1"></td>
                <td><button type="submit" class="btn btn-primary" id="add_cart_btn">加入购物车</button></td>
            </tr>
            <input type="hidden" id="goods_id" value="{{$data->goods_id}}">
        </table>
    </form>
@endsection
@section('foot')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection