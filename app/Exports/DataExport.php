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

class DataExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, WithStrictNullComparison
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function query()
    {
        $request = $this->request;
        $purchase_numbers = PurchaseNumber::when($request->date, function ($query, $date) {

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
            });
        // ->select('customers.*','customer_histories.start_date as history_start_date');
        return $purchase_numbers;
    }
    public function headings(): array
    {
        return [
            'Date',
            'Lucky Number',
            'Amount',
            'Name',
            'Phone',
            'Facebook',
            'Viber',
            'Created At',
            'Updated At'

        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => StyleNumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => StyleNumberFormat::FORMAT_TEXT,
            'C' => StyleNumberFormat::FORMAT_NUMBER,
            'E' => StyleNumberFormat::FORMAT_TEXT,
            'H' => StyleNumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => StyleNumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function map($purchase_number): array
    {
        return [
            Date::stringToExcel($purchase_number->purchase_date),
            $purchase_number->number,
            $purchase_number->amount,
            $purchase_number->customer_name,
            $purchase_number->customer_phone,
            $purchase_number->customer_facebook,
            $purchase_number->customer_viber,
            Date::stringToExcel($purchase_number->created_at),
            Date::stringToExcel($purchase_number->updated_at),
        ];
    }
}
