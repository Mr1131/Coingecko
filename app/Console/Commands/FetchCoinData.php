<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FetchCoinData extends Command
{
    protected $signature = 'coin:fetch';

    protected $description = 'Fetches data from Coingecko API and stores it in the database';

    public function handle()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/coins/list?include_platform=true');

        if ($response->successful()) {
            $coins = $response->json();
            $chunkSize = 200; // Set chunk size

            DB::table('coins')->truncate(); // Truncate the table before inserting new data

            $this->info('Inserting coin data...');

            collect($coins)->chunk($chunkSize)->each(function ($chunk) {
                $coinData = [];

                $currentTime = Carbon::now()->toDateTimeString();

                foreach ($chunk as $coin) {
                    $coinData[] = [
                        'id' => $coin['id'],
                        'symbol' => $coin['symbol'],
                        'name' => $coin['name'],
                        'platforms' => json_encode($coin['platforms']),
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ];
                }

                DB::table('coins')->insert($coinData);
            });

            $this->info('Coin data fetched and stored successfully.');
        } else {
            $this->error('Failed to fetch coin data from the API.');
        }
    }
}
