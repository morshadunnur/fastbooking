<?php

namespace App\Jobs;

use App\Actions\BoredApi\BoredResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ParseBoredActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::retry(3,200)->get(env('BORED_API_URL'));
            $response->throw();
            $bored_data = new BoredResponse($response->json());
            $bored_data->import();
        }catch (RequestException $e){
            app('log')->debug(json_encode([
                        'tag'  => 'Bored API Failed',
                        'data' => $e->getMessage(),
                    ], JSON_PRETTY_PRINT));
        }

    }
}
