<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignAdminController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderBy('id','DESC')->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'nullable|numeric|min:0',
            'status'      => 'required|in:active,paused,completed,draft',
            'attachments.*' => 'nullable|image|max:4096',
        ]);

        $campaign = Campaign::create($data);

        // Save images
        if ($request->hasFile('attachments')) {
            foreach ($request->attachments as $image) {
                $path = $image->store('campaigns', 'public');
                Attachment::create([
                    'campaign_id' => $campaign->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.campaigns.index')
                         ->with('success', 'تم إنشاء الحملة بنجاح');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'nullable|numeric|min:0',
            'status'      => 'required|in:active,paused,completed,draft',
            'attachments.*' => 'nullable|image|max:4096',
        ]);

        $campaign->update($data);

        // Upload new images
        if ($request->hasFile('attachments')) {
            foreach ($request->attachments as $image) {
                $path = $image->store('campaigns', 'public');
                Attachment::create([
                    'campaign_id' => $campaign->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.campaigns.index')
                         ->with('success', 'تم تحديث الحملة بنجاح');
    }

    public function destroy(Campaign $campaign)
    {
        // حذف الصور
        foreach ($campaign->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
                         ->with('success', 'تم حذف الحملة');
    }
}
