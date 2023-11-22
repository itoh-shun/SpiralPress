@extends('template.base')
@section('style')
@endsection

@section('script')
<script>
    @include('script/combined.php');
</script>
@endsection
@section('content')
    <div class="flex flex-col min-h-screen bg-opacity-90">
        <nav class="bg-white border-gray-200">
            <div class="flex flex-wrap justify-between items-center mx-auto container-fluid p-4">
                <div class="flex flex-wrap gap-4">
                    <label for="my-drawer" class="btn btn-square drawer-button lg:hidden">
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
                    @yield('body')
                </div> 
                <div class="drawer-side">
                    <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="min-h-full bg-base-200 text-base-content p-4 w-80">
                        <ul class="menu p-0">
                            <li>
                                <details open>
                                    <summary>ProjectName</summary>
                                    <ul>
                                        <li><a>メディア</a></li>
                                        <li><a>固定ページ</a></li>
                                        <li><a>ブログ投稿</a></li>
                                        <li><a>コメント一覧</a></li>
                                        <li><a>設定</a></li>
                                    </ul>
                                </details>
                            </li>
                        </ul>
                        <a class="btn w-full btn-success mt-4" href="{{ url('projects.index') }}">
                            <span class="material-symbols-outlined">add</span>
                            新規プロジェクト作成
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <footer  class="bg-slate-800 ">
            <div class="px-4 py-6 container mx-auto text-white">
                <div>
                    <span class="text-sm text-gray-200 ">{!! config('app.copyright','') !!}</span>
                </div>
            </div>  
        </footer>
    </div>
@endsection
 