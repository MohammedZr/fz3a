@extends('layouts.app')
@section('title','تعديل الحملة')

@section('content')

<div class="bg-white p-4 shadow rounded">

    <h3 class="fw-bold mb-4">تعديل الحملة</h3>

    <form action="{{ route('admin.campaigns.update',$campaign->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">عنوان الحملة</label>
            <input type="text" name="title" class="form-control" value="{{ $campaign->title }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea name="description" class="form-control" rows="5" required>{{ $campaign->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الهدف (اختياري)</label>
            <input type="number" name="goal_amount" class="form-control" value="{{ $campaign->goal_amount }}">
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select">
                <option value="active" {{ $campaign->status=='active'?'selected':'' }}>نشطة</option>
                <option value="paused" {{ $campaign->status=='paused'?'selected':'' }}>موقوفة</option>
                <option value="completed" {{ $campaign->status=='completed'?'selected':'' }}>مكتملة</option>
                <option value="draft" {{ $campaign->status=='draft'?'selected':'' }}>مسودة</option>
            </select>
        </div>

        <hr>

        <h5 class="fw-bold">إضافة صور جديدة</h5>

        <div class="mb-3">
            <input type="file" name="attachments[]" class="form-control" multiple>
        </div>

        <button class="btn btn-primary px-4">تحديث</button>

    </form>

</div>

@endsection
