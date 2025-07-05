<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{

    protected $data;

    public function __construct($data)
    {
        // Convert Collection to array if necessary
        $this->data = $data instanceof \Illuminate\Database\Eloquent\Collection ? $data->toArray() : $data;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data)->map(function ($user) {
            return [
                'first_name' => $user->first_name . ' ' . $user->family_name,
                'phone_number' => $user->phone_number,
                'created_at' => $user->created_at,
            ];
        });
    }

    /**
     * Define headings for the exported file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'اسم العميل',
            'رقم الجوال',
            'تاريخ الإنشاء',
        ];
    }
}
