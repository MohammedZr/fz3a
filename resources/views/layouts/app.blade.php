<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','فزعة')</title>
  <!-- Bootstrap 5 RTL CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>body{background:#f9fafb}</style>
  @stack('head')
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<!-- Lottie Player -->
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

<style>
    body { 
        font-family: 'Tajawal', sans-serif !important; 
        background: #f9fafb;
    }
</style>

</head>
<body>
 <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
<a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
    <img src="{{ asset('images/logo.png') }}" 
         alt="فزعة" 
         style="height: 72px; width:auto; margin-left: 6px;">
</a>

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
<li class="nav-item">
    <a class="nav-link" href="{{ route('donations.my') }}">تبرعاتي</a>
</li>

          {{-- صفحات عامة --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('campaigns.index') }}">الحملات</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('donations.create') }}">تبرّع الآن</a>
          </li>
@auth
    @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
        </li>
            <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.donations.index') }}">إدارة التبرعات</a>
    </li>
    @endif
    

    <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button class="btn btn-link nav-link">خروج</button>
        </form>
    </li>
@else
    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">دخول</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">تسجيل</a></li>
@endauth


        </ul>
      </div>
    </div>
</nav>


  <main class="container py-4">@yield('content')</main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  {{-- Chat Support Bubble --}}
<div id="chatBubble" 
    style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 65px;
        height: 65px;
        background: #0d6efd;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        z-index: 9999;
    ">
    💬
</div>

{{-- Chat Window --}}
<div id="chatWindow" 
    style="
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 320px;
        height: 420px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.20);
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 9999;
    ">
    
    {{-- Header --}}
    <div style="background:#0d6efd; color:#fff; padding:12px; font-weight:bold;">
        دعم فزعة ✦
        <span id="closeChat" style="float:left; cursor:pointer;">✖</span>
    </div>

    {{-- Messages --}}
    <div id="chatMessages" style="flex:1; padding:10px; overflow-y:auto; background:#f7f7f7;"></div>

    {{-- Input --}}
    <div style="padding:10px; border-top:1px solid #ddd;">
        <input id="chatInput" type="text" class="form-control" placeholder="اكتب رسالتك…">
        <button id="chatSend" class="btn btn-primary w-100 mt-2">إرسال</button>
    </div>
</div>
@push('scripts')
<script>

let chatVisible = false;

// فتح / إغلاق الشات
document.getElementById('chatBubble').onclick = () => {
    chatVisible = !chatVisible;
    document.getElementById('chatWindow').style.display = chatVisible ? 'flex' : 'none';
};

document.getElementById('closeChat').onclick = () => {
    document.getElementById('chatWindow').style.display = 'none';
    chatVisible = false;
};

// إرسال رسالة
document.getElementById('chatSend').onclick = sendMessage;

function sendMessage() {
    let msg = document.getElementById('chatInput').value;
    if (!msg.trim()) return;

    fetch("{{ route('chat.send') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ message: msg })
    }).then(() => {
        document.getElementById('chatInput').value = "";
        fetchMessages();
    });
}

// جلب الرسائل
function fetchMessages() {
    fetch("{{ route('chat.fetch', 1) }}") // 1 = chat_id
    .then(res => res.json())
    .then(data => {
        let box = document.getElementById('chatMessages');
        box.innerHTML = "";

        data.forEach(m => {
            box.innerHTML += `
                <div style="text-align:${m.sender_id == {{ auth()->id() ?? 0 }} ? 'right' : 'left'}; margin-bottom:7px;">
                    <span style="
                        background:${m.sender_id == {{ auth()->id() ?? 0 }} ? '#0d6efd' : '#e9ecef'};
                        color:${m.sender_id == {{ auth()->id() ?? 0 }} ? 'white' : 'black'};
                        padding:6px 10px;
                        border-radius:10px;
                        display:inline-block;
                        max-width:80%;
                    ">
                        ${m.message}
                    </span>
                </div>
            `;
        });

        box.scrollTop = box.scrollHeight;
    });
}

// تحديث تلقائي كل 2 ثانية
setInterval(fetchMessages, 2000);

</script>

</body>
</html>
