<?php

namespace App\Exports;

use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    use Exportable;

    public array $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return collect($this->records);
    }

    public function headings(): array
    {
        $h = $this->records[0] ?? [];
        return array_keys($h);

    }
}
