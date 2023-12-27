@extends('template.base')
@section('style')
@endsection

@section('content')
    <dialog id="loading" class="bg-transparent">
        <span class="loading loading-infinity w-56"></span>
    </dialog>
    <div class="flex flex-col min-h-screen bg-opacity-90">
        <nav class="navbar bg-base-100">
            <div class="navbar-start">
                <label for="my-drawer" class="btn btn-square btn-ghost drawer-button lg:hidden">
                    <span class="material-symbols-outlined">
                        menu
                    </span>
                </label>
                <div class="flex-1">
                    <a class="btn btn-ghost text-xl" href="{{ url('/') }}">{{ $project->projectName ?? '' }}</a>
                </div>
            </div>
            <div class="navbar-end">
                <div class="navbar-center hidden lg:flex z-50">
                    {!! $menuHtml !!}
                </div>
                <button class="btn btn-square btn-ghost">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </button>
            </div>
        </nav>
        <div class="flex flex-grow bg-base-300">
            <div class="drawer">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content w-full">
                    <div class="container mx-auto min-h-full bg-base-100 p-4" id="default-siteview">
                        @yield('body')
                    </div>
                </div> 
                <div class="drawer-side h-full">
                    <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="bg-base-200 text-base-content p-4 w-80 h-full">
                        <div class="flex-1">
                            <a class="btn btn-ghost text-xl" href="{{ url('/') }}">{{ $project->projectName ?? '' }}</a>
                        </div>
                        {!! $menuHtml !!}
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
 