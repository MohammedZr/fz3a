<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Campaign;

class DonationController extends Controller
{
    /**
     * عرض صفحة التبرع
     */
    public function create(Request $request)
    {
        $campaignId = $request->query('campaign');
        $campaign = $campaignId ? Campaign::find($campaignId) : null;

        return view('donations.create', compact('campaign'));
    }

    /**
     * حفظ التبرع
     */
    public function store(Request $request)
    {
        // توحيد نوع التبرع (أمان إضافي)
        $type = $request->input('type') === 'goods' ? 'goods' : 'cash';

        // التحقق من البيانات
        $validated = $request->validate([
            'type'        => 'required|in:cash,goods',
            'campaign_id' => 'nullable|exists:campaigns,id',

            // تبرع مالي
            'amount'      => 'nullable|numeric|min:1',

            // بيانات المتبرع
            'donor_name'  => 'nullable|string|max:100',
            'donor_phone' => 'nullable|string|max:30',
            'donor_email' => 'nullable|email|max:150',
            'is_anonymous'=> 'sometimes|boolean',

            // تبرع عيني (اختياري – لا يسبب خطأ إذا غير موجود)
            'items'               => 'nullable|array',
            'items.*.category'    => 'nullable|string|max:100',
            'items.*.condition'   => 'nullable|string|max:50',
            'items.*.quantity'    => 'nullable|string|max:50',

            // طلب الاستلام
            'pickup'              => 'nullable|array',
            'pickup.city'         => 'nullable|string|max:80',
            'pickup.address'      => 'nullable|string|max:255',
            'pickup.phone'        => 'nullable|string|max:30',
            'pickup.datetime'     => 'nullable|date',
        ]);

        // إنشاء التبرع
        $donation = Donation::create([
            'user_id'      => auth()->id(),
            'campaign_id'  => $validated['campaign_id'] ?? null,
            'type'         => $type,
            'amount'       => $type === 'cash' ? ($validated['amount'] ?? null) : null,
            'currency'     => 'LYD',
            'status'       => $type === 'cash' ? 'pending' : 'verified',
            'donor_name'   => $validated['donor_name'] ?? null,
            'donor_phone'  => $validated['donor_phone'] ?? null,
            'donor_email'  => $validated['donor_email'] ?? null,
            'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
        ]);

        /*
        |--------------------------------------------------------------------------
        | التبرعات العينية
        |--------------------------------------------------------------------------
        */
        if ($type === 'goods') {

            // عناصر التبرع (إذا كانت العلاقة موجودة)
            if (!empty($validated['items']) && method_exists($donation, 'items')) {
                foreach ($validated['items'] as $item) {
                    $donation->items()->create($item);
                }
            }

            // طلب الاستلام (إذا كانت العلاقة موجودة)
            if (!empty($validated['pickup']) && method_exists($donation, 'pickupRequest')) {
                $donation->pickupRequest()->create([
                    'city'               => $validated['pickup']['city'] ?? null,
                    'address_line'       => $validated['pickup']['address'] ?? null,
                    'contact_phone'      => $validated['pickup']['phone'] ?? null,
                    'preferred_datetime' => $validated['pickup']['datetime'] ?? null,
                ]);
            }

            return redirect()
                ->route('home')
                ->with('success', 'تم تسجيل تبرعك العيني بنجاح، وسيتم التواصل معك قريبًا.');
        }

        /*
        |--------------------------------------------------------------------------
        | التبرعات المالية → صفحة التفاصيل / الدفع
        |--------------------------------------------------------------------------
        */
        return redirect()->route('donations.show', $donation->id);
    }

    /**
     * عرض تبرع واحد
     */
    public function show($id)
    {
        $donation = Donation::with(['campaign'])
            ->findOrFail($id);

        return view('donations.show', compact('donation'));
    }

    /**
     * صفحة تبرعاتي
     */
    public function myDonations()
    {
        $donations = Donation::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donations.my', compact('donations'));
    }
}
