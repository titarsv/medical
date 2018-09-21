<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersonalSale;

class DeliverySale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery:sale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sale delivery';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param PersonalSale $sale
     */
    public function handle(PersonalSale $sale)
    {
        $sale->delivery();
    }
}
