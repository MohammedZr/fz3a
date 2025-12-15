@extends('layouts.app')

@section('title', 'تبرعاتي')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">تبرعاتي</h3>
        <span class="text-muted">إجمالي التبرعات: {{ $donations->count() }}</span>
    </div>

    @if($donations->count() == 0)
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-1"></i>
            لم تقم بأي تبرع حتى الآن.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>نوع التبرع</th>
                                <th>المبلغ</th>
                                <th>الحملة</th>
                                <th>تاريخ التبرع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        @if($donation->type == 'cash')
                                            <span class="badge bg-success">
                                                💰 تبرع مالي
                                            </span>
                                        @else
                                            <span class="badge bg-info text-dark">
                                                📦 تبرع عيني
                                            </span>
                                        @endif
                                    </td>

                                    <td class="fw-bold text-primary">
                                        {{ $donation->amount ? number_format($donation->amount, 2) . ' LYD' : '—' }}
                                    </td>

                                    <td>
                                        {{ $donation->campaign->title ?? 'غير مرتبط بحملة' }}
                                    </td>

                                    <td class="text-muted">
                                        {{ $donation->created_at->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif

</div>
@endsection
