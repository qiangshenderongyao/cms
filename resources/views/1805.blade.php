@extends('layout.main')


@section('header')
    @parent
    <p style="color: red;">欢迎━(*｀∀´*)ノ亻!头部</p>
@endsection

@section('content')
<form action="/add" method="post">
    @csrf
    <table>
        <tr>
            <td>姓名</td>
            <td><input type="text" name="cname"></td>
        </tr>
        <tr>
            <td><input type="submit" value="提交"></td>
            <td></td>
        </tr>
    </table>
</form>
@endsection

@section('footer')
    @parent
    <p style="color: red;">欢迎━(*｀∀´*)ノ亻!尾部</p>
@endsection