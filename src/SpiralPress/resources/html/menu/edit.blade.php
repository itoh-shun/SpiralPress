@extends('template.dashboard-layout')

@section('body')
    <h1 class="text-3xl font-bold text-clifford">
        ダッシュボード
    </h1>
<div x-data="menuHandler()" x-init="init">
    <ul x-ref="menuList" class="menu menu-horizontal menu-lg p-4 overflow-y-auto bg-base-100 text-base-content w-full" x-html="renderMenu(items)">
    </ul>
</div>
<script>
function menuHandler() {
return {
    items: [
        {
            id: 1,
            name: 'Home',
            url: "https://github.com/",
            children: []
        },
        {
            id: 2,
            name: 'About',
            url: "https://github.com/",
            children: []
        },
        {
            id: 3,
            isFolder: true,
            name: 'Services',
            url: "https://github.com/",
            children: [
                {
                    id: 4,
                    name: 'Web Design',
                    url: "https://github.com/",
                    children: []
                },
                {
                    id: 5,
                    name: 'Hosting',
                    url: "https://github.com/",
                    children: []
                }
            ]
        },
        {
            id: 6,
            isFolder: true,
            url: "https://github.com/",
            name: 'Contact',
            children: []
        }
    ],
    init() {
        this.initializeSortable(this.$refs.menuList);
    },

    initializeSortable(element) {
        Sortable.create(element, {
            fallbackOnBody: true,
            swapThreshold: 0.05,
            group: 'menu-items', 
            animation: 300, 
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: (evt) => {
                // 並べ替えが完了したときの処理
            },
        });

        // isFolder が true のアイテムに対して再帰的にSortableを初期化
        element.querySelectorAll('.folder').forEach(folder => {
            const subList = folder.querySelector('ul');
            if (subList) {
                this.initializeSortable(subList);
            }
        });
    },
    renderMenu(items) {
        return items.map(item => `
            <li class="${item.isFolder ? 'folder' : ''} menu-items">
                <a>
                    ${item.isFolder ? 
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>' 
                    : ' <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'}
                ${item.name}</a>
                ${item.children ? `<ul>${this.renderMenu(item.children)}</ul>` : ''}
            </li>
        `).join('');
    }
};
}
</script>    
@endsection