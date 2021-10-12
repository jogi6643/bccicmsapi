<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userroles;
use Illuminate\Support\Facades\Validator;
use DB;
class PrivilegesUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::beginTransaction();
        try {
        $privilegesUser = Userroles::paginate(10);
        if (!empty($privilegesUser)) {
            $response = APIResponse('200', 'Success', $privilegesUser);
            return $response;
        } else {
            $response = APIResponse('201', 'Data not found.');
            return $response;
        }
    } catch(\Exception $e) {
        DB::rollBack();
        
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
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
        $user_privileg_status = $request->user_privileg_status;
        $validateData= Validator::make($request->all(),[
            'role_name' => 'required',
           ]);
       
        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            
            return APIResponse('201', 'Validation errors.', $messages);
        }
        
        if($user_privileg_status == '')
        {
         try{
            $privilegesUser                             =      new Userroles();
            $privilegesUser->role_name                  =     $request->role_name;
            $privilegesUser->role_status                =     $request->role_status;
            $privilegesUser->read                       =     $request->read??"";
            $privilegesUser->write                      =     $request->write??"";
            $privilegesUser->country_nationality        =     $request->country_nationality??"";
            $privilegesUser->save();

            if ($privilegesUser)
            {
                 $response = APIResponse('200', 'Success', $privilegesUser); 
                 return $response;
            }
            else
            {
            $response = APIResponse('201', 'Privileges not added');
            return $response;
        }
    }
        catch(\Exception $e) {
            
            $response = APIResponse('201', 'Oops. Something went wrong.');
             return $response;
        }
  
    }
        else
        {

          
        $privilege_User_Update = Userroles::where('role_id', $user_privileg_status)->count();
       
        if ($privilege_User_Update == 0) {
                $response = APIResponse('201', 'Privileges not found');
                return $response;
        }
        else
        {
         try{
         $privilege_User_Update = Userroles::where('role_id', $user_privileg_status)
                                ->update(
                                        [
                                            'role_name'                     =>     $request->role_name,
                                            'role_status'                   =>     $request->role_status,
                                            'read'                          =>     $request->read??"",
                                            'write'                         =>     $request->write??"",
                                            'country_nationality'           =>     $request->country_nationality??"",
                                        ]
                
                                        );

                                        if ($privilege_User_Update)
                                        {
                                        $response = APIResponse('200', 'Privilege  updated successfully');
                                        return $response;
                                    }
                                    else
                                    {
                                        $response = APIResponse('201', 'Privilege can not be updated');
                                        return $response;
                                    }
                                }
                                    catch(\Exception $e) {
                               
                                        $response = APIResponse('201', 'Oops. Something went wrong.');
                                         return $response;
                                    }

        }
  
    }

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

