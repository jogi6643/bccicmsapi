<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usersmst;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;
class UserManagementController extends Controller
{

    public function index()
    {
        DB::beginTransaction();
        try {
        $user_list = Usersmst::where('user_status', '<>' , 2)->orderBy('user_id', 'DESC')->paginate(10);
        $response = APIResponse('200', 'Success', $user_list);
        return $response;
     } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
        $user = Usersmst::where('user_id', $id)->first();
        if (!$user) {
            $response = APIResponse('201', 'User not found ');
             return $response;
        }
        $response = APIResponse('200', 'Success', $user->toArray());
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    public function store(Request $request)
    {
        

        $user_update_status = $request->user_update_status;
        if($user_update_status == '')
        {
   
   
            $validateData= Validator::make($request->all(),[
                'user_first_name' => 'required',
                'user_last_name' => 'required',
                'user_password'=>'required',
                'c_password'=>'required|same:user_password',
                'user_title' => 'required',
                'user_phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'user_country_id' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'user_address' => 'required',
                'user_role_id'=>'required',
                'user_status'=>'required',
                'user_email_id'=>'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|email|unique:usersmsts',
                // 'email'=>'required|email|unique:users',
               ]);
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
            try {
            $imageUrl = '';
            if ($request->hasFile('user_photo_url')) {
                $file = $request->file('user_photo_url');
                $folderpath = 'bcci/images/';
                $result = uploadFileToS3($file,$folderpath);
                $imageUrl = $result['ObjectURL'] ?? '';
            }   

        $user                             =      new Usersmst();
        $user->user_first_name            =     $request->user_first_name;
        $user->user_last_name             =     $request->user_last_name;
        $user->user_password              =     $request->user_password;
        $user->user_title                 =     $request->user_title;
        $user->user_phone_number          =     $request->user_phone_number;
        $user->user_country_id            =     $request->user_country_id;
        $user->user_address               =     $request->user_address;
        $user->user_role_id               =     $request->user_role_id;
        $user->user_email_id              =     $request->user_email_id;
        $user->user_status              =       $request->user_status;
        $user->user_dob                   =     '2017-06-15';
        $user->user_group_id              =     12;
        $user->user_created_by            =     1;
        $user->user_modified_by           =     1;
        $user->user_season_id             =     "Id eveniet atque ea sed.";
        $user->association_id             =     421;
        $user->device_id                  =     "Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.";
        $user->flag_id                    =     41;
        $user->user_otp                   =     101010;
        $user->user_gender                =     2;
        $user->user_photo_url             =     $imageUrl;
        $user->save();

        $usertbl                          =      new User();
        $usertbl->name                    =     $request->user_first_name." ".$request->user_last_name;
        $usertbl->email                   =     $request->user_email_id;
        $usertbl->user_status             =     $request->user_status??0;
        $usertbl->password                =     bcrypt($request->user_password);
        $usertbl->save();
        if ($user && $usertbl)
        {
            $response = APIResponse('200', 'Success', $user);
            return $response;
        }
        else
        {
            $response = APIResponse('201', 'User not added');
             return $response;
        }

    } catch(\Exception $e) {
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }

        }
        else
        {
           
            $validateData= Validator::make($request->all(),[
                'user_first_name' => 'required',
                'user_last_name' => 'required',
                'user_password'=>'required',
                'c_password'=>'required|same:user_password',
                'user_title' => 'required',
                'user_phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'user_country_id' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'user_address' => 'required',
                'user_role_id'=>'required',
                'user_status'=>'required',
                // 'user_email_id'=>'email|unique:usersmsts',
               ]);
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
     try {
            $User_Update = Usersmst::where('user_id', $user_update_status)->count();
        if ($User_Update == 0) {
            $response = APIResponse('201', 'User not found');
            return $response;
        }
        else
        {
            $img = $request->hasFile('user_photo_url');
            if($img===true)
            {
            if ($request->hasFile('user_photo_url')) {
                $file = $request->file('user_photo_url');
                $folderpath = 'bcci/images/';
                $result = uploadFileToS3($file,$folderpath);
                $imageUrl = $result['ObjectURL'] ?? '';
            }  
        }
        $User_Update = Usersmst::where('user_email_id', $request->user_email_id)
        ->update(
                [
                'user_first_name'            =>     $request->user_first_name,
                'user_last_name'             =>     $request->user_last_name,
                'user_password'              =>     $request->user_password,
                'user_title'                 =>     $request->user_title,
                'user_phone_number'          =>     $request->user_phone_number,
                'user_country_id'            =>     $request->user_country_id,
                'user_address'               =>     $request->user_address,
                'user_role_id'               =>     $request->user_role_id,
                'user_email_id'              =>     $request->user_email_id,
                'user_status'              =>       $request->user_status,
                'user_dob'                   =>     '2017-06-15',
                'user_group_id'              =>     12,
                'user_created_by'            =>     1,
                'user_modified_by'           =>     1,
                'user_season_id'             =>     "Id eveniet atque ea sed.",
                'association_id'             =>     421,
                'device_id'                  =>     "Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.",
                'flag_id'                    =>     41,
                'user_otp'                   =>     101010,
                'user_gender'                =>     2,
                // 'user_photo_url'             =>     $imageUrl,
                ]
            
            );
            if($img===true)
            {
                Usersmst::where('user_email_id', $request->user_email_id)->update(['user_photo_url'=>$imageUrl]);
            }
            $Usertbl = User::where('email', $request->user_email_id)
            ->update(
                    [
                    'name'                    =>     $request->user_first_name." ".$request->user_last_name,
                    'password'                =>     bcrypt($request->user_password),
                    'user_status'             =>     $request->user_status??0,
                    ]
                );
            if ($User_Update && $Usertbl)
            {
                $response = APIResponse('200', 'User updated successfully');
                return $response;
            }
        else
        {
            $response = APIResponse('200', 'User Already updated');
            return $response;
        }
  
        }
    } catch(\Exception $e) {
        
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }
}

   

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
        $user = Usersmst::where('user_id', $id)->where('user_status', '<>' , 2)->first();
        if (!$user) {
            $response = APIResponse('201', 'User not found');
            return $response;
        }
        $useremail = Usersmst::where('user_id', $id)->first()->user_email_id;
        $userid = User::where('email', $useremail)->first()->id;
        $deleted1 = User::where('id',$userid)->update(['user_status' => 2]);
        if ($user->where('user_id', $id)->update(['user_status'=>2]) &&  $deleted1) {
            $response = APIResponse('200','User deleted Successfully');
            return $response;
        } else {
            $response = APIResponse('201', 'User can not be deleted');
            return $response;
        }

    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    // Search by field
    public function search_users(Request $request)
    {
        DB::beginTransaction();
        try {
        $data = $request['data'];
        $user_list = Usersmst::where('user_status', '<>' , 2)
                         ->where('user_first_name', 'like', "%{$data}%")
                         ->orWhere('user_last_name', 'like', "%{$data}%")
                         ->orWhere('user_email_id', 'like', "%{$data}%")
                         ->orWhere('user_id', 'like', "%{$data}%")
                         ->paginate(10);
        $response = APIResponse('200', 'Success', $user_list);
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    // Bulk Delete Users
    public function bulkDeleteUser(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'user_ids.*' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {
        $ids = $request->get('user_ids');
        $userids = array();
        $email = Usersmst::select('user_email_id')->whereIn('user_id',$ids)->get();
        foreach ($email as $key => $value) {
            $userids[] = User::where('email',$value->user_email_id)->first()->id??0;
        }
        
        $deleted = Usersmst::whereIn('user_id',$ids)->update(['user_status' => 2]);
        $deleted1 = User::whereIn('id',$userids)->update(['user_status' => 2]);
    
        if ($deleted && $deleted1)
        {
            $response = APIResponse('200','User deleted Successfully');
            return $response;
        }
        else
        {
            $response = APIResponse('201','User Not found!');
            return $response;
        }

    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
      
    }
}
