@extends('layout.bts')
@section('content')
    <form class="form-inline">
    <!-- @csrf -->
        <table>
            <tr>
                <!-- <td>id</td> -->
                <td>用户</td>
                <td>身份证号</td>
                <td>类型</td>
                <td>app_key</td>
                <td>app_secert</td>
                <td>状态</td>
                <td>理由</td>
            </tr>
            @foreach($data as $v)
                <tr>
                    <td>{{$v['sname']}}</td>
                    <td>{{$v['shenfen']}}</td>
                    <td>{{$v['yt']}}</td>
                    <td>{{$v['app_key']}}</td>
                    <td>{{$v['app_secert']}}</td>
                    <td>{{$v['status']}}</td>
                    <td>{{$v['liyou']}}</td>
                </tr>
            @endforeach
            {{$data->links()}}
        </table>
    </form>
@endsection