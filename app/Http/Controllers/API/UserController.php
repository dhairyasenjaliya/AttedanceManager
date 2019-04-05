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
        return User::with('punches')->latest()->paginate(5);
    }

    public function name()
    {    
        return User::select('id','name','status','bio','photo')->latest()->paginate(5);
    }
 
    public function timesheet()
    {
        $user = auth('api')->user(); 
        $punch = punches::where('user_id','=',$user->id )->whereDate('created_at', '=', Carbon::today()->toDateString())->get() ;         
        return $punch;  
    }  

    public function year()
    { 
        $user = auth('api')->user();  
        $punch = punches::where('user_id','=',$user->id )->whereYear('created_at', date('Y'))->get() ;  
        // $punch = punches::where('user_id','=',$user->id )->whereYear('created_at', date('Y'))->get() ; 
        return $punch;   
    }  


    public function month()
    {
        $currentMonth = date('m');
        $user = auth('api')->user();  
        $punch = punches::where('user_id','=',$user->id )->whereRaw('MONTH(created_at) = ?',[$currentMonth])->get() ;
        return $punch;  
    } 


    public function week()
    {
        $now = \Carbon\Carbon::now();
        $weekStart = $now->subDays($now->dayOfWeek)->setTime(0, 0);

        $user = auth('api')->user();  
        $punch = punches::where('user_id','=',$user->id )->where('created_at', '>=', $weekStart)->get() ;
        return $punch;  
    } 


   

   
 
    public function timesheetmanager()
    {
        return User::select('name','status','bio','photo')->latest()->paginate(5);
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
        $user = auth('api')->user(); 
        $this->validate($request, [
            'name'=> 'required|string|max:191',
            'email'=>'required|string|max:191|email|unique:users,email,'.$user->id , //Escape current user
            'password'=>'sometimes|min:6',
            'leaves'=>'integer',
        ]);     
         
         $currentPhoto = $user->photo;
         if($request->photo != $currentPhoto){ 
            File::delete('image/profile/'.$currentPhoto);  
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
            // dd(Carbon::now()->format('h:i:s'));
           
            // $punch_in =  Carbon::now();  
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
            // dd($request['diff']->diffInMinutes(Carbon::now()));
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
            'name'=> 'sometimes|string|max:191',
            'email'=>'sometimes|string|max:191|email|unique:users,email,'.$user->id , //Escape current user
            'password'=>'sometimes|min:6', 
        ]);

        if($request->leaves != null)
        {         
               
            if($request->rm_leave == true ){
                $data = User::where('id',$user->id)->update(['leaves' => DB::raw('leaves + '.$request->leaves)]);                
                dd($data);
            } 
            
            if($request->rm_leave == false){   
                $data = User::where('id',$user->id)->update(['leaves' => DB::raw('leaves -'.$request->leaves)]);              
                dd($data);
            }
        }

        $user->update($request->all());
            
        // $currentPhoto = $user->photo;
         
        //  if($request->photo != $currentPhoto){ 
        //     File::delete('image/profile/'.$currentPhoto);  
        //     $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
        //     Image::make($request->photo)->save(public_path('image/profile/').$name);
        //     $request->merge(['photo'=>$name]);
        //  }  
        
             
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
            })->paginate(5);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}
