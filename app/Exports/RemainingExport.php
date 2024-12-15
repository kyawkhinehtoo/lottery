<?php

namespace App\Exports;

use App\Models\PurchaseNumber;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as StyleNumberFormat;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class RemainingExport implements FromCollection, WithColumnFormatting, WithMapping, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $request = $this->request;

        $purchaseDate = Carbon::today();
        $startOfPeriod = $purchaseDate->day <= 15
            ? $purchaseDate->copy()->startOfMonth()
            : $purchaseDate->copy()->startOfMonth()->addDays(15);

        $endOfPeriod = $purchaseDate->day <= 15
            ? $startOfPeriod->copy()->addDays(14)
            : $purchaseDate->copy()->endOfMonth();

        if ($request->date) {
            $startOfPeriod = Carbon::parse($request->date[0])->format('Y-m-d');
            $endOfPeriod = Carbon::parse($request->date[1])->format('Y-m-d');
        }

        $purchasedNumbers = PurchaseNumber::whereBetween('purchase_date', [$startOfPeriod, $endOfPeriod])
            ->pluck('number')
            ->toArray();

        $numbers = collect(range(0, 999))->map(fn($n) => [
            'number' => str_pad($n, 3, '0', STR_PAD_LEFT),
            'sold' => in_array(str_pad($n, 3, '0', STR_PAD_LEFT), $purchasedNumbers),
        ]);

        return $numbers;
    }

    public function headings(): array
    {
        return [
            'Number',
            'Status',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => StyleNumberFormat::FORMAT_TEXT,
            'B' => StyleNumberFormat::FORMAT_TEXT,
        ];
    }

    public function map($number): array
    {
        return [
            $number['number'],
            $number['sold'] ? 'Sold Out' : 'Available',
        ];
    }
}
