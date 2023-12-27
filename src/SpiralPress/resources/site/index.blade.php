@extends('site.template.default')
@section('body')
<div class="prose">
    @if($showHead)
    <div class="container mx-auto block mb-4">
        <h1 class="text-xl text-base-content font-bold">{{$post->title}}</h1>
        @if($post->updateAt != '')
        <span class="text-sm">最終更新日時 {{$post->updateAt}}</span> 
        @endif
        <span class="text-sm">投稿日時 {{$post->createAt}}</span>
    </div>
    @endif
    <div id="site_content"></div>
</div>
<script>
    let html = {!! $codeHtmltoJson !!};
    document.getElementById('site_content').innerHTML = html['html']
    
</script>
@endsection

