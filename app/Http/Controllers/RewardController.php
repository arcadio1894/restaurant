<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $milestones = Milestone::orderBy('flames')->get();

        $user = Auth::user();

        $flames = $user->flames;

        return view('reward.index', compact('milestones', 'flames'));
    }
}
