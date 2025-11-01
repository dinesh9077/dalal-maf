<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InquiryExport implements FromView
{
    protected $inquiry;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function view(): View
    {
        return view('backend.property.export.inquiry', [
            'inquiry' => $this->inquiry
        ]);
    }
}
