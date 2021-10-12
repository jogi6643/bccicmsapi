<?php

namespace App\Repositories;

use App\Http\Requests\ImageRequest;
use App\Interfaces\ImageInterface;
use App\Models\User;
use DB;

class ImageRepostitory implements ImageInterface
{
    // Use ResponseAPI Trait in this repository
    // use ResponseAPI;

    public function getAllImages()
    {
         // Returning all users
        try {
            $user_list = User::paginate(10);
            $response = APIResponse('200', 'Success', $user_list);
            return $response;
        } catch(\Exception $e) {
            $response = APIResponse('201', 'Oops. Something went wrong.');
             return $response;
        }
      
       
        
       
    }

    // public function getUserById($id)
    // {
    //     try {
    //         $user = User::find($id);
            
    //         // Check the user
    //         if(!$user) return $this->error("No user with ID $id", 404);

    //         return $this->success("User Detail", $user);
    //     } catch(\Exception $e) {
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }

    public function requestImage(Request $request, $id = null)
    {
        
        DB::beginTransaction();
        try {
            // If images exists when we find it
            // Then update the user
            // Else create the new one.
            $user = $id ? User::find($id) : new User;

            // Check the user 
            if($id && !$user) return $this->error("No user with ID $id", 404);

            $user->name = $request->name;
            // Remove a whitespace and make to lowercase
            $user->email = preg_replace('/\s+/', '', strtolower($request->email));
            
            // I dont wanna to update the password, 
            // Password must be fill only when creating a new user.
            if(!$id) $user->password = \Hash::make($request->password);

            // Save the user
            $user->save();

            DB::commit();
            return $this->success(
                $id ? "User updated"
                    : "User created",
                $user, $id ? 200 : 201);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    // public function deleteUser($id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $user = User::find($id);

    //         // Check the user
    //         if(!$user) return $this->error("No user with ID $id", 404);

    //         // Delete the user
    //         $user->delete();

    //         DB::commit();
    //         return $this->success("User deleted", $user);
    //     } catch(\Exception $e) {
    //         DB::rollBack();
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }
}