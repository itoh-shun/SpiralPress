@extends('template.dashboard-layout')

@section('body')
<div class="container">
    <h1 class="text-3xl font-bold text-clifford">ページ</h1>
    <div class="my-4">
        <a href="{{ url('posts.create', ['projectId' => $projectId],['categoryShareId' => $categoryShareId]) }}" class="btn btn-primary">ページを追加</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th>登録日時</th>
                    <th>更新日時</th>
                    <th>差し替えURL</th>
                    <th>タイトル</th>
                    <th>カテゴリ</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <!-- row -->
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->createAt }}</td>
                        <td>{{ $post->updateAt }}</td>
                        <td>%cms:post:url:{{ $post->postShareId }}%</td>
                        <td>{{ $post->title }}</td>
                        <td><span class="badge badge-primary badge-outline">{{ $post->categoryName }}</span></td>
                        <td>
                            <a href="{{ url('posts.edit', [
                                'projectId' => $post->projectId,
                                'id' => $post->postId
                            ]) }}" class="btn btn-sm btn-accent">編集</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection