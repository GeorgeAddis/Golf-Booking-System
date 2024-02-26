<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $times = [];
        $startTime = Carbon::createFromTime(8, 0);
        $endTime = Carbon::createFromTime(16, 0);
    
        while ($startTime->lessThan($endTime)) {
            $times[] = $startTime->format('H:i');
            $startTime->addMinutes(5);
        }
    
        $bookings = Booking::whereDate('date', $selectedDate)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->time->format('H:i') => $item->booked_by];
            })->toArray();
    
        return view('bookings.index', compact('times', 'bookings', 'selectedDate'));
    }
    
    public function book(Request $request)
    {
        \Log::info('Booking request:', $request->all());
    
        $validated = $request->validate([
            'time' => 'required',
            'booked_by' => 'required|string|max:255',
            'date' => 'required|date', 
        ]);
    
        try {
            Booking::updateOrCreate(
                ['time' => $request->time, 'date' => $request->date], 
                ['booked_by' => $request->booked_by]
            );
        } catch (\Exception $e) {
            \Log::error('Error booking time slot: ' . $e->getMessage());
        }
    
        return redirect()->route('bookings.index', ['date' => $request->date]);
    }
    
}
