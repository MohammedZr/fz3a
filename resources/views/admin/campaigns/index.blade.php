@extends('layouts.app')
@section('title', 'إدارة الحملات')

@section('content')

<div class="bg-white p-4 shadow rounded">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">إدارة الحملات</h3>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
            + حملة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>المبلغ الهدف</th>
                <th>تاريخ الإنشاء</th>
                <th width="160">إجراءات</th>
            </tr>
        </thead>

        <tbody>
            @foreach($campaigns as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->title }}</td>
                <td>{{ $c->status }}</td>
                <td>{{ number_format($c->goal_amount, 2) }}</td>
                <td>{{ $c->created_at->format('Y-m-d') }}</td>

                <td>
                    <a href="{{ route('admin.campaigns.edit', $c->id) }}" class="btn btn-sm btn-warning">
                        تعديل
                    </a>

                    <form action="{{ route('admin.campaigns.destroy', $c->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('تأكيد الحذف؟')">
                            حذف
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $campaigns->links() }}

</div>

@endsection
