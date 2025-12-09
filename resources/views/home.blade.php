@extends('layouts.app')

@section('title','فزعة — منصّة جمع التبرعات')

@section('content')

{{-- ============================= --}}
{{--         HERO SECTION          --}}
{{-- ============================= --}}
<section class="py-5">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold mb-3">ساهم في صناعة الخير مع <span class="text-primary">فزعة</span></h1>

            <p class="lead text-muted mb-4">
                منصة تبرعات آمنة وسهلة تجمع بين المحتاجين والمساهمين.  
                يمكنك التبرع بالمال، الملابس، الأجهزة، أو أي شيء يمكنه أن يُحدث فرقًا.
            </p>

            @if(auth()->check())
    {{-- المستخدم مسجّل دخول --}}
    <a href="{{ route('donations.create') }}" class="btn btn-primary btn-lg px-4 me-2">
        تبرّع الآن
    </a>
@else
    {{-- المستخدم غير مسجّل --}}
    <a href="#" onclick="requireLogin()" class="btn btn-primary btn-lg px-4 me-2">
        تبرّع الآن
    </a>
@endif
<script>
function requireLogin() {
    alert("يجب تسجيل الدخول قبل القيام بعملية التبرّع.");
    window.location.href = "{{ route('register') }}";
}
</script>

            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary btn-lg px-4">استعرض الحملات</a>
        </div>

        <div class="col-lg-6 text-center mt-4 mt-lg-0">
            {{-- Lottie Animation --}}
            <dotlottie-wc 
                src="https://lottie.host/6770d87e-8587-4871-aff5-63ebd318accc/3zxHrOj15l.lottie" 
                style="width: 450px; height: 450px;"
                autoplay
                loop>
            </dotlottie-wc>
        </div>
    </div>
</section>

{{-- ================================== --}}
{{--      ANNOUNCEMENT POSTER            --}}
{{-- ================================== --}}
<div class="w-100 py-5 mb-5"
     style="
        background: linear-gradient(to right, #1f3b73cc, #3c5faacc),
                    url('/images/poster-bg.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        color: white;
     ">

    <div class="container text-center">

        <h1 class="fw-bold mb-3" style="font-size: 2.4rem;">
            🚀 إطلاق حملة تبرعات جديدة الآن!
        </h1>

        <p class="mb-4" style="font-size: 1.2rem;">
            كن جزءًا من الخير وساهم معنا في دعم الحالات الإنسانية 👏
        </p>

        <a href="{{ route('campaigns.index') }}"
           class="btn btn-light btn-lg px-5 py-2"
           style="font-weight: bold; font-size: 1.2rem;">
            عرض الحملات
        </a>

    </div>

</div>

<hr class="my-5">

{{-- ============================= --}}
{{--     SECTION: HOW IT WORKS     --}}
{{-- ============================= --}}
<section class="py-5">
    <h2 class="h3 text-center fw-bold mb-4">كيف تعمل منصة فزعة؟</h2>

    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <div class="mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" width="60">
                </div>
                <h5 class="fw-bold">1. اختر نوع تبرعك</h5>
                <p class="text-muted">يمكنك التبرع بالمال أو بالأشياء مثل الملابس، الأجهزة، الأثاث وغيرها.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <div class="mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/1584/1584203.png" width="60">
                </div>
                <h5 class="fw-bold">2. املأ بيانات التبرع</h5>
                <p class="text-muted">ضع بيانات بسيطة وسيقوم فريقنا بالتواصل معك عند الحاجة.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <div class="mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/1048/1048953.png" width="60">
                </div>
                <h5 class="fw-bold">3. يصل التبرع لمن يستحق</h5>
                <p class="text-muted">فريق فزعة يتأكد من وصول التبرعات للمستفيدين بشكل منظم وموثوق.</p>
            </div>
        </div>
    </div>
</section>

<hr class="my-5">

{{-- ============================= --}}
{{--      ACTIVE CAMPAIGNS         --}}
{{-- ============================= --}}
<section class="py-5">
    <h2 class="h3 text-center fw-bold mb-4">الحملات النشطة</h2>

    <div class="row g-4">
        @foreach($campaigns ?? [] as $c)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $c->title }}</h5>
                    <p class="text-muted small">{{ Str::limit($c->description, 100) }}</p>

                    @if($c->goal_amount)
                    <div class="progress mb-2">
                        @php 
                            $p = min(100, ($c->raised_amount / max(1,$c->goal_amount)) * 100); 
                        @endphp
                        <div class="progress-bar" style="width: {{ number_format($p,2) }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ number_format($c->raised_amount,2) }} / {{ number_format($c->goal_amount,2) }} LYD
                    </small>
                    @endif

                    <a href="{{ route('campaigns.show', $c) }}" class="btn btn-primary w-100 mt-3">عرض الحملة</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(empty($campaigns) || count($campaigns) == 0)
        <p class="text-center text-muted mt-3">لا توجد حملات حالياً.</p>
    @endif
</section>

<hr class="my-5">

{{-- ============================= --}}
{{--         STATISTICS            --}}
{{-- ============================= --}}
<section class="py-5 text-center">
    <h2 class="h3 fw-bold mb-4">إحصائيات فزعة</h2>

    <div class="row g-4 justify-content-center">
        <div class="col-md-3">
            <div class="p-4 bg-white shadow rounded">
                <h2 class="fw-bold text-primary">{{ $stats['donations'] ?? 0 }}</h2>
                <p class="text-muted">تبرعات</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 bg-white shadow rounded">
                <h2 class="fw-bold text-primary">{{ $stats['campaigns'] ?? 0 }}</h2>
                <p class="text-muted">حملات</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 bg-white shadow rounded">
                <h2 class="fw-bold text-primary">{{ $stats['donors'] ?? 0 }}</h2>
                <p class="text-muted">متبرعون</p>
            </div>
        </div>
    </div>
</section>

<hr class="my-5">

{{-- ============================= --}}
{{--          FOOTER              --}}
{{-- ============================= --}}
<footer class="py-4 text-center text-muted">
    <p>© {{ date('Y') }} — منصة <strong>فزعة</strong> لجمع التبرعات</p>
</footer>

@endsection
