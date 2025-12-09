@extends('layouts.app')

@section('content')
<div class="container">
    

    <h3 class="mb-3 d-flex align-items-center">
        الدردشة مع فريق فزعة

        {{-- حالة المشرف --}}
        <span id="adminStatus" class="ms-3 badge bg-secondary">
            جاري التحديث...
        </span>
    </h3>

    {{-- CHAT BOX --}}
    <div id="chatBox"
        style="height: 400px; overflow-y: auto; border:1px solid #ddd; padding:15px; background:#f8f9fa; border-radius:10px;">
        
        <div id="loading" class="text-center text-muted">جارِ تحميل الرسائل...</div>

        @foreach($messages as $msg)
            <div class="mb-2 {{ $msg->sender_id == auth()->id() ? 'text-end' : 'text-start' }}">
                <p class="px-3 py-2 d-inline-block rounded-3 shadow-sm 
                    {{ $msg->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}">
                    {{ $msg->message }}
                </p>
            </div>
        @endforeach

    </div>

    {{-- SEND MESSAGE --}}
    <form id="chatForm" class="mt-3">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="اكتب رسالتك..." required>
            <button class="btn btn-primary px-4">إرسال</button>
        </div>
    </form>

</div>
@endsection


@push('scripts')
<script>

// =========================
//   تحديث حالة المشرف Online/Offline
// =========================
function updateAdminStatus() {
    fetch("{{ route('admin.status') }}") 
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('adminStatus');

            if (data.online) {
                badge.className = "badge bg-success";
                badge.innerText = "المسؤول متصل الآن";
            } else {
                badge.className = "badge bg-danger";
                badge.innerText = "المسؤول غير متصل";
            }
        });
}
setInterval(updateAdminStatus, 5000);
updateAdminStatus();


// =========================
//       Fetch Messages
// =========================
setInterval(fetchMessages, 2000);

function fetchMessages() {
    fetch("{{ route('chat.fetch', $chat->id) }}")
        .then(res => res.json())
        .then(data => {
            let box = document.getElementById('chatBox');
            let html = "";

            data.forEach(m => {
                html += `
                    <div class="mb-2 ${m.sender_id == {{ auth()->id() }} ? 'text-end' : 'text-start'}">
                        <p class="px-3 py-2 d-inline-block rounded-3 shadow-sm 
                           ${m.sender_id == {{ auth()->id() }} 
                                ? 'bg-primary text-white' 
                                : 'bg-white border'}">
                            ${m.message}
                        </p>
                    </div>
                `;
            });

            box.innerHTML = html;
            box.scrollTop = box.scrollHeight;
        });
}


// =========================
//      SEND MESSAGE
// =========================

document.getElementById('chatForm').onsubmit = function(e){
    e.preventDefault();

    let formData = new FormData(this);

    // منع إرسال رسائل فارغة
    if (this.message.value.trim() === "") return;

    fetch("{{ route('chat.send') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(() => {
        this.message.value = "";
        fetchMessages();
    });

};
</script>
@endpush
