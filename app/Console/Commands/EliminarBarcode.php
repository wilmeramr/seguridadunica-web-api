<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Autorizaciones;


class EliminarBarcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcode:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hasta =  \Carbon\Carbon::now('America/Argentina/Buenos_Aires')->subMonth();
        $desde =  \Carbon\Carbon::now('America/Argentina/Buenos_Aires')->subMonth(2);

        $aut =  Autorizaciones::where('aut_email','=','wilmeramr@gmail.com')
        ->where('aut_envio_mail','=',1)
        ->whereNotNull('aut_barcode')
        ->whereBetween('aut_hasta',[$desde,$hasta])
        ->get();

        foreach ($aut as $value) {

            $path = public_path().'/img/barcode/'.$value->aut_barcode;

            if(\File::exists($path)){

                \File::delete($path);
                $value->aut_barcode =null;
                $value->save();

              }else{
                \Log::error('File does not exists.');
                $value->aut_barcode =null;
                $value->save();
              }


}

    }
}
