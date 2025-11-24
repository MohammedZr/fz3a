<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;


class CampaignController extends Controller
{
    public function index() {
        $campaigns = Campaign::query()->where('status','active')
            ->orderByDesc('id')->paginate(12);
        return view('campaigns.index', compact('campaigns'));
    }

public function show(Campaign $campaign) {
    abort_unless($campaign->status !== 'draft', 404);
    return view('campaigns.show', compact('campaign'));
}

}
