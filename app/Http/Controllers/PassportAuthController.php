<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Validator;
use Aws\Kms\KmsClient;
use Aws\Exception\AwsException;
class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $name = $request->name;
        if($email != '' && $password != '' && $name != ''){
            $this->validate($request, [
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
                
            ]);
            $user = User::where('email', $request->email)->count();
            // echo '<pre>';print_r($user);exit; 
            if($user != 1){
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'user_status'=>1
                ]);            
                $token = $user->createToken('LaravelAuthApp')->accessToken;        
                return response()->json(['token' => $token], 200);
            }else{
                $response = APIResponse('201', 'Duplicate Entry');
                return $response;
            }
                
        }else{
            $response = APIResponse('201', 'Name Email and Password is mandatory');
            return $response;
        }
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $validateData= Validator::make($request->all(),[
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
            'password' => 'required',],
    );
        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $email = $request->email;
        $password = $request->password; 
        $userCount = User::where('email', $request->email)->count();
          if($userCount>0){
            $user = User::where('email', $request->email)->first();
            $data = [
                'email' => $email,
                'password' => $password
            ];
            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $data = [
                    'token' => $token,
                    'name' => $user->name
                ];
                $response = APIResponse('200', 'User Logged in successfully', $data);
                return $response;
            } else {
                $response = APIResponse('201', 'Wrong Credentials');
                return $response;
            }
        }
        else{
            $response = APIResponse('201', 'User does not exist');
            return $response;
        }
        
    }   

    /**
     * Forget password
     */
    public function forgotpass(Request $request){
        $validateData= Validator::make($request->all(),[
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
        ],
        );

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
           
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $to     =  $request->email;
        $mail   =  User::where('email',$to)->first();
      
        if(!$mail){
            $response = APIResponse('201', 'User  does not exist in database');
        }else{
            $six_digit_random_number_otp = random_int(100000, 999999);
            User::where('email', $to)->update(['user_otp'=>$six_digit_random_number_otp]);
            $details = ['to'=>$to,'otp'=>$six_digit_random_number_otp];
            Mail::to($to)->send(new \App\Mail\NotifyMail($details));
            $response = APIResponse('200', 'Otp sent your registered Email.please check your Email');
        }

        return $response;

    }
     /**
     * Reset password
     */
    public function resetpass(Request $request){

       
        $validateData= Validator::make($request->all(),[
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
            'password' => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$/',
            'otp'      => 'required',
        ],
        [
            'password.regex'    => 'password must contain at least one digit,one special character and first letter should always be a normal character.',
        ]
      
        );

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }


        $email      =   $request->email;
        $password   =   $request->password;
        $otp        =   $request->otp;
        
        $mail   =  User::where('email', $email)->first();
     
        if(!$mail){
            $response = APIResponse('201', 'this email ID does not exist in database');
        }else{
            $mail   =  User::where('email', $email)->where('user_otp', $otp)->first();
            
            if(!$mail){
                $response = APIResponse('201', 'Otp does not match. Please try again.');
            }
            else{
                  User::where('email', $email)
                    ->update([
                        'password' => bcrypt($request->password),
                        'user_otp' => '',
                    ]);
                $response = APIResponse('200', 'password updated successfully.');
            }
    
        }

        return $response;  
       
    }

    public function kms(Request $request){

        // Create a KmsClient 
$KmsClient =KmsClient::factory([
    'credentials' => array(
        'key' => 'AKIAQ5OFAS6CZ2ANI2PQ',
        'secret' => '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',
    ),
    'region' => 'ap-south-1', // dont forget to set the region
    'version' => 'latest', // version string
]);

$ciphertext ='AQIDAHg/4Gvf6puDEaq3pajC6BcrwiaES5y5+L57QWRCwjFNTgFJXFV5XjyqA11OsZQ/+af0AAAAbjBsBgkqhkiG9w0BBwagXzBdAgEAMFgGCSqGSIb3DQEHATAeBglghkgBZQMEAS4wEQQMFgoeTty6yRJsgV9mAgEQgCuZ4o9q2+DX+83IXPeRJycyv9Bu6mreDSDILJ2y/3isBymTVM7M5IpOBgAE';
$decoded = base64_decode($ciphertext);

 $data = ['CiphertextBlob' => $decoded,'EncryptionContext'=>['service' => 'elastictranscoder.amazonaws.com']];
 //print_r($data);exit;
try {
   // Decrypt - should match $orig
$result = $KmsClient->decrypt($data);
$plaintext = $result['Plaintext'];
return $plaintext;
//print_r($plaintext);exit;
    //var_dump($result->get('Plaintext'));exit;
} catch (AwsException $e) {
    // Output error message if fails
    echo $e->getMessage();
    echo "\n";
}
    }

}
