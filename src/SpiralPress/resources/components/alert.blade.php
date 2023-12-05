
<dialog id="danger" class="bg-transparent md:w-full">
    <div class="relative rounded-xl overflow-auto p-8">
        <div class="flex justify-center leading-6">
            <div class="card w-96 bg-base-100 text-base-content shadow-md">
                <div class="p-4 w-14 left-1/2 -translate-x-1/2 h-14 absolute -top-6 flex items-center justify-center rounded-full bg-base-100 text-error ">
                    <span class="material-symbols-outlined text-5xl">
                        exclamation
                    </span>
                </div>
                <div class="card-body items-center text-center mt-4 text-error">
                    <h2 class="card-title">エラーが発生しました</h2>
                    <p x-text="message"></p>
                    <div class="card-actions justify-end">
                        <form method="dialog">
                            <button class="btn btn-ghost btn-sm btn-wide" @click="$store.success.closeModal()">閉じる</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</dialog>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('danger', {
            message: '',
            openModal() {
                success.showModal();
            },
            closeModal() {
                success.close();
            }
        });
    });
</script>
<dialog id="success" class="bg-transparent md:w-full">
    <div class="relative rounded-xl overflow-auto p-8">
        <div class="flex justify-center leading-6">
            <div class="card w-96 bg-base-100 text-base-content shadow-md">
                <div class="p-4 w-14 left-1/2 -translate-x-1/2 h-14 absolute -top-6 flex items-center justify-center rounded-full bg-base-100 text-success ">
                    <span class="material-symbols-outlined text-5xl">
                        task_alt
                    </span>
                </div>
                <div class="card-body items-center text-center mt-4">
                    <h2 class="card-title">完了しました！</h2>
                    <p x-text="message"></p>
                    <div class="card-actions justify-end">
                        <form method="dialog">
                            <button class="btn btn-primary btn-sm btn-wide" @click="$store.success.closeModal()">閉じる</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</dialog>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('success', {
            message: '',
            openModal() {
                success.showModal();
            },
            closeModal() {
                success.close();
            }
        });
    });
</script>