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
                <td>app_secret</td>
                <td>状态</td>
                <td>理由</td>
                <td>获取app_key以及app_secret</td>
            </tr>
            @foreach($data as $v)
                <tr>
                    <td>{{$v['sname']}}</td>
                    <td>{{$v['shenfen']}}</td>
                    <td>{{$v['yt']}}</td>
                    <td>{{$v['app_key']}}</td>
                    <td>{{$v['app_secret']}}</td>
                    <td>{{$v['status']}}</td>
                    <td>{{$v['liyou']}}</td>
                    <td>
                        @if($v['status']==2)
                        <button>未通过</button>
                    @else
                        <a href="/fafang?id={{$v['id']}}">获取</a></td>
                        @endif
                </tr>
            @endforeach
            {{$data->links()}}
        </table>
    </form>
@endsection