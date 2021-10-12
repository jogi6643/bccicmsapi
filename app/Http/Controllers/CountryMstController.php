<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countrymst;

class CountryMstController extends Controller
{
    /**
     * Display the list of all the countries.
     */
    public function listCountry(Request $request)
    {
        $data = Countrymst::where('country_status', 1)->select('country_id', 'country_name', 'country_flag')->get();

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
        }
       
        return $response;
    }
}
