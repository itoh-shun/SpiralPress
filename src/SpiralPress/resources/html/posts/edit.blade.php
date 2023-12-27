@extends('template.dashboard-layout')

@section('body')
    <h1 class="text-3xl font-bold text-clifford">ページを編集</h1>
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
    <div class="container mx-auto p-4" x-data="edit">
        <h1 class="text-2xl font-bold mb-4">記事を編集</h1>
        <form method="POST" name="postupdate" class="w-full" action="{{ url('posts.update', ['projectId' => $post->projectId, 'id' => $post->postId]) }}" method="post">
            @csrf
            <div class="flex flex-wrap my-6 gap-4">
                <div class="form-control w-full lg:flex-row flex-col">
                    <label class="label lg:min-w-[16rem] justify-start gap-2">
                        <span class="label-text">カテゴリ選択</span>
                        <span class="badge badge-error">
                            必須
                        </span>
                    </label>
                    <div class="w-full">
                        <select class="select select-bordered w-full max-w-xs" name="categoryShareId" x-model="categoryShareId.value">
                            <option value=""> --- カテゴリを選択してください --- </option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.categoryShareId" x-text="category.categoryName" :selected="categoryShareId.value === category.categoryShareId"></option>
                            </template>
                        </select>
                        <div class="text-error" x-text="categoryShareId.message"></div>
                    </div>
                </div>
                <div class="form-control w-full lg:flex-row flex-col">
                    <label class="label lg:min-w-[16rem] justify-start gap-2">
                        <span class="label-text">タイトル</span>
                        <span class="badge badge-error">
                            必須
                        </span>
                    </label>
                    <div class="w-full">
                        <input type="text" id="title" name="title" class="input input-bordered w-full"  x-model='title.value' x-class="title.isValid ? 'input-error' : '' ">
                        <div class="text-error" x-text="title.message"></div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap my-6 gap-4">
                <div class="form-control w-full flex-col">
                    <label class="label lg:min-w-[16rem] justify-start gap-2">
                        <span class="label-text">コンテンツ</span>
                        <span class="badge badge-error">
                            必須
                        </span>
                    </label>
                    <div class="w-full">
                        <div class="text-error" x-text="content.message"></div>
                        <div id="editorjs" class="prose editorjs"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="content" id="content" :value="content.value">
            <input type="hidden" name="_method" value="put">
            <button type="button" class="btn btn-primary" @click="save">更新</button>
        </form>
    </div>
    <script>
        function edit(){
            return {
                categories: @json($categories),
                categoryShareId: {
                    value : "{{ $validate['categoryShareId']['value'] ?? $post->categoryShareId }}",
                    isValid : "{{ $validate['categoryShareId']['isValid'] === false }}",
                    message : "{{ $validate['categoryShareId']['message'] }}",
                },
                title: {
                    value : "{{ $validate['title']['value'] ?? $post->title }}",
                    isValid : "{{ $validate['title']['isValid'] === false }}",
                    message : "{{ $validate['title']['message'] }}",
                },
                content: {
                    value : {!! $contentJson !!},
                    isValid : "{{ $validate['content']['isValid'] === false }}",
                    message : "{{ $validate['content']['message'] }}",
                },
                editor: null,
                init() {
                    const self = this;
                    this.editor = new EditorJS({
                        holder: 'editorjs',
                        placeholder: 'No content',
                        data: self.content.value,
                        autofocus: true,
                        tunes: ['textVariant'],
                        tools: {
                            linkTool: {
                                class: LinkTool,
                                config: {
                                    endpoint: '{!! url("url.fetch") !!}', // Your backend endpoint for url data fetching,
                                    header: 'application/x-www-form-urlencoded'
                                }
                            },
                            textVariant: TextVariantTune,
                            code: CodeTool,
                            raw: RawTool,
                            underline: Underline,
                            inlineCode: {
                                class: InlineCode,
                                shortcut: 'CMD+SHIFT+M',
                            },
                            attaches: {
                                class: AttachesTool,
                                config: {
                                    uploader: {
                                        uploadByFile(file){
                                            return new Promise((resolve, reject) => {
                                                const reader = new FileReader();
                                                reader.onload = function (e) {
                                                    const base64 = e.target.result;
                                                    const fileName = file.name;
                                                    const lastIndex = fileName.lastIndexOf('.');
                                                    const extension = lastIndex !== -1 ? fileName.substring(lastIndex + 1) : '';

                                                    const params = new URLSearchParams();
                                                    params.append('_method', 'post');
                                                    params.append('extension', extension);
                                                    params.append('base64', base64);
                                                    params.append('_token', "{{ $this->csrf_token }}");
                                                    fetchApi("{!! url('file.upload') !!}", params)
                                                    .then(data => {
                                                        resolve(data.data);
                                                    }).catch(error => {
                                                        reject(new Error("file extension of file not permitted"));
                                                    })
                                                };
                                                reader.readAsDataURL(file);
                                            });
                                        },
                                    }
                                }
                            },
                            Marker: {
                                class: Marker,
                                shortcut: 'CMD+SHIFT+M',
                            },
                            table: Table,
                            list: {
                                class: NestedList,
                                inlineToolbar: true,
                                config: {
                                    defaultStyle: 'unordered'
                                },
                            },
                            warning: {
                                class: Warning,
                                inlineToolbar: true,
                                shortcut: 'CMD+SHIFT+W',
                                config: {
                                    titlePlaceholder: 'Title',
                                    messagePlaceholder: 'Message',
                                },
                            },
                            postListTool : {
                                class : PostListTool,
                                config: {
                                    categories: this.categories,
                                }
                            },
                            quote: Quote,
                            delimiter: Delimiter,
                            paragraph: {
                                class: Paragraph,
                                inlineToolbar: true,
                            },
                            checklist: {
                                class: Checklist,
                                inlineToolbar: true,
                            },
                            image: {
                                class: ImageTool,
                                config: {
                                    /**
                                     * Custom uploader
                                     */
                                    uploader: {
                                        /**
                                         * Upload file to the server and return an uploaded image data
                                         * @param {File} file - file selected from the device or pasted by drag-n-drop
                                         * @return {Promise.<{success, file: {url}}>}
                                         */
                                        uploadByFile(file){
                                            return new Promise((resolve, reject) => {
                                                const reader = new FileReader();
                                                reader.onload = function (e) {
                                                    const base64 = e.target.result;
                                                    resolve({
                                                        success: 1,
                                                        file: {
                                                            url: base64
                                                        }
                                                    });
                                                };
                                                reader.readAsDataURL(file);
                                            });
                                        },

                                        /**
                                         * Send URL-string to the server. Backend should load image by this URL and return an uploaded image data
                                         * @param {string} url - pasted image URL
                                         * @return {Promise.<{success, file: {url}}>}
                                         */
                                        uploadByUrl(url){
                                            return {
                                                success: 1,
                                                file: {
                                                    url: url,
                                                    // any other image data you want to store, such as width, height, color, extension, etc
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            header: Header,
                            embed: {
                                class: Embed,
                                inlineToolbar: true,
                            }
                        },
                    });
                },
                save(){
                    this.editor.save().then((outputData) => {
                        const form = document.querySelector("form[name=postupdate]");
                        form.addEventListener('formdata', (e) => {
                            let fd = e.formData;
                            fd.set('content', encodeURIComponent(JSON.stringify(outputData)));
                        });
                        // submit
                        form.submit();

                    }).catch((error) => {
                        console.error('エディタの保存エラー: ', error);
                    });
                }
            }
        }
    </script>
@endsection