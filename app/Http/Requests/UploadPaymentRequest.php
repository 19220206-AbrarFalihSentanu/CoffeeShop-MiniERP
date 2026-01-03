<?php
// File: app/Http/Requests/UploadPaymentRequest.php
// Jalankan: php artisan make:request UploadPaymentRequest

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization sudah dicek di controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:transfer_bank,e_wallet,cash'],
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // 2MB
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.image' => 'File bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, JPEG, atau PNG.',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
            'customer_notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'payment_method' => 'metode pembayaran',
            'payment_proof' => 'bukti pembayaran',
            'customer_notes' => 'catatan',
        ];
    }
}
