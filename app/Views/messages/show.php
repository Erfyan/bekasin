<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-6 min-h-[calc(100vh-120px)] flex flex-col">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 flex flex-col">
        
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-4">
            <a href="<?= base_url('messages') ?>" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-emerald-600 hover:bg-slate-100 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900"><?= esc($partner['full_name'] ?? 'Obrolan Pengguna') ?></h1>
                <p class="text-xs text-slate-500">Aktif di Bekasin-Aja</p>
            </div>
        </div>

        <!-- Main Chat Box Container -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col flex-1 min-h-[550px]">
            
            <!-- Top Product Attached Card (if any) -->
            <?php if ($product): ?>
                <div class="p-3.5 bg-emerald-50/80 border-b border-emerald-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-emerald-200/50 flex items-center justify-center text-emerald-800 shrink-0">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 tracking-wider">Membahas Produk:</span>
                            <h4 class="text-xs font-bold text-slate-900 truncate"><?= esc($product['title']) ?></h4>
                            <span class="text-xs font-extrabold text-emerald-600">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <a href="<?= base_url('products/' . $product['slug']) ?>" target="_blank" class="px-3 py-1.5 bg-white hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-200 transition-colors shrink-0">
                        Lihat Barang ↗
                    </a>
                </div>
            <?php endif; ?>

            <!-- Message Bubbles Container -->
            <div id="chat-messages-container" class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/50 max-h-[420px]">
                <?php 
                $myId = (int) session()->get('user_id');
                $lastMsgId = 0;
                foreach ($messages as $m): 
                    $isMe = ($m['sender_id'] == $myId);
                    $lastMsgId = max($lastMsgId, (int)$m['id']);
                ?>
                    <div class="flex <?= $isMe ? 'justify-end' : 'justify-start' ?>">
                        <div class="max-w-md sm:max-w-lg <?= $isMe ? 'bg-emerald-600 text-white rounded-2xl rounded-tr-xs shadow-xs' : 'bg-white text-slate-800 border border-slate-200/90 rounded-2xl rounded-tl-xs shadow-xs' ?> px-4 py-3">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap"><?= esc($m['message_text']) ?></p>
                            <span class="block text-[10px] mt-1 <?= $isMe ? 'text-emerald-200 text-right' : 'text-slate-400' ?>">
                                <?= date('H:i', strtotime($m['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Chat Input Footer -->
            <form id="form-send-message" action="<?= base_url('messages/' . $conversation['id'] . '/send') ?>" method="POST" class="p-4 bg-white border-t border-slate-100 flex items-center gap-3">
                <?= csrf_field() ?>
                <input 
                    type="text" 
                    id="message-input"
                    name="message_text" 
                    placeholder="Tulis pesan atau tanya kondisi barang ke <?= esc($partner['full_name'] ?? 'penjual') ?>..." 
                    class="flex-1 px-4 py-3 text-sm bg-slate-100 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                    required
                    autocomplete="off"
                >
                <button type="submit" class="w-12 h-12 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-md shadow-emerald-600/20 active:scale-95 transition-all shrink-0">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('chat-messages-container');
    const form = document.getElementById('form-send-message');
    const input = document.getElementById('message-input');
    let lastId = <?= $lastMsgId ?>;
    const myId = <?= (int) session()->get('user_id') ?>;

    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }
    scrollToBottom();

    // AJAX Send Form
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        const formData = new FormData(form);
        input.value = '';

        // Optimistic UI Append
        const tempDiv = document.createElement('div');
        tempDiv.className = 'flex justify-end';
        tempDiv.innerHTML = `
            <div class="max-w-md bg-emerald-600 text-white rounded-2xl rounded-tr-xs shadow-xs px-4 py-3">
                <p class="text-sm leading-relaxed">${escapeHtml(text)}</p>
                <span class="block text-[10px] mt-1 text-emerald-200 text-right">Baru saja</span>
            </div>
        `;
        container.appendChild(tempDiv);
        scrollToBottom();

        try {
            await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            pollNewMessages();
        } catch (err) {
            console.error('Send message failed', err);
        }
    });

    // Polling Function
    async function pollNewMessages() {
        try {
            const res = await fetch(`<?= base_url('api/messages/' . $conversation['id'] . '/poll') ?>?after_id=${lastId}`);
            const data = await res.json();

            if (data.success && data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    lastId = Math.max(lastId, msg.id);
                    if (parseInt(msg.sender_id) !== myId) {
                        const div = document.createElement('div');
                        div.className = 'flex justify-start';
                        div.innerHTML = `
                            <div class="max-w-md bg-white text-slate-800 border border-slate-200/90 rounded-2xl rounded-tl-xs shadow-xs px-4 py-3">
                                <p class="text-sm leading-relaxed">${escapeHtml(msg.message_text)}</p>
                                <span class="block text-[10px] mt-1 text-slate-400">Baru saja</span>
                            </div>
                        `;
                        container.appendChild(div);
                    }
                });
                scrollToBottom();
            }
        } catch (err) {
            console.error('Polling error', err);
        }
    }

    function escapeHtml(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
        );
    }

    // Interval poll every 3.5 seconds
    setInterval(pollNewMessages, 3500);
});
</script>
<?= $this->endSection() ?>
