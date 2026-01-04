<?php
// File: app/Http/Requests/ManualExpenseRequest.php
// Jalankan: php artisan make:request ManualExpenseRequest

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isOwner();
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'in:operational,salary,maintenance,marketing,other'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'min:10', 'max:500'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Kategori pengeluaran wajib dipilih.',
            'category.in' => 'Kategori pengeluaran tidak valid.',
            'amount.required' => 'Jumlah pengeluaran wajib diisi.',
            'amount.numeric' => 'Jumlah pengeluaran harus berupa angka.',
            'amount.min' => 'Jumlah pengeluaran minimal Rp 0.',
            'description.required' => 'Deskripsi pengeluaran wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.before_or_equal' => 'Tanggal transaksi tidak boleh di masa depan.',
        ];
    }
}
