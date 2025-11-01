<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PropertiesExport implements FromView
{
    protected $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function view(): View
    {
        return view('backend.property.export.properties', [
            'properties' => $this->properties
        ]);
    }
}
