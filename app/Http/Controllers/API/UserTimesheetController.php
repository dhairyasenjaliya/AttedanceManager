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

class UserTimesheetController extends Controller
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
         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    public function userid(Request $request)
    { 
        $this->authorize('isAdmin');
        $punch = punches:: where('user_id','=',$request->id )->whereDate('created_at', '=',$request->date)->get();
        // $user = User::where('id',$request->id)->get(['name']);
        return $punch; 
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
        $punch = punches::findOrFail($id); 
        if($punch->punch_out){
            $punch->delete();
            // $user = User::where('id',$punch->user_id)->update(['status' => 'Out']); 

           $user = DB::table('users')
            ->where('id', $punch->user_id)->update(['status' => 'In']);
            
        }
        if($punch->punch_in){
            $punch->delete();  
            $user = DB::table('users')->where('id', $punch->user_id)->update(['status' => 'Out']);
        } 
    }
}
