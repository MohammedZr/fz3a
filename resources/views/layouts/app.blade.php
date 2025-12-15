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
        <a class="nav-link" href="{{ route('donations.my') }}">تبرعاتي</a>
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
</body>
</html>
