<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
 use DataTables;;
 use Illuminate\Support\Facades\Auth;
 use App\Models\Country;



class UsuarioController extends Controller
{
    
  

    function __construct(){

        $this -> middleware('permission:ver-user|crear-user|editar-user|borrar-user',['only'=>['index']]);
        $this -> middleware('permission:crear-user',['only'=>['create','store']]);
        $this -> middleware('permission:editar-user',['only'=>['edit','update']]);
        $this -> middleware('permission:borrar-user',['only'=>['destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user = Auth::user();
       // dd($user->roles[0]->name);
        $query =User::join('countries', 'users.country_id', '=', 'countries.id');
        if($user->roles[0]->name !='Administrador'){
        $query->where ('users.country_id','=',$user->country_id);
        }
        $usuarios =  $query->get(['users.*', 'countries.countryname as countryname']);
       
     //  $usuarios = User::paginate(5);
     // dd(count($usuarios[0]->getRoleNames()));

     
       return view('usuarios.index',compact('usuarios'));
    }

 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $user = Auth::user();

       $roles = Role::where('name','<>','Administrador')->pluck('name','name');

       $countrys = Country::where ('id',$user->country_id)->get();
       $data = [$countrys[0]->id => $countrys[0]->countryname];

       //dd($roles);
       return view('usuarios.crear',compact('roles','data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'lote_id' => 'required',
            'roles' => 'required'

        ]);

       $input = $request->all();
       $input['password'] = Hash::make($input['password']);

       $user = User::create($input);

       $user ->assignRole($request->input('roles'));

       return redirect()->route('usuarios.index')->with('info', 'Usuario creado exitosamente.!!');
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
        $user = User::find($id);
        $roles = Role::where('name','<>','Administrador')->pluck('name','name');
        $userRole = $user->roles->pluck('name','name')->all();
        $countrys = Country::where ('id',$user->country_id)->get();
        $data = [$countrys[0]->id => $countrys[0]->countryname];
        return view('usuarios.editar',compact('user','roles','userRole','data'));


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
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'country_id' => 'required',
            'roles' => 'required'

        ]);

        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));
        return redirect()->route('usuarios.index')->with('info', 'Usuario editado exitosamente.!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }

    public function getUsers()
    {
        $user = Auth::user();

        $query = DB::table('users')->join('countries', 'users.country_id', '=', 'countries.id');

        if($user->roles[0]->name !='Administrador'){
            $query->where ('users.country_id','=',$user->country_id);
            }

            $query->select(['users.id','users.name','users.email', 'countries.countryname','users.created_at','users.updated_at']);
  $datatable = Datatables::of($query);
  $datatable->addColumn('action', function ($user) {
    return '';
});
  if($user->can('borrar-user')){
  $datatable->addColumn('action', function ($us) {
      return ' <a href="usuarios/'.$us->id.'/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
      <form id="formulario1" action="/usuarios/'.$us->id.'" >
  <input class="btn btn-xs btn btn-danger" id="boton" type="submit" value="Borrar">
</form> <a href="usuarios/'.$us->id.'/destroy" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Borrar</a>
      ';
    });
}
$datatable->editColumn('id', 'ID: {{$id}}');

     
      
    return $datatable ->make(true);
   
       // dd($user->roles[0]->name);
    
    }
}
