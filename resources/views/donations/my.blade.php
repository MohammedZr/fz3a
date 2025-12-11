@extends('layouts.app')

@section('title', 'تبرعاتي')

@section('content')

<div class="container py-4">

    <h2 class="fw-bold mb-4">تبرعاتي</h2>

    @if($donations->isEmpty())
        <div class="alert alert-info">
            لم تقم بأي تبرعات حتى الآن.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نوع التبرع</th>
                        <th>القيمة / التفاصيل</th>
                        <th>تاريخ التبرع</th>
                        <th>الحملة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $donation->id }}</td>

                            <td>
                                @if($donation->type === 'cash')
                                    <span class="badge bg-success">تبرع مالي</span>
                                @else
                                    <span class="badge bg-info">تبرع عيني</span>
                                @endif
                            </td>

                            <td>
                                @if($donation->type === 'cash')
                                    {{ number_format($donation->amount, 2) }} LYD
                                @else
                                    <small class="text-muted">تبرع عيني</small>
                                @endif
                            </td>

                            <td>{{ $donation->created_at->format('Y-m-d') }}</td>

                            <td>
                                @if($donation->campaign)
                                    {{ $donation->campaign->title }}
                                @else
                                    <span class="text-muted">بدون حملة</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection
