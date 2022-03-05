<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class CountryController extends Controller
{

    function __construct(){

        $this -> middleware('permission:ver-country|crear-country|editar-country|borrar-country',['only'=>['index']]);
        $this -> middleware('permission:crear-country',['only'=>['create','store']]);
        $this -> middleware('permission:editar-country',['only'=>['edit','update']]);
        $this -> middleware('permission:borrar-country',['only'=>['destroy']]);



    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $countrys =  Country::paginate(5);
      return view('country.index',compact('countrys'));
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('country.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request);
     
        request()->validate([
            'cuit' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'logo' => 'required|mimes:jpeg,png,jpg',
            'logoapp' => 'required|mimes:jpeg,png,jpg',
            'active' => 'required',


        ]);

      $data= $request->all();

        if($request->hasFile('logo')){
            $file = $request->logo;
            $file->move(public_path().'/img',$file->getClientOriginalName());
            $data['logo'] = $file->getClientOriginalName();
            //$request->merge(['logo' =>  $file->getClientOriginalName()]);
        }
        if($request->hasFile('logoapp')){
            $file = $request->logoapp;
            $file->move(public_path().'/img',$file->getClientOriginalName());
            $data['logoapp'] = $file->getClientOriginalName();
        }
       // dd($request);
        Country::create($data);
    
        return redirect()->route('countrys.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
