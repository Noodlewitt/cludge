@extends('default_theme::layouts.main', ['meta'=>['title'=>$content->name]])

@section('content')
    <pre>hello world.
        {{$content->id}}
        //need to grab the content_type details - table name, content etc etc.

        {{$content->content_type->fields()->first()->name}}
    </pre>
@endsection
