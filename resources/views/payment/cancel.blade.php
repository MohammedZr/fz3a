@extends('layouts.app')

@section('content')
<div class="alert alert-danger text-center p-5">
    <h2>فشل الدفع</h2>
    <p>تم إلغاء العملية. يمكنك المحاولة مجددًا.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">العودة للصفحة الرئيسية</a>
</div>
@endsection
