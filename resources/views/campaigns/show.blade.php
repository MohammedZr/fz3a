@extends('layouts.app')

@section('title', $campaign->title)

@section('content')

<div class="row g-4">

    {{-- LEFT SIDE: CAMPAIGN INFO --}}
    <div class="col-lg-8">

        {{-- CAMPAIGN HEADER --}}
        <div class="bg-white p-4 rounded shadow-sm">
            <h1 class="h4 fw-bold">{{ $campaign->title }}</h1>

            @if($campaign->attachments && count($campaign->attachments))
                <img src="{{ asset('storage/' . $campaign->attachments->first()->path) }}"
                     class="img-fluid rounded mb-3 mt-2"
                     alt="Campaign image">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png"
                     class="img-fluid rounded mb-3 mt-2"
                     style="max-height: 300px; object-fit: contain;">
            @endif

            <p class="text-muted">{{ $campaign->description }}</p>

            {{-- GOAL + PROGRESS --}}
            @if($campaign->goal_amount)
                @php
                    $percent = min(100, ($campaign->raised_amount / max(1, $campaign->goal_amount)) * 100);
                @endphp

                <div class="mb-2">
                    <div class="progress" style="height:14px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ number_format($percent,2) }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">
                            {{ number_format($campaign->raised_amount,2) }} / {{ number_format($campaign->goal_amount,2) }} LYD
                        </small>
                        <small class="fw-bold text-success">{{ number_format($percent,2) }}%</small>
                    </div>
                </div>
            @endif

            {{-- DONATE BUTTON --}}
            <a href="{{ route('donations.create', ['campaign' => $campaign->id]) }}" class="btn btn-primary btn-lg mt-3">
                تبرّع لهذه الحملة
            </a>

        </div>


        {{-- DONORS LIST --}}
        <div class="bg-white p-4 rounded shadow-sm mt-4">
            <h5 class="fw-bold mb-3">آخر المتبرعين</h5>

            @php
                $donors = $campaign->donations()->orderBy('id','DESC')->limit(10)->get();
            @endphp

            @forelse($donors as $d)
                <div class="border-bottom pb-2 mb-2">
                    <strong>
                        {{ $d->is_anonymous ? 'متبرّع مجهول' : ($d->donor_name ?: 'متبرّع') }}
                    </strong>

                    @if($d->type === 'cash')
                        <span class="text-success">تبرّع بـ {{ number_format($d->amount,2) }} LYD</span>
                    @else
                        <span class="text-info">تبرّع عيني</span>
                    @endif

                    <br>
                    <small class="text-muted">{{ $d->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p class="text-muted">لا يوجد متبرعون بعد.</p>
            @endforelse
        </div>

    </div>


    {{-- RIGHT SIDE: SUMMARY CARD --}}
    <div class="col-lg-4">
        <div class="bg-white p-4 rounded shadow-sm">

            <h5 class="fw-bold mb-3">معلومات الحملة</h5>

            <p class="mb-1"><strong>الحالة:</strong>
                <span class="text-primary">
                    @if($campaign->status == 'active') نشطة
                    @elseif($campaign->status == 'completed') مكتملة
                    @elseif($campaign->status == 'paused') موقوفة
                    @else قيد المراجعة
                    @endif
                </span>
            </p>

            <p class="mb-1"><strong>عدد المتبرعين:</strong> 
                {{ $campaign->donations->count() }}
            </p>

            @if($campaign->goal_amount)
            <p class="mb-1"><strong>الهدف:</strong> 
                {{ number_format($campaign->goal_amount,2) }} LYD
            </p>
            @endif

            <p class="mb-3"><strong>بدأت في:</strong> 
                {{ $campaign->created_at->format('Y-m-d') }}
            </p>

            {{-- Donate BTN --}}
            <a href="{{ route('donations.create', ['campaign' => $campaign->id]) }}" 
               class="btn btn-success w-100 mt-3">
                ساهم الآن
            </a>
        </div>
    </div>

</div>

@endsection
