@extends('template.dashboard-layout')

@section('body')
<h1 class="text-3xl font-bold underline text-clifford">
    ダッシュボード
</h1>

<div id="editorjs"></div>

<script>

let editor = new EditorJS({
    holder: 'editorjs',
    placeholder: 'No content',
    data: {},
})

</script>
@endsection