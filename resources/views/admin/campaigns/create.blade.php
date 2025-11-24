@extends('layouts.app')
@section('title','حملة جديدة')

@section('content')

<div class="bg-white p-4 shadow rounded">

    <h3 class="fw-bold mb-4">إنشاء حملة جديدة</h3>

    <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">عنوان الحملة</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الهدف (اختياري)</label>
            <input type="number" name="goal_amount" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select">
                <option value="active">نشطة</option>
                <option value="paused">موقوفة</option>
                <option value="completed">مكتملة</option>
                <option value="draft">مسودة</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">صور الحملة</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
        </div>

        <button class="btn btn-primary px-4">إنشاء</button>

    </form>

</div>

@endsection
