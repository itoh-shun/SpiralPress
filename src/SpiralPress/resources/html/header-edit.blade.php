@extends('template.base')

@section('content')
<div x-data="{ title: 'マイ管理画面', editMode: false }">
    <div class="content mt-5 p-3">
        <div id="editorjs"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editor = new EditorJS({
            holder: 'editorjs',
            placeholder: 'No content',
            data: {},
            tools:  {
                header: Header,
                list: List,
                quote: Quote,
                marker: Marker,
                delimiter: Delimiter,
                inlineCode: InlineCode,
                image: SimpleImage,
                checklist: Checklist,
                warning: Warning,
                code: CodeTool,
                linkTool: LinkTool,
                embed: Embed,
                table: Table,
                textVariant: TextVariantTune,
                raw: RawTool,
                alert: {
                    class: Alert,
                    inlineToolbar: true,
                    shortcut: 'CMD+SHIFT+A',
                    config: {
                        defaultType: 'primary',
                        messagePlaceholder: 'Enter something',
                    },
                },
                AnyButton: {
                    class: AnyButton,
                    inlineToolbar: false,
                    config:{
                        css:{
                        "btnColor": "btn--gray",
                        }
                    }
                },
            },
            onReady: () => {
                new Undo({ editor });
            },
            tunes: ['textVariant'],
            i18n: {
                messages: {
                    tools: {
                        "AnyButton": {
                            'Button Text': 'ボタンに表示するテキスト',
                            'Link Url': 'ボタンの飛び先のURL',
                            'Set': "設定する",
                            'Default Button': "デフォルト",
                        }
                    }
                },
            },
        });
    });
</script>
@endsection