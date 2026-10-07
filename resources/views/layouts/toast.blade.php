{{-- Floating Universal Toast Notification Engine --}}
<div 
    x-data="{
        toasts: [],
        addToast(toast) {
            const id = Date.now() + Math.random();
            const type = toast.type || 'success';
            let title = toast.title;
            if (!title) {
                if (type === 'success') title = 'Berhasil';
                else if (type === 'error') title = 'Terjadi Kesalahan';
                else if (type === 'warning') title = 'Peringatan';
                else title = 'Informasi';
            }
            const duration = toast.duration || 4500;
            const newToast = {
                id,
                title,
                message: toast.message || '',
                type,
                progress: 100,
                interval: null,
                paused: false
            };
            
            this.toasts.push(newToast);

            // Progress timer
            const step = 20;
            const totalSteps = duration / step;
            const stepPercent = 100 / totalSteps;

            newToast.interval = setInterval(() => {
                if (!newToast.paused) {
                    newToast.progress -= stepPercent;
                    if (newToast.progress <= 0) {
                        this.removeToast(newToast.id);
                    }
                }
            }, step);
        },
        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                clearInterval(this.toasts[index].interval);
                this.toasts.splice(index, 1);
            }
        },
        pauseToast(toast) {
            toast.paused = true;
        },
        resumeToast(toast) {
            toast.paused = false;
        }
    }"
    @toast.window="addToast($event.detail)"
    x-init="
        window.showToast = (message, type = 'success', title = '') => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type, title } }));
        };
    "
    class="fixed bottom-6 right-4 sm:right-6 z-50 flex flex-col-reverse gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            @mouseenter="pauseToast(toast)"
            @mouseleave="resumeToast(toast)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto w-full bg-white rounded-2xl shadow-xl border overflow-hidden transition-all duration-200 group"
            :class="{
                'border-emerald-200 shadow-emerald-500/5': toast.type === 'success',
                'border-rose-200 shadow-rose-500/5': toast.type === 'error',
                'border-amber-200 shadow-amber-500/5': toast.type === 'warning',
                'border-sky-200 shadow-sky-500/5': toast.type === 'info'
            }"
        >
            <div class="p-4 flex items-start gap-3.5">
                <!-- Icon Badge -->
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 shadow-2xs"
                     :class="{
                         'bg-emerald-500 text-white': toast.type === 'success',
                         'bg-rose-500 text-white': toast.type === 'error',
                         'bg-amber-500 text-white': toast.type === 'warning',
                         'bg-sky-500 text-white': toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </template>
                </div>

                <!-- Content Text -->
                <div class="flex-1 min-w-0 pt-0.5">
                    <h4 class="text-xs font-bold text-slate-900 leading-tight" x-text="toast.title"></h4>
                    <p class="text-[11px] text-slate-600 mt-1 leading-snug" x-text="toast.message"></p>
                </div>

                <!-- Close Button -->
                <button 
                    type="button" 
                    @click="removeToast(toast.id)" 
                    class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition-colors flex-shrink-0"
                    title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Animated Shrinking Timer Bar -->
            <div class="w-full bg-slate-100 h-1 overflow-hidden">
                <div 
                    class="h-full transition-all duration-75 ease-linear"
                    :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-rose-500': toast.type === 'error',
                        'bg-amber-500': toast.type === 'warning',
                        'bg-sky-500': toast.type === 'info'
                    }"
                    :style="`width: ${toast.progress}%;`">
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Trigger Laravel Flash Session Toasts on Load --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            if (window.showToast) {
                window.showToast(@json(session('success')), 'success', 'Berhasil!');
            }
        @endif

        @if (session('error'))
            if (window.showToast) {
                window.showToast(@json(session('error')), 'error', 'Peringatan');
            }
        @endif

        @if (session('status'))
            if (window.showToast) {
                window.showToast(@json(session('status')), 'info', 'Status');
            }
        @endif

        @if ($errors->any())
            if (window.showToast) {
                window.showToast(@json($errors->first()), 'error', 'Validasi Gagal');
            }
        @endif
    });
</script>
