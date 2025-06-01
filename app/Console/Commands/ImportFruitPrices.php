<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportFruitPrices extends Command
{
   
    protected $signature = 'import:fruit-prices 
                            {source : csv or api} 
                            {--file= : Path to CSV file when source is csv} 
                            {--date= : Price date (YYYY-MM-DD). Defaults to today}';

 
    protected $description = 'Import current fruit prices from CSV or API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $source = $this->argument('source');
        $priceDate = $this->option('date') ?? now()->toDateString();

        if (!in_array($source, ['csv', 'api'])) {
            $this->error('Invalid source. Use either "csv" or "api"');
            return 1;
        }

        if ($source === 'csv') {
            $filePath = $this->option('file');
            if (!$filePath || !file_exists($filePath)) {
                $this->error('CSV file not found or not specified');
                return 1;
            }
            $this->importFromCsv($filePath, $priceDate);
        } else {
            $this->importFromApi($priceDate);
        }

        $this->info('Fruit prices imported successfully!');
        return 0;
    }

    //import from csv
    protected function importFromCsv(string $filePath, string $priceDate)
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $this->info('Starting CSV import...');
        $bar = $this->output->createProgressBar(count($csv));

        foreach ($csv as $record) {
            try {
                $this->processPriceRecord($record, $priceDate);
            } catch (\Exception $e) {
                $this->error("Error processing record: " . $e->getMessage());
                continue;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

     protected function importFromDialogApi(string $priceDate)
    {
        // Example API endpoint - replace with actual Sri Lankan fruit price API
        $apiUrl = 'https://api.dialog.lk/foodprices/1.0.0/fruits';
        
        //$this->info('Fetching data from API...');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer YOUR_DIALOG_API_KEY',
                'Accept' => 'application/json',
            ])->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                return $this->processDialogData($data['fruits']);
            }
            else {
                $this->error('API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('API Error: ' . $e->getMessage());
        }
    }

    protected function processPriceRecord(array $record, string $priceDate)
    {
        // Find or create fruit
        $fruit = Fruit::firstOrCreate(
            ['name' => trim($record['fruit_name'])],
            ['description' => $record['description'] ?? null]
        );

        // Find or create market
        $market = Market::firstOrCreate(
            ['name' => trim($record['market_name'])],
            [
                'location' => $record['market_location'] ?? 'Unknown',
                'district' => $record['district'] ?? 'Colombo'
            ]
        );

        // Create or update price
        Price::updateOrCreate(
            [
                'fruit_id' => $fruit->id,
                'market_id' => $market->id,
                'price_date' => $priceDate
            ],
            [
                'price' => $record['price'],
                'unit' => $record['unit'] ?? 'kg'
            ]
        );
    }

}
