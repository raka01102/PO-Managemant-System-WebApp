<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class OCRService
{
    public function scan(string $filePath): array
    {
        $fullPath = storage_path('app/public/' . $filePath);

        $response = Http::attach(
            'file',
            fopen($fullPath, 'r'),
            basename($fullPath)
        )->post('http://127.0.0.1:5000/ocr');

        if (!$response->successful()) {
            return [
                'po_number' => null,
                'customer_name' => null,
                'items' => [],
                'raw_text' => 'Error API'
            ];
        }

        $ocrData = $response->json();

        $rawText = $ocrData['raw_text'] ?? '';

        return [
            'po_number'     => $this->extractPONumber($rawText),
            'customer_name' => $this->guessCustomerName($rawText),
            'items'         => $this->extractItems($rawText),
            'raw_text'      => $rawText
        ];
    }

    private function extractPONumber(string $text): ?string
    {
        $patterns = [
            '/(WS\/[\d-]+\/[\d.]+)/i',
            '/(FROM\/[A-Z]+\/[\d-]+)/i',
            '/(?:NO|No|Nomor)[\s.:]*([A-Z0-9\/.-]{5,})/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    private function extractTotal(string $text): float
    {
        $patterns = [
            '/(?:Total|Grand Total|Jumlah)[\s:]*Rp?[\s.]*([\d.,]+)/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $cleaned = str_replace(['.', ','], '', $matches[1]);
                return (float) $cleaned;
            }
        }

        return 0;
    }

    private function extractItems(string $text): array
    {
        $items = [];

        $lines = array_values(array_filter(array_map('trim', explode("\n", $text))));

        $units = ['lembar', 'meter', 'pcs', 'roll', 'set', 'box', 'dus', 'kg', 'btg'];

        for ($i = 0; $i < count($lines); $i++) {

            $current = $lines[$i];

            // Skip header
            if (preg_match('/BARANG|KODE|QTY|JUMLAH|SAT/i', $current)) {
                continue;
            }

            // 🔥 Jika baris ini adalah nama barang (huruf)
            if (
                !preg_match('/^\d+$/', $current) &&
                !in_array(strtolower($current), $units) &&
                strlen($current) > 3
            ) {

                $name = $current;
                $qty = null;
                $unit = 'pcs';

                // 🔥 Cek baris berikutnya untuk qty
                if (isset($lines[$i + 1]) && is_numeric($lines[$i + 1])) {
                    $qty = (int)$lines[$i + 1];
                    $i++; // skip qty line
                }

                // 🔥 Cek baris setelah qty untuk unit
                if (isset($lines[$i + 1]) && in_array(strtolower($lines[$i + 1]), $units)) {
                    $unit = strtolower($lines[$i + 1]);
                    $i++; // skip unit line
                }

                // Validasi qty tidak besar (hindari tanggal)
                if ($qty !== null && $qty < 1000) {
                    $items[] = [
                        'name' => strtoupper($name),
                        'qty'  => $qty,
                        'unit' => $unit
                    ];
                }
            }
        }

        return $items;
    }

    private function guessCustomerName(string $text): ?string
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            if (stripos($text, $customer->name) !== false) {
                return $customer->name;
            }
        }

        return null;
    }
}
