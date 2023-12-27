@extends('template.dashboard-layout')

@section('body')
<h1 class="text-3xl font-bold text-clifford">
    グローバルメニュー管理
</h1>
<div x-data="menuHandler()" x-init="init">
    <button class="btn w-full my-4" @click="createModal = true">メニュー項目の追加</button>
    <template x-if="createModal">
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">メニュー項目の追加</h3>
                <form @submit.prevent="addItem">
                    <div class="form-control">
                        <label class="label" for="name">名前</label>
                        <input type="text" id="name" class="input input-bordered" x-model="newItem.name">
                    </div>
        
                    <div class="form-control">
                        <label class="label cursor-pointer" for="isFolder">
                            <input type="checkbox" id="isFolder" class="checkbox" x-model="newItem.isFolder">
                            <span class="label-text">フォルダ</span>
                        </label>
                    </div>
        
                    <div class="form-control">
                        <label class="label" for="postShareId">ページ</label>
                        <select x-model="newItem.postShareId" :disabled="newItem.isFolder" class="select select-bordered">
                            <option > --- 選択してください --- </option>
                            <template x-for="post in pages">
                                <option :value="post.postShareId" x-text="post.title"></option>
                            </template>
                        </select>
                    </div>
        
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">追加</button>
                        <button type="button" class="btn" @click="createModal = false">キャンセル</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
    <template x-if="editModal">
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">メニュー項目の編集</h3>
                <form @submit.prevent="editSelectedItem">
                    <div class="form-control">
                        <label class="label" for="editName">名前</label>
                        <input type="text" id="editName" class="input input-bordered" x-model="selectedItem.name">
                    </div>
        
                    <div class="form-control" x-show="!selectedItem.isFolder">
                        <label class="label" for="editPostShareId">ページ</label>
                        <select x-model="selectedItem.postShareId" :disabled="selectedItem.isFolder" class="select select-bordered">
                            <option > --- 選択してください --- </option>
                            <template x-for="post in pages">
                                <option :value="post.postShareId" x-text="post.title" :selected="post.postShareId == selectedItem.postShareId"></option>
                            </template>
                        </select>
                    </div>
        
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">保存</button>
                        <button type="button" class="btn" @click="editModal = false">キャンセル</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
    <template x-if="deleteModal">
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">メニュー項目の削除</h3>
                <p>本当にこのメニュー項目を削除しますか？</p>
                <div class="modal-action">
                    <button type="button" class="btn btn-error" @click="deleteSelectedItem">削除</button>
                    <button type="button" class="btn" @click="deleteModal = false">キャンセル</button>
                </div>
            </div>
        </div>
    </template>
    <ul x-ref="menuList" class="menu menu-lg p-4 overflow-y-auto bg-base-100 text-base-content w-full">
    </ul>
    <div class="my-4 text-center">
        <button type="button" class="btn btn-primary btn-wide mx-auto" @click="save">保存</button>
    </div>
</div>
<script>
function menuHandler() {
return {
    createModal: false,
    editModal: false,
    deleteModal: false,
    selectedItem: null,
    newItem: { id: '' , name: '', isFolder: false, postShareId: '' },
    menuItems: {!! $globalMenuJson !!},
    pages: @json($pages),
    init() {
        this.$refs.menuList.innerHTML = '';
        this.$refs.menuList.innerHTML = this.renderMenu(this.menuItems);
        this.initSortable(this.$refs.menuList, null);
    },
    initSortable(element, parentArray) {
        Sortable.create(element, {
            group: 'nested',
            onEnd: (evt) => {
                this.moveItem(evt.item.dataset.id, evt.to.dataset.id, evt.oldIndex, evt.newIndex);
                window.addEventListener('beforeunload', (event) => {
                    event.preventDefault();
                    event.returnValue = '';
                });
            }
        });
        element.querySelectorAll('ul').forEach((ul) => {
            this.initSortable(ul, element.dataset.id);
        });
    },
    addItem() {
        if (!this.newItem.name) return;
        // 新しい項目をmenuItemsに追加
        this.menuItems.push({
            id: Date.now(), // 一意のIDを生成
            name: this.newItem.name,
            postShareId: this.newItem.postShareId,
            isFolder: this.newItem.isFolder,
            children: this.newItem.isFolder ? [] : null
        });

        // フォームをリセット
        this.newItem = { name: '', isFolder: false, postShareId: '' };
        this.createModal = false;
        this.init();
    },
    showEditModal(item) {
        this.selectedItem = item;
        this.editModal = true;
    },
    editSelectedItem() {
        this.editItem(this.menuItems, this.selectedItem);
        this.editModal = false;
        this.init();
    },
    editItem(items,editItem) {
        for (let i = 0; i < items.length; i++) {
            if (items[i].id === editItem.id) {
                items[i] = { ...editItem };
                return true;
            }

            if (items[i].children && items[i].children.length) {
                if (this.editItem(items[i].children, editItem)) {
                    return true;
                }
            }
        }
        return false;
    },
    // 削除モーダルの表示
    showDeleteModal(item) {
        this.selectedItem = item;
        this.deleteModal = true;
    },
    // 削除の処理
    deleteSelectedItem() {
        this.deleteItem(this.menuItems, this.selectedItem.id);
        this.deleteModal = false;
        this.init();
    },
    deleteItem(items, itemId) {
        for (let i = 0; i < items.length; i++) {
            if (items[i].id === itemId) {
                items.splice(i, 1);
                return true;
            }

            if (items[i].children && items[i].children.length) {
                if (this.deleteItem(items[i].children, itemId)) {
                    return true;
                }
            }
        }
        return false;
    },
    moveItem(itemId, newParentId, oldIndex, newIndex) {
        // 項目を見つけて削除
        let itemToMove = null;
        let findAndRemoveItem = (items, itemId) => {
            return items.filter(item => {
                if (item.id == itemId) {
                    itemToMove = item;
                    return false;
                }
                if (item.children) {
                    item.children = findAndRemoveItem(item.children, itemId);
                }
                return true;
            });
        };
        this.menuItems = findAndRemoveItem(this.menuItems, itemId);
        // 新しい位置に項目を挿入
        let insertItem = (items, newItem, newParentId, newIndex) => {
            if (newParentId == null) {
                items.splice(newIndex, 0, newItem);
                return;
            }
            for (let item of items) {
                if (item.id == newParentId) {
                    item.children.splice(newIndex, 0, newItem);
                    return;
                }
                if (item.children) {
                    insertItem(item.children, newItem, newParentId, newIndex);
                }
            }
        };
        insertItem(this.menuItems, itemToMove, newParentId, newIndex);
        this.init();
    },
    save() {
        loading.showModal();
        const params = new URLSearchParams();
        params.append('_method', 'post');
        params.append('items', encodeURIComponent(JSON.stringify(this.menuItems)));
        params.append('_token', "{{ $this->csrf_token }}");
        fetchApi("{!! url('menu.store' , ['projectId' => $projectId]) !!}", params)
        .then(data => {
            let store = Alpine.store('success');
            store.message = `更新が完了しました`; 
            store.openModal();
        })
        .catch((error) => {
            console.error('Error:', error);
        }).finally(() => {
            loading.close();
        });
    },
    renderMenu(items) {
        return items.map(item => `
            <li class="${item.isFolder ? 'folder' : ''} menu-items" data-id="${item.id}">
                <a>
                    ${item.isFolder ? 
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>' 
                    : ' <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'}
                ${item.name}
                <button type="button" class="btn btn-warning" @click='showEditModal(${JSON.stringify(item)})'>編集</button>
                ${ ( (item.isFolder && item.children?.length == 0) || !item.isFolder) ? `<button type="button" class="btn btn-error" @click='showDeleteModal(${JSON.stringify(item)})'>削除</button>` : ''}
                </a>
                ${item.isFolder && item.children ? `<ul data-id="${item.id}">${this.renderMenu(item.children)}</ul>` : ''}
            </li>
        `).join('');
    }
};
}
</script>    
@endsection