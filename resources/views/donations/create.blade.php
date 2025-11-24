@extends('layouts.app')

@section('title', 'تبرّع الآن')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="bg-white p-4 shadow rounded">

            <h2 class="h4 fw-bold mb-4 text-center">✨ تبرّعك يصنع فرقًا</h2>

            <form action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- CAMPAIGN ID --}}
                <input type="hidden" name="campaign_id" value="{{ request('campaign') }}">

                {{-- TYPE SELECTOR --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">نوع التبرع</label>
                    <select id="donationType" name="type" class="form-select">
                        <option value="cash">تبرع مالي</option>
                        <option value="goods">تبرع عيني</option>
                    </select>
                </div>

                {{-- CASH DONATION --}}
                <div id="cashFields" class="mb-4">
                    <label class="form-label fw-bold">المبلغ</label>
                    <input type="number" min="1" step="0.5" name="amount" class="form-control" placeholder="مثال: 50">
                </div>

                {{-- IN-KIND DONATION --}}
                <div id="inKindFields" class="mb-4" style="display: none;">

                    <label class="form-label fw-bold">تفاصيل التبرع</label>

                    <div class="border rounded p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input name="items[0][category]" class="form-control" placeholder="الفئة (ملابس، غذاء...)">
                            </div>
                            <div class="col-md-4">
                                <input name="items[0][condition]" class="form-control" placeholder="الحالة (جيد جدًا...)">
                            </div>
                            <div class="col-md-4">
                                <input name="items[0][quantity]" class="form-control" placeholder="الكمية (3 قطع...)">
                            </div>
                        </div>
                    </div>

                    {{-- IMAGE UPLOAD --}}
                    <label class="form-label fw-bold">صور التبرع (اختياري)</label>
                    <input type="file" class="form-control mb-3" multiple name="attachments[]">

                    {{-- PICKUP REQUEST --}}
                    <div class="mt-3">
                        <h6 class="fw-bold">طلب استلام من المنزل</h6>
                        <div class="row g-2 mt-2">
                            <div class="col-md-4">
                                <input name="pickup[city]" class="form-control" placeholder="المدينة">
                            </div>
                            <div class="col-md-4">
                                <input name="pickup[address]" class="form-control" placeholder="العنوان">
                            </div>
                            <div class="col-md-4">
                                <input name="pickup[phone]" class="form-control" placeholder="رقم الهاتف">
                            </div>
                            <div class="col-md-6 mt-3">
                                <input name="pickup[datetime]" type="datetime-local" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- DONOR INFO --}}
                <h5 class="fw-bold mb-3">بيانات المتبرّع</h5>

                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <input type="text" name="donor_name" class="form-control" placeholder="الاسم (اختياري)">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="donor_phone" class="form-control" placeholder="الهاتف (اختياري)">
                    </div>
                    <div class="col-md-4">
                        <input type="email" name="donor_email" class="form-control" placeholder="البريد (اختياري)">
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" name="is_anonymous" type="checkbox" value="1" id="anonCheck">
                    <label class="form-check-label" for="anonCheck">
                        التبرع مجهول الهوية
                    </label>
                </div>

                {{-- SUBMIT --}}
                <button class="btn btn-primary w-100 py-2">إرسال التبرع</button>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const donationType = document.getElementById('donationType');
    const cashFields = document.getElementById('cashFields');
    const inKindFields = document.getElementById('inKindFields');

    function toggleFields() {
        if (donationType.value === 'cash') {
            cashFields.style.display = 'block';
            inKindFields.style.display = 'none';
        } else {
            cashFields.style.display = 'none';
            inKindFields.style.display = 'block';
        }
    }

    donationType.addEventListener('change', toggleFields);
    toggleFields();
</script>
@endpush
