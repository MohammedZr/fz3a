@extends('layouts.app')

@section('title', 'إنشاء حساب')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-center fw-bold mb-4">إنشاء حساب</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <small>{{ $errors->first() }}</small>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" class="form-control" name="name" required placeholder="الاسم">
                </div>

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" required placeholder="example@mail.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" required placeholder="••••••">
                </div>

                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation" required placeholder="••••••">
                </div>

                <button class="btn btn-primary w-100">إنشاء حساب</button>

                <p class="mt-3 text-center">
                    لديك حساب؟ <a href="{{ route('login') }}">سجّل دخول</a>
                </p>

            </form>
        </div>

    </div>
</div>

@endsection
