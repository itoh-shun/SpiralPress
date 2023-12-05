@extends('template.dashboard-layout')

@section('body')
<h1 class="text-3xl font-bold text-clifford">
    プロジェクト作成
</h1>
@if($message)
<div class="alert {{ ($isError) ? 'alert-error' : 'alert-success' }} my-4">
    @if($isError)
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    @else 
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    @endif
    {{ $message }}
</div>
@endif
<form class="w-full lg:mx-0 mx-auto" action="{{ url('projects.store') }}" method="post" x-data="{ 
    projectName: {
        value : '{{ $validate['projectName']['value'] }}',
        isValid : '{{ $validate['projectName']['isValid'] === false }}',
        message : '{{ $validate['projectName']['message'] }}',
    }
}">
    <div class="flex flex-wrap my-6 gap-4">
        <div class="form-control w-full lg:flex-row flex-col">
            <label class="label lg:min-w-[16rem] justify-start gap-2">
                <span class="label-text">プロジェクト名</span>
                <span class="badge badge-error">
                    必須
                </span>
            </label>
            <div class="w-full">
                <input type="text" name="projectName" x-model='projectName.value' placeholder="Project Name" class="input input-bordered w-full " x-class="projectName.isValid ? 'input-error' : '' " />
                <div class="text-error" x-text="projectName.message"></div>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap my-6 gap-4">
        <div class="form-control w-full mt-8">
            <button class="btn btn-wide btn-success mx-auto">作成</button>
        </div>
        <input type="hidden" name="_method" value="post" />
        @csrf
    </div>
</form>
@endsection