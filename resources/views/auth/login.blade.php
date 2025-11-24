@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-center fw-bold mb-4">تسجيل الدخول</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <small>{{ $errors->first() }}</small>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@mail.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label for="remember" class="form-check-label">تذكرني</label>
                </div>

                <button class="btn btn-primary w-100">دخول</button>

                <p class="mt-3 text-center">
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}">إنشاء حساب جديد</a>
                </p>

            </form>
        </div>

    </div>
</div>

@endsection
