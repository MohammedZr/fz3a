<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;

class DonationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::query()->with(['campaign','user']);

        // فلترة بسيطة عبر GET params
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('donor_name', 'like', "%{$q}%")
                    ->orWhere('donor_email', 'like', "%{$q}%")
                    ->orWhere('id', $q);
            });
        }

        $donations = $query->orderBy('id','desc')->paginate(15)->withQueryString();

        $campaigns = Campaign::orderBy('title')->get();

        return view('admin.donations.index', compact('donations','campaigns'));
    }

    public function show(Donation $donation)
    {
        $donation->load(['campaign','items','attachments','pickupRequest','user']);
        return view('admin.donations.show', compact('donation'));
    }

    public function changeStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,verified'
        ]);

        $donation->status = $request->status;
        $donation->save();

        return back()->with('success', 'تم تغيير حالة التبرع.');
    }

    public function destroy(Donation $donation)
    {
        // إذا يوجد مرفقات احذف الملفات ثم السجلات
        if ($donation->attachments()->exists()) {
            foreach ($donation->attachments as $att) {
                // حذف الملف من التخزين (إن كنت تخزن في public)
                \Illuminate\Support\Facades\Storage::disk('public')->delete($att->path);
                $att->delete();
            }
        }

        $donation->delete();

        return redirect()->route('admin.donations.index')->with('success','تم حذف التبرع.');
    }
}
