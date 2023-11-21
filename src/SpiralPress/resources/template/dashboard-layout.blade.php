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
            <div class="flex flex-wrap justify-between items-center mx-auto container p-4">
                <a href="{{ config('url.area') }}" class="flex items-center flex-col md:flex-row">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap">{{ config("app.name" , "" ) }}</span>
                </a>
            </div>
        </nav>
        <div class="flex flex-grow">
        @yield('body')
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
 