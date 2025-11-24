@extends('layouts.app')

@section('title','إدارة التبرعات')

@section('content')
<div class="bg-white p-4 rounded shadow">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">إدارة التبرعات</h3>
        <small class="text-muted">إجمالي: {{ $donations->total() }}</small>
    </div>

    {{-- فلتر --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.donations.index') }}">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>معلق</option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>مدفوع</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>ملغي</option>
                <option value="verified" {{ request('status')=='verified'?'selected':'' }}>مؤكد</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">كل الأنواع</option>
                <option value="cash" {{ request('type')=='cash'?'selected':'' }}>مالي</option>
                <option value="goods" {{ request('type')=='goods'?'selected':'' }}>عيني</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="campaign" class="form-select">
                <option value="">كل الحملات</option>
                @foreach($campaigns as $c)
                    <option value="{{ $c->id }}" {{ request('campaign') == $c->id ? 'selected' : '' }}>
                        {{ $c->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="بحث باسم أو بريد أو ID" value="{{ request('q') }}">
        </div>

        <div class="col-md-1 d-grid">
            <button class="btn btn-outline-primary">فلتر</button>
        </div>
    </form>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نوع</th>
                    <th>المبلغ</th>
                    <th>المتبرع</th>
                    <th>حملة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th width="220">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>
                        @if($d->type==='cash') <span class="badge bg-success">مالي</span>
                        @else <span class="badge bg-info">عيني</span>
                        @endif
                    </td>
                    <td>{{ $d->amount ? number_format($d->amount,2) . ' ' . ($d->currency??'LYD') : '-' }}</td>
                    <td>
                        {{ $d->is_anonymous ? 'مجهول' : ($d->donor_name ?: '-') }}<br>
                        <small class="text-muted">{{ $d->donor_email }}</small>
                    </td>
                    <td>{{ $d->campaign?->title ?? '-' }}</td>
                    <td>
                        <span class="badge
                            {{ $d->status==='paid' ? 'bg-success' : ($d->status==='pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                            {{ ucfirst($d->status) }}
                        </span>
                    </td>
                    <td>{{ $d->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.donations.show', $d->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>

                        <form action="{{ route('admin.donations.changeStatus', $d->id) }}" method="POST" class="d-inline">
                            @csrf
                            <select name="status" class="form-select form-select-sm d-inline-block" style="width:110px; vertical-align:middle;">
                                <option value="pending">معلق</option>
                                <option value="paid">مدفوع</option>
                                <option value="verified">مؤكد</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                            <button class="btn btn-sm btn-outline-success">تطبيق</button>
                        </form>

                        <form action="{{ route('admin.donations.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="8" class="text-center">لا توجد تبرعات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $donations->links() }}
    </div>
</div>
@endsection
