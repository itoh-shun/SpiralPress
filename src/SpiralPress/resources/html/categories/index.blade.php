@extends('template.dashboard-layout')

@section('body')
<div class="container">
    <h1 class="text-3xl font-bold text-clifford">カテゴリ一覧</h1>
    <div class="my-4">
        <a href="{{ url('categories.create', ['projectId' => $projectId]) }}" class="btn btn-primary">カテゴリを追加</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th>登録日時</th>
                    <th>更新日時</th>
                    <th>名前</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <!-- row -->
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->createAt }}</td>
                        <td>{{ $category->updateAt }}</td>
                        <td>{{ $category->categoryName }}</td>
                        <td>
                            <a href="{{ url('categories.edit', [
                                'projectId' => $projectId,
                                'id' => $category->categoryId
                            ]) }}" class="btn btn-sm btn-accent">編集</a>
                        </td>
                        <td>
                            <a href="{{ url('categories.posts.index', [
                                'projectId' => $projectId,
                                'categoryId' => $category->categoryId,
                            ]) }}" class="btn btn-sm btn-accent">ページ一覧</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection