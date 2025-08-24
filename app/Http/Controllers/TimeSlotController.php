<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;
use App\Http\Resources\TimeSlotResource;

class TimeSlotController extends Controller
{
    public function index()
    {
        return TimeSlotResource::collection(TimeSlot::all());
    }
}
