<?php
/**
 * @author Author Name: Md Morshadun Nur
 * @email  Email: morshadunnur@gmail.com
 */

namespace App\Actions\BoredApi;


use App\Models\BoreActivity;

class BoredResponse
{
    private array $bored_data;
    public function __construct(array $bored_data)
    {
        $this->bored_data = $bored_data;
    }

    public function import()
    {
        return BoreActivity::create([
            'api_response' => $this->bored_data
        ]);
    }

}
