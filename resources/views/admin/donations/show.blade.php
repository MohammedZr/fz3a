@extends('layouts.app')

@section('title','تفاصيل التبرع')

@section('content')
<div class="bg-white p-4 rounded shadow">

    <div class="d-flex justify-content-between">
        <h3>تفاصيل التبرع #{{ $donation->id }}</h3>
        <div>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary">العودة للقائمة</a>
        </div>
    </div>

    <hr>

    <div class="row g-3">
        <div class="col-md-6">
            <h5>بيانات التبرع</h5>
            <p><strong>النوع:</strong> {{ $donation->type }}</p>
            <p><strong>المبلغ:</strong> {{ $donation->amount ?? '-' }} {{ $donation->currency }}</p>
            {{-- <p><strong>الحالة:</strong> {{ $donation->status }}</p> --}}
            <p><strong>التاريخ:</strong> {{ $donation->created_at }}</p>
        </div>

        <div class="col-md-6">
            <h5>بيانات المتبرع</h5>
            <p><strong>الاسم:</strong> {{ $donation->is_anonymous ? 'مجهول' : ($donation->donor_name ?? '-') }}</p>
            <p><strong>البريد:</strong> {{ $donation->donor_email ?? '-' }}</p>
            <p><strong>الهاتف:</strong> {{ $donation->donor_phone ?? '-' }}</p>
            <p><strong>مستخدم:</strong> {{ $donation->user?->name ?? '-' }}</p>
        </div>
    </div>

    @if($donation->items && $donation->items->count())
        <hr>
        <h5>عناصر التبرع العيني</h5>
        <ul>
            @foreach($donation->items as $it)
                <li>{{ $it->category }} - {{ $it->quantity ?? '-' }} - {{ $it->condition ?? '-' }}</li>
            @endforeach
        </ul>
    @endif

    @if($donation->attachments && $donation->attachments->count())
        <hr>
        <h5>مرفقات</h5>
        <div class="row g-2">
            @foreach($donation->attachments as $att)
                <div class="col-md-3">
                    <img src="{{ asset('storage/'.$att->path) }}" class="img-fluid rounded">
                </div>
            @endforeach
        </div>
    @endif

    @if($donation->pickupRequest)
        <hr>
        <h5>بيانات الاستلام</h5>
        <p>{{ $donation->pickupRequest->address_line ?? '-' }}</p>
    @endif

    <hr>
    <form action="{{ route('admin.donations.changeStatus', $donation->id) }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="pending" {{ $donation->status=='pending'?'selected':'' }}>معلق</option>
                <option value="paid" {{ $donation->status=='paid'?'selected':'' }}>مدفوع</option>
                <option value="verified" {{ $donation->status=='verified'?'selected':'' }}>مؤكد</option>
                <option value="cancelled" {{ $donation->status=='cancelled'?'selected':'' }}>ملغي</option>
            </select>
        </div>
        <div class="col-md-8">
            <button class="btn btn-success">تغيير الحالة</button>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>

</div>
@endsection
