This is a blog item. roar.

{{$content->content_type->name}}

<?php
    $model = $content->data('cl_'.$content->content_type->name)->get();
?>

@foreach($model as $post)
    <h2>{{$post->name}}</h2>
    <p>
        {{$post->text}}
    </p>
@endforeach
