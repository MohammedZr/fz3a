<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Campaign;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        $campaignId = $request->query('campaign');
        $campaign = $campaignId ? Campaign::find($campaignId) : null;
        return view('donations.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        // توحيد القيم الصحيحة للنوع
        $request->merge([
            'type' => $request->type === 'goods' ? 'goods' : 'cash'
        ]);

        $validated = $request->validate([
            'type'        => 'required|in:cash,goods',
            'campaign_id' => 'nullable|exists:campaigns,id',

            // مالي
            'amount'      => 'nullable|numeric|min:1',

            // المتبرع
            'donor_name'  => 'nullable|string|max:100',
            'donor_phone' => 'nullable|string|max:30',
            'donor_email' => 'nullable|email',
            'is_anonymous'=> 'sometimes|boolean',

            // عيني
            'items.*.category'  => 'nullable|string|max:100',
            'items.*.condition' => 'nullable|string|max:50',
            'items.*.quantity'  => 'nullable|string|max:50',

            // الاستلام
            'pickup.city'     => 'nullable|string|max:80',
            'pickup.address'  => 'nullable|string|max:255',
            'pickup.phone'    => 'nullable|string|max:30',
            'pickup.datetime' => 'nullable|date',
        ]);

        // إنشاء التبرع
        $donation = Donation::create([
            'user_id'      => auth()->id(),
            'campaign_id'  => $validated['campaign_id'] ?? null,
            'type'         => $validated['type'],
            'amount'       => $validated['type'] === 'cash' ? $validated['amount'] : null,
            'currency'     => 'LYD',
            'status'       => $validated['type'] === 'cash' ? 'pending' : 'verified',
            'donor_name'   => $validated['donor_name'] ?? null,
            'donor_phone'  => $validated['donor_phone'] ?? null,
            'donor_email'  => $validated['donor_email'] ?? null,
            'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
        ]);

        // ⭐ تبرعات عينية
        if ($validated['type'] === 'goods') {

            if (isset($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $donation->items()->create($item);
                }
            }

            if (isset($validated['pickup'])) {
                $donation->pickupRequest()->create([
                    'city'              => $validated['pickup']['city'] ?? null,
                    'address_line'      => $validated['pickup']['address'] ?? null,
                    'contact_phone'     => $validated['pickup']['phone'] ?? null,
                    'preferred_datetime'=> $validated['pickup']['datetime'] ?? null,
                ]);
            }

            return redirect()->route('home')
                ->with('success','تم تسجيل تبرعك العيني بنجاح.');
        }

        // ⭐ تبرع مالي → الانتقال إلى صفحة الدفع
        return redirect()->route('donations.show', $donation->id);
    }
    public function show($id)
{
    $donation = Donation::with(['campaign', 'items', 'attachments', 'pickupRequest'])->findOrFail($id);
    return view('donations.show', compact('donation'));
}
public function __construct()
{
    $this->middleware('auth')->only(['create', 'store']);
}

}
