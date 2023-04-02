<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Autorizaciones;
use App\Models\Lote;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use FilesystemIterator;

class BashInicial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BashInicial:process';

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
        $arrFiles = array();
        $iterator = new FilesystemIterator(storage_path() ."/app/bash");

foreach($iterator as $entry) {


    if(Str::lower($entry->getExtension()) =='ok'){
        $result = preg_replace('/.ok/',"", $entry->getFileName());

        $interador = 0;
        if (($open = fopen(storage_path() . "/app/bash/".$result, "r")) !== FALSE) {

            while (($data = fgetcsv($open, 0, ";")) !== FALSE) {
                if($interador > 0){

                    if(Str::contains(Str::lower($entry->getFileName()), 'lotes')){
                        try{
                            $lote_id = Lote::where('lot_country_id','=',$data[0])
                            ->where('lot_name','=',Str::lower($data[1]))
                            ->select('lot_id')

                            ->get();
                     if($lote_id->count()==0){
                            Lote::create([
                                'lot_country_id' => $data[0],
                                'lot_name' => Str::upper($data[1]),
                                'lot_activo'=> 1

                            ]);
                        }
                        } catch (\Throwable $th) {

                            \Log::error($th->getMessage());

                        }

                    }
                    if(Str::contains(Str::lower($entry->getFileName()), 'usuarios')){


                        try{
                        $lote_id = Lote::where('lot_country_id','=',$data[0])
                                            ->where('lot_name','=',Str::lower($data[1]))
                                            ->select('lot_id')

                                            ->get();
                            if($lote_id->count()==0){
                                throw new Exception('Lote no registrado '.$data[0].'-'.$data[1]);
                            }
                                           // \Log::debug( $lote_id->count());

                        $user = User::whereRaw('LOWER(`email`) LIKE ?',Str::lower($data[4]))->get();

                        if($user->count() > 0){
                            throw new Exception('Email ya existe '.$data[0].'-'.$data[1].'-'.$data[4]);
                        }

                        $user =  User::create([
                            'us_lote_id' => $lote_id->first()->lot_id,
                            'us_name' => Str::upper($data[2]),
                            'us_apellido' => '',
                            'us_phone' =>$data[3],
                            'email'=>$data[4],
                            'password' =>bcrypt(Str::random(16)),
                            'us_active' => 1
                        ]);

                        $user ->assignRole(3);

                    } catch (\Throwable $th) {

                        \Log::error($th->getMessage());

                    }

                        }
                  //  $users[] = $data;
                 //   \Log::debug($data);
                }

                $interador++;


            }

            fclose($open);

            unlink(storage_path() . "/app/bash/".$result);
           unlink(storage_path() . "/app/bash/".$entry->getFileName());

        }



    }

}

    }
}
