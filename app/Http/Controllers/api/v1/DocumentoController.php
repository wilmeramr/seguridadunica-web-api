<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Documento;
use App\Models\Noticia;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'doc_tipo' => 'required|integer',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        };

        $co_id = $request->user()->lote()->first()->country()->first()->co_id;
       $doc = Documento::where('doc_country_id',"=",$co_id)->where('doc_tipo','=',$request->doc_tipo)->paginate(20);

        return response($doc, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }


        $validator = \Validator::make($request->all(), [
            'doc_tipo' => 'nullable|integer',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $co_id = $request->user()->lote()->first()->country()->first()->co_id;


        $explo = explode('/',$request->doc_url);
        $pdfName = $explo[count($explo)-1];

        Documento::create([

            'doc_tipo' =>$request->doc_tipo,
            'doc_url' => $request->doc_url,
            'doc_country_id' =>$co_id,
            'doc_name'=> $pdfName,
            'doc_app'=>1
        ]
        );

        Notificacion::create([
            'noti_user_id'=>$request->user()->id,
            'noti_aut_code'=> 'DOCUMENTOS',
            'noti_titulo' =>  "Nuevo Documento.",
            'noti_body' =>   $pdfName,
            'noti_to' => 'T',
            'noti_to_user' =>$request->user()->id,

            'noti_event' => 'Documentos' ,
            'noti_priority' =>'high',
            'noti_envio'=> 0,
            'noti_app'=> 1
        ]);

        return response('', 201);

    }
    public function uploadPDF(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);

        }

        $validator = \Validator::make($request->all(), [
            'pdf' => 'required|mimes:pdf',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'El documento debe ser un pdf.'], 500);
        }
        if($request->hasFile('pdf')){

           $co_name = $request->user()->lote()->first()->country()->first()->co_name;

            $pdf_url = pathinfo($request->pdf->getClientOriginalName(), PATHINFO_FILENAME)  ." - ".$co_name.".".$request->pdf->getClientOriginalExtension();
            $path = 'pdf/documentos';
            $file = $request->pdf->storeAs($path, $pdf_url);


            if($file){

                $url = Storage::url('pdf/documentos/'.$pdf_url);
                $response =[
                    'link'=> $url
                ];
                return response($response, 201);;
            }else{

                return response('', 500);

            }

        }
            return response('', 500);
    }



}
