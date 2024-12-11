<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\PurchaseNumber;
use App\Models\WinningNumber;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use DB;

class ListNumberController extends Controller
{
    public function index(Request $request)
    {
        $winning_numbers = WinningNumber::all();
        $purchase_numbers = PurchaseNumber::all();

        // Parse the target date to determine the period
        $purchaseDate = Carbon::today();

        $startOfPeriod = $purchaseDate->day <= 15
            ? $purchaseDate->copy()->startOfMonth()
            : $purchaseDate->copy()->startOfMonth()->addDays(15);

        $endOfPeriod = $purchaseDate->day <= 15
            ? $startOfPeriod->copy()->addDays(14)
            : $purchaseDate->copy()->endOfMonth();

        // Get numbers already purchased within the period
        $purchasedNumbers = PurchaseNumber::whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])
            ->pluck('number')
            ->toArray();
        $cartNumbers = DB::table('carts')->pluck('number')->toArray();
        // Generate all numbers from 000 to 999
        $numbers = collect(range(0, 999))
            ->map(fn($n) => [
                'number' => str_pad($n, 3, '0', STR_PAD_LEFT),
                'disabled' => in_array(str_pad($n, 3, '0', STR_PAD_LEFT), $purchasedNumbers),
                'is_cart' => in_array(str_pad($n, 3, '0', STR_PAD_LEFT), $cartNumbers),
                'is_winning' => in_array(str_pad($n, 3, '0', STR_PAD_LEFT), $winning_numbers->pluck('number')->toArray()),
            ]);
        $config = Config::first();
        $amount = ($config->amount) ?? 0;
        $title = ($config->title) ?? null;
        $display = ($config->display) ?? null;
        return Inertia::render('ListNumber', [
            'numbers' => $numbers,
            'winning_numbers' => $winning_numbers,
            'purchase_numbers' => $purchase_numbers,
            'amount' => $amount,
            'title' => $title,
            'display' => $display,
        ]);
    }
}
