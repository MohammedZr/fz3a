@extends('layouts.app')

@section('title','لوحة التحكم')

@section('content')
<div class="container-fluid">

    <div class="bg-white p-4 shadow rounded mb-4">
        <h2 class="h4 fw-bold">لوحة التحكم</h2>
        <p class="text-muted">مرحبًا {{ auth()->user()->name }} 👋</p>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="p-4 bg-primary text-white rounded shadow">
                <h3>{{ \App\Models\Campaign::count() }}</h3>
                <p>عدد الحملات</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-success text-white rounded shadow">
                <h3>{{ \App\Models\Donation::count() }}</h3>
                <p>عدد التبرعات</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-info text-white rounded shadow">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>عدد المستخدمين</p>
            </div>
        </div>
        <div class="col-md-4">
    <a href="{{ route('admin.users.index') }}">
        <div class="p-4 rounded bg-dark text-white shadow-sm">
            <h3 class="mb-1">{{ \App\Models\User::count() }}</h3>
            <p>إدارة المستخدمين</p>
        </div>
    </a>
</div>


    </div>

</div>
@endsection
