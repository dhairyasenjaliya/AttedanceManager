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
        return User::select('id','email','name','status','bio','photo')->latest()->paginate(12);
    }


     

    public function authuser(Request $request)
    {  
        $user = User::where('id',$request->id)->get();
        return $user;
    }  
 
    public function timesheet(Request $request)
    {
        $date = $request->date;        
        $user = auth('api')->user();
        $punch = punches::where('user_id','=',$user->id )->whereDate('created_at', '=', $date)->get() ;   
        return $punch;  
    }  

    public function daily()
    {       
        $user = auth('api')->user();
        $punch = punches::where('user_id','=',$user->id )->whereDate('created_at', '=',Carbon::today()->toDateString())->get() ;   
        return $punch;  
    }  

    public function year(Request $request)
    {  
        if($request->id != null){
            $punch = punches::where('user_id','=',$request->id)->get();  
        }
        else{
            $user = auth('api')->user();  
            $punch = punches::where('user_id','=',$user->id)->get();   
        }
        return $punch;  
    }  


    public function month(Request $request)
    {
        $currentMonth = date('m');
        if($request->id != null){
            $punch = punches::where('user_id','=',$request->id)->whereRaw('MONTH(created_at) = ?',[$currentMonth])->get() ;
        }
        else{
            $user = auth('api')->user();  
            $punch = punches::where('user_id','=',$user->id )->whereRaw('MONTH(created_at) = ?',[$currentMonth])->get() ;
        }   
        return $punch;  
    } 


    public function week(Request $request)
    {
        $now = \Carbon\Carbon::now();
        $weekStart = $now->subDays($now->dayOfWeek)->setTime(0, 0);

        if($request->id != null){
            $punch = punches::where('user_id','=',$request->id)->where('created_at', '>=', $weekStart)->get() ;
        }
        else{
            $user = auth('api')->user();  
            $punch = punches::where('user_id','=',$user->id )->where('created_at', '>=', $weekStart)->get() ;
        }    
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
        }  
        return ['meassage'=>  $user->status];
    } 

    public function adminpunch(Request $request)
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


    public function leave()
    {
        return auth('api')->user();
    } 

    public function casule_leave(Request $request)
    {  
          
        $user = User::findOrFail($request->id); 
       if($request->rm_leave == true ){
                $data = User::where('id',$user->id)->update(['leaves' => DB::raw('leaves + '.$request->leaves)]);  
                dd($data);
            } 
            
            if($request->rm_leave == false){   
                $data = User::where('id',$user->id)->update(['leaves' => DB::raw('leaves -'.$request->leaves)]);  
                dd($data);
            
        }
    }

    public function medical_leave(Request $request )
    {   
        
        $user = User::findOrFail($request->id); 
            
            if($request->medical_rm_leave == true ){
                $data = User::where('id',$user->id)->update(['medical_leaves' => DB::raw('medical_leaves + '.$request->medical_leaves)]);                
                dd($data);
            } 
            
            if($request->medical_rm_leave == false){   
                $data = User::where('id',$user->id)->update(['medical_leaves' => DB::raw('medical_leaves -'.$request->medical_leaves)]);              
                dd($data);
            } 
    }

    public function unpaid_leave(Request $request )
    {   
        $user = User::findOrFail($request->id); 
            if($request->unpaid_rm_leave == true ){
                $data = User::where('id',$user->id)->update(['unpaid_leaves' => DB::raw('unpaid_leaves + '.$request->unpaid_leaves)]);                
                dd($data);
            } 
            
            if($request->unpaid_rm_leave == false){   
                $data = User::where('id',$user->id)->update(['unpaid_leaves' => DB::raw('unpaid_leaves -'.$request->unpaid_leaves)]);              
                dd($data);
            } 
    } 

    public function update(Request $request, $id)
    {   
        
        $this->authorize('isAdmin'); 
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name'=> 'sometimes|string|max:191',
            'email'=>'sometimes|string|max:191|email|unique:users,email,'.$user->id , //Escape current user
            'password'=>'sometimes|min:6', 
        ]);
        
        if(!empty($request->password))
        {
            $request->merge(['password'=>Hash::make($request['password'])]);
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
