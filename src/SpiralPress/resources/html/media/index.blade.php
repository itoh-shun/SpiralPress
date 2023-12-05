@extends('template.dashboard-layout')

@section('body')
    <h1 class="text-3xl font-bold underline text-clifford">
        メディア管理
    </h1>
    <div class="my-4">
        <a class="btn">新規追加</a>
    </div>
    <div class="my-4">
        {!! $media->links() !!}
    </div>
    <div class="grid grid-cols-1 gap-4">
        @foreach($media->getData() as $data)
        <div class="card card-side glass">
            <figure>
                @if($data->type === 'video')
                <video controls class="max-h-40 w-96">
                    <source src="{{ $data->file }}">
                </video>
                @elseif($data->type === 'audio')
                <audio controls src="{{ $data->file }}" class="max-h-40 w-96"></audio>
                @else
                <img src="{{ $data->file }}" class="max-h-40 w-96" />
                @endif
            </figure>
            <div class="card-body justify-between">
                <div>
                    <input class="input input-ghost w-full" readonly type="text" value="{{ $data->file }}"> 
                </div>
                <div class="card-actions justify-end">
                    <button class="btn btn-error">削除</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection