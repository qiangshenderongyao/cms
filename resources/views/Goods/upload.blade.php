@extends('layout.main')
@section('content')
    <form action="/uploadpdf" method="post" enctype="multipart/form-data" class="form-inline">
    @csrf
            <input type="file" name="filename" class="form-control" id="goods_num" value="1">
            <input type="submit" value="upload" class="btn btn-primary">
    </form>
@endsection