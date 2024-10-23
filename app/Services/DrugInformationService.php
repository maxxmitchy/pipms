<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DrugInformationService
{
    protected $baseUrl = 'https://api.fda.gov/drug/label.json';

    public function getDrugInformation(string $drugName)
    {
        $response = Http::get($this->baseUrl, [
            'search' => "openfda.brand_name:$drugName",
            'limit' => 1,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['results'][0])) {
                return $data['results'][0];
            }
        }

        return null;
    }
}
