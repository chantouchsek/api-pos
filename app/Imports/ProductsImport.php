<?php

namespace App\Imports;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Webpatser\Uuid\Uuid;

class ProductsImport implements ToModel, WithChunkReading, WithBatchInserts, ShouldQueue, WithValidation, WithHeadingRow
{
    use Importable;

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }


    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     * @throws \Exception
     */
    public function model(array $row)
    {
        return new Product([
            'category_id' => $row['category'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'cost' => $row['cost'],
            'imported_date' => $row['imported_date'],
            'expired_at' => new Carbon($row['expired_at']),
            'sku' => $row['sku'],
            'tax_rate' => $row['tax_rate'],
            'tax_method' => $row['tax_method'],
            'uuid' => (string)Uuid::generate(4),
            'code' => $row['code']
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'string'],
            'tax_method' => 'nullable|in:Inclusive,Exclusive',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => [
                Rule::exists('categories', 'id')->where('active', true)
            ],
            'code' => 'required',
            'description' => 'nullable|min:5',
            'imported_date' => 'required|date',
            'expired_at' => 'required|date'
        ];
    }
}
