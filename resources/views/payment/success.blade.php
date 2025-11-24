@extends('layouts.app')

@section('content')
<div class="alert alert-success text-center p-5">
    <h2>شكراً لك!</h2>
    <p>تم استلام تبرعك بنجاح ❤️</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">العودة للصفحة الرئيسية</a>
</div>
@endsection
