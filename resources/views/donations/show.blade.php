@extends('layouts.app')

@section('title', 'تأكيد التبرع')

@section('content')

<div class="bg-white p-4 shadow rounded">

    <h3 class="fw-bold mb-3">شكراً لك على التبرع ❤️</h3>

    <p class="mb-1"><strong>نوع التبرع:</strong> 
        @if($donation->type === 'cash') مالي @else عيني @endif
    </p>

    @if($donation->type === 'cash')
        <p class="mb-1"><strong>المبلغ:</strong> {{ number_format($donation->amount, 2) }} LYD</p>
    @endif

    <p><strong>الحملة:</strong> {{ $donation->campaign->title ?? 'غير محدد' }}</p>

    <hr>

    {{-- ⭐⭐ زر الدفع هنا ⭐⭐ --}}
    @if($donation->type === 'cash')
        <a href="{{ route('payment.checkout', $donation->id) }}" 
           class="btn btn-success btn-lg">
            الدفع الآن
        </a>
    @endif

</div>

@endsection
