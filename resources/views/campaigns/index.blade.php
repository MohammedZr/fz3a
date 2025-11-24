@extends('layouts.app')
@section('title','الحملات')
@section('content')
<h1 class="h4 mb-3">الحملات النشطة</h1>
<div class="row g-3">
@forelse($campaigns as $c)
  <div class="col-md-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <h2 class="h5">{{ $c->title }}</h2>
        <p class="text-muted small">{{ Str::limit($c->description,120) }}</p>
        @if($c->goal_amount)
          <div class="mb-2">
            <div class="progress" role="progressbar">
              @php $p = min(100, (float)($c->raised_amount / max(1,$c->goal_amount))*100); @endphp
              <div class="progress-bar" style="width: {{ number_format($p,2) }}%"></div>
            </div>
            <small class="text-muted">
              {{ number_format($c->raised_amount,2) }} / {{ number_format($c->goal_amount,2) }}
            </small>
          </div>
        @endif
        <a href="{{ route('campaigns.show',$c) }}" class="btn btn-outline-primary btn-sm">التفاصيل</a>
      </div>
    </div>
  </div>
@empty
  <p class="text-muted">لا توجد حملات حالياً.</p>
@endforelse
</div>
<div class="mt-3">{{ $campaigns->links() }}</div>
@endsection
