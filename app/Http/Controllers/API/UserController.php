<?php

namespace App\Http\Controllers\API;
 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\punches;
use Illuminate\Support\Facades\Hash;
use Image;
use File;
use Carbon\Carbon;
use DB;
use DateTime;
 

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->authorize('isAdmin');   Will check and allow for all the pages for Admin only
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $this->authorize('isAdmin');
        return User::latest()->paginate(5);
    }
 
    public function timesheet()
    {
        $user = auth('api')->user(); 
        $punch = punches::where('user_id','=',$user->id )->whereDate('created_at', '=', Carbon::today()->toDateString())->get(); 
    
        
        // dd(DB::table('punches')->where('user_id', $user->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->select(DB::raw(" TIMEDIFF(punch_out, punch_in)  as result"))->get(['result']));
        return $punch; 
    }

        // public function total(User $user)
        // {
        //     return $user->totalTiming;
        // }

    public function date(Request $request)
    {
        $user = auth('api')->user();    
        $punch = punches::where('user_id','=',$user->id )->whereDate('created_at', '=',Carbon::today()->toDateString())->get();  
        
    }
 
    public function timesheetmanager()
    {
        $this->authorize('isAdmin');
        $punch = punches::orderBy('user_id')->get();
        return $punch;
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->authorize('isAdmin');  
        $this->validate($request, [
            'name'=> 'required|string|max:191',
            'email'=>'required|string|max:191|email|unique:users', 
            'password'=>'required|min:6',
        ]);

        return User::create([
            'name'=>$request['name'],
            'email'=>$request['email'], 
            'password'=>Hash::make($request['password']),
            'type'=>$request['type'],
            'bio'=>$request['bio'],
            'photo'=>$request['photo'],
        ]);
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

    public function updateprofile(Request $request)
    {    
        $user= auth('api')->user(); 
        $this->validate($request, [
            'name'=> 'required|string|max:191',
            'email'=>'required|string|max:191|email|unique:users,email,'.$user->id , //Escape current user
            'password'=>'sometimes|min:6'
        ]);  
         
         $currentPhoto = $user->photo;
         if($request->photo != $currentPhoto){ 
            File::delete(public_path('image/profile/').$currentPhoto);                 
            $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            Image::make($request->photo)->save(public_path('image/profile/').$name);
            $request->merge(['photo'=>$name]);
         } 

         if(!empty($request->password))
         {
             $request->merge(['password'=>Hash::make($request['password'])]);
         }
         $user -> update( $request->all() );
    } 

    //Profile Display

    public function profile()
    {
        //  return Auth::User();
         return auth('api')->user();//For api 
    }

    public function punch(Request $request)
    {
        $user = User::findOrFail($request->id);        
        $user->status = $request->status;
        if($user->status == "In")
        {
            punches::create([
                'user_id'=>$request['id'], 
                'punch_in'=>Carbon::now(),  
                'punch_out'=>NULL, 
            ]);
            $user->save();
        }
        else if($user->status == 'Out'){
            punches::create([
                'user_id'=>$request['id'], 
                'punch_out'=>Carbon::now(),  
                'punch_in'=>NULL,  
            ]);
            $user->save();
        }        
        return ['meassage'=>  $user->status];
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
        $this->authorize('isAdmin');    
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name'=> 'required|string|max:191',
            'email'=>'required|string|max:191|email|unique:users,email,'.$user->id , //Escape current user
            'password'=>'sometimes|min:6', 
        ]);           
        
        $user -> update( $request->all() );
        // return ['meassage'=>$id];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        $user->delete();
    }
    
    public function search()
    {
        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%")
                        ->orWhere('type','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}
