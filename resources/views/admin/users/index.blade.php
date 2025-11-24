@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="bg-white p-4 rounded shadow">

    <h3 class="fw-bold mb-4">إدارة المستخدمين</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الدور</th>
                <th>تاريخ التسجيل</th>
                <th width="200">إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    @if($u->role === 'admin')
                        <span class="badge bg-success">مسؤول</span>
                    @else
                        <span class="badge bg-secondary">مستخدم</span>
                    @endif
                </td>
                <td>{{ $u->created_at->format('Y-m-d') }}</td>

                <td>
                    @if($u->role !== 'admin')
                        <form action="{{ route('admin.users.makeAdmin', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-primary">ترقية لمسؤول</button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.removeAdmin', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-warning">إزالة المسؤول</button>
                        </form>
                    @endif

                    <form action="{{ route('admin.users.destroy', $u->id) }}" 
                          method="POST" class="d-inline"
                          onsubmit="return confirm('تأكيد الحذف؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}

</div>
@endsection
