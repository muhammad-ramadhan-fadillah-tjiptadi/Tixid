<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// proses manipulasi tanggal dan waktu
use Carbon\Carbon;


class UserExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::orderBy('created_at', 'DESC')->get();
    }
    public function headings(): array
    {
        return ["No", "Nama", "Email", "Role", "Tanggal Bergabung"];
    }
    public function map($user): array
    {
        return [
            ++$this->key,
            $user->name,
            $user->email,
            $user->role,
            Carbon::parse($user->created_at)->format('d-m-Y H:i:s')
        ];
    }
}
