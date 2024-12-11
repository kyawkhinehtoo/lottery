<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\PurchaseNumber;
use App\Models\WinningNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        //   dd($request->date);
        $winningNumbers = WinningNumber::when($request->winning, function ($query, $winning) {
            $query->where('number', $winning);
        })->orderBy('draw_date', 'DESC')->paginate(5, ['*'], 'winning_numbers');
        $winningNumbers->appends($request->all())->links();
        $purchaseNumbers = PurchaseNumber::when($request->date, function ($query, $date) {

            $startDate = Carbon::parse($date[0])->format('Y-m-d');
            $endDate = Carbon::parse($date[1])->format('Y-m-d');
            $query->whereBetween('purchase_date', [$startDate, $endDate]);
        })
            ->when($request->general, function ($query, $general) {
                $query->where(function ($query) use ($general) {
                    $query->where('number', 'LIKE', '%' . $general . '%')
                        ->orWhere('customer_name', 'LIKE', '%' . $general . '%')
                        ->orWhere('customer_phone', 'LIKE', '%' . $general . '%');
                });
            })

            ->orderBy('id', 'DESC')->paginate(5, ['*'], 'purchase_numbers');
        $purchaseNumbers->appends($request->all())->links();

        $todaySaleAmount = PurchaseNumber::whereDate('purchase_date', today())->sum('amount');
        $todaySaleNumber = PurchaseNumber::whereDate('purchase_date', today())->count();

        $purchaseDate = Carbon::today();

        $startOfPeriod = $purchaseDate->day <= 15
            ? $purchaseDate->copy()->startOfMonth()
            : $purchaseDate->copy()->startOfMonth()->addDays(15);

        $endOfPeriod = $purchaseDate->day <= 15
            ? $startOfPeriod->copy()->addDays(14)
            : $purchaseDate->copy()->endOfMonth();

        // Get numbers already purchased within the period
        $totalSaleAmount = PurchaseNumber::whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])->sum('amount');
        $totalSaleNumber = PurchaseNumber::whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])->count();
        $config = Config::first();
        $amount = ($config->amount) ?? 0;
        $title = ($config->title) ?? null;
        $display = ($config->display) ?? null;
        return Inertia::render('Dashboard', [
            'winningNumbers' => $winningNumbers,
            'purchaseNumbers' => $purchaseNumbers,
            'todaySaleAmount' => $todaySaleAmount,
            'todaySaleNumber' => $todaySaleNumber,
            'totalSaleAmount' => $totalSaleAmount,
            'totalSaleNumber' => $totalSaleNumber,
            'amount' => $amount,
            'title' => $title,
            'display' => $display,
        ]);
    }
    // Add selected number to the cart
    public function addPurchase(Request $request)
    {
        $request->validate([
            'number' =>  [
                'required',
                'regex:/^\d{3}(,\d{3})*$/'
            ],
            'customer_name' => 'required|string',
            'amount' => 'required|numeric',
            'customer_phone' => [
                'required',
                'regex:/^09\d{7,9}$/',
            ],
            'purchase_date' => 'required|date',
        ]);
        // Add the number to the cart
        // Calculate the period range

        $purchaseDate = Carbon::createFromFormat('Y-m-d', $request->purchase_date);

        // Determine the start and end of the period
        $startOfPeriod = $purchaseDate->day <= 15
            ? $purchaseDate->copy()->startOfMonth()
            : $purchaseDate->copy()->startOfMonth()->addDays(15);

        $endOfPeriod = $purchaseDate->day <= 15
            ? $startOfPeriod->copy()->addDays(14)
            : $purchaseDate->copy()->endOfMonth();

        $numbers = $request->number;
        if (strpos($request->number, ',') !== false) {
            // Explode the string into an array
            $numbers = explode(',', $request->number);

            foreach ($numbers as $number) {
                $existingNumber = PurchaseNumber::where('number', $number)
                    ->whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])
                    ->exists();


                if ($existingNumber) {
                    return redirect()->back()->withErrors(['number' => 'No. ' . $number . ' is already purchased by other within the period.']);
                }
                PurchaseNumber::create([
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_facebook' => $request->customer_facebook,
                    'customer_viber' => $request->customer_viber,
                    'number' => $number,
                    'amount' => $request->amount,
                    'purchase_date' => $request->purchase_date,
                ]);
            }
        } else {
            $existingNumber = PurchaseNumber::where('number', $numbers)
                ->whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])
                ->exists();


            if ($existingNumber) {
                return redirect()->back()->withErrors(['number' => 'No. ' . $numbers . ' is already purchased by other within the period.']);
            }
            PurchaseNumber::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_facebook' => $request->customer_facebook,
                'customer_viber' => $request->customer_viber,
                'number' => $numbers,
                'amount' => $request->amount,
                'purchase_date' => $request->purchase_date,
            ]);
        }
        // Check for uniqueness within the period



        return redirect()->back()->with('message', 'Number added to purchase successfully!');
    }
    public function addWinning(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'draw_date' => 'required|string',
        ]);


        // Add the number to the cart
        WinningNumber::create([
            'number' => $request->number,
            'draw_date' => $request->draw_date,
        ]);
        return redirect()->back()->with('message', 'Number added to winning successfully!');
    }
    public function editPurchase(Request $request, $id)
    {
        $request->validate([
            'number' => [
                'required',
                'regex:/^\d{3}$/'
            ],
            'customer_name' => 'required|string',
            'customer_phone' => [
                'required',
                'regex:/^09\d{7,9}$/',
            ],
            'purchase_date' => 'required|date',
        ]);

        // Calculate the period range
        $purchaseDate = Carbon::createFromFormat('Y-m-d', $request->purchase_date);

        $startOfPeriod = $purchaseDate->day <= 15
            ? $purchaseDate->copy()->startOfMonth()
            : $purchaseDate->copy()->startOfMonth()->addDays(15);

        $endOfPeriod = $purchaseDate->day <= 15
            ? $startOfPeriod->copy()->addDays(14)
            : $purchaseDate->copy()->endOfMonth();

        $existingNumber = PurchaseNumber::where('number', $request->number)
            ->whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])
            ->where('id', '!=', $id) // Exclude current record
            ->exists();

        if ($existingNumber) {
            return redirect()->back()->withErrors(['number' => 'No. ' . $request->number . ' is already purchased by other within the period.']);
        }
        // Update the purchase record
        $purchase = PurchaseNumber::findOrFail($id);

        // If numbers were split, only update the first one (or modify logic as needed)
        $purchase->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_facebook' => $request->customer_facebook,
            'customer_viber' => $request->customer_viber,
            'number' => $request->number,
            'amount' => $request->amount,
            'purchase_date' => $request->purchase_date,
        ]);

        return redirect()->back()->with('message', 'Purchase updated successfully!');
    }
    public function deletePurchase($id)
    {
        $purchase = PurchaseNumber::findOrFail($id);
        $purchase->delete();

        return redirect()->back()->with('message', 'Purchase number deleted successfully!');
    }

    public function editWinning(Request $request, $id)
    {
        $request->validate([
            'number' => 'required|string',
            'draw_date' => 'required|string',
        ]);

        $winning = WinningNumber::findOrFail($id);
        $winning->update([
            'number' => $request->number,
            'draw_date' => $request->draw_date,
        ]);

        return redirect()->back()->with('message', 'Winning number updated successfully!');
    }

    public function deleteWinning($id)
    {
        $winning = WinningNumber::findOrFail($id);
        $winning->delete();

        return redirect()->back()->with('message', 'Winning number deleted successfully!');
    }
    public function updatePrice(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'title' => 'required|string',
            'display' => 'required|string',
        ]);

        Config::updateOrCreate(
            ['id' => 1], // Assuming there's only one price entry to update or create
            [
                'amount' => $request->amount,
                'title' => $request->title,
                'display' => $request->display,
            ]
        );
        return redirect()->back()->with('message', 'Price updated successfully!');
    }
}
