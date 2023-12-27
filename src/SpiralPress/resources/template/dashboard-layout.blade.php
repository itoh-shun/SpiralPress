@extends('template.base')
@section('style')
@endsection

@section('content')
    <dialog id="loading" class="bg-transparent">
        <span class="loading loading-infinity w-56"></span>
    </dialog>
    <div class="flex flex-col min-h-screen bg-opacity-90">
        <nav class="bg-base-content text-base-100">
            <div class="flex flex-wrap justify-between items-center mx-auto container-fluid p-4">
                <div class="flex flex-wrap gap-4">
                    <label for="my-drawer" class="btn btn-square btn-ghost drawer-button lg:hidden">
                        <span class="material-symbols-outlined">
                            menu
                        </span>
                    </label>
                    <a href="{{ config('url.area') }}" class="flex items-center flex-col md:flex-row">
                        <span class="self-center text-2xl font-semibold whitespace-nowrap">{{ config("name" , "" ) }}</span>
                    </a>
                </div>
                <a href="{{ config('url.logout', '') }}">ログアウト</a>
            </div>
        </nav>
        <div class="flex flex-grow">
            <div class="drawer lg:drawer-open">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content container my-2 mx-auto px-4">
                    <div class="m-4 p-8 rounded shadow-xl border">
                        @yield('body')
                    </div>
                </div> 
                <div class="drawer-side h-full">
                    <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="bg-base-200 text-base-content p-4 w-80 h-full">
                        <ul class="menu p-0 break-all">
                            @foreach(getProjects() as $project)
                            <li>
                                <details {{ ($projectId === $project->projectId)? 'open' : '' }}>
                                    <summary>{{ $project->projectName }}</summary>
                                    <ul>
                                        <!--<li><a href="{{ url('media.index', ['projectId' => $project->projectId]) }}">メディア</a></li>-->
                                        <li><a href="{{ url('categories.index', ['projectId' => $project->projectId]) }}">カテゴリ管理</a></li>
                                        <li><a href="{{ url('posts.index', ['projectId' => $project->projectId]) }}">ページ</a></li>
                                        <!--<li><a href="{{ url('comments.index', ['projectId' => $project->projectId]) }}">コメント一覧</a></li>-->
                                        <li><a href="{{ url('menu.index', ['projectId' => $project->projectId]) }}">グローバルメニュー設定</a></li>
                                        <li><a href="{{ url('projects.edit', ['id' => $project->projectId]) }}">設定</a></li>
                                    </ul>
                                </details>
                            </li>
                            @endforeach
                        </ul>
                        <!--<a class="btn w-full btn-ghost mt-4" href="{{ url('projects.create') }}">
                            <span class="material-symbols-outlined">add</span>
                            新規プロジェクト作成
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-neutral-content text-neutral ">
            <div class="px-4 py-6 container mx-auto">
                <div>
                    <span class="text-sm ">{!! config('app.copyright','') !!}</span>
                </div>
            </div>  
        </footer>
    </div>
@endsection
 