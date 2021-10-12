<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Franchisesmst;
use Illuminate\Support\Facades\Validator;

class FranchiseManagementController extends Controller
{
    /**
     * add new franchises in the database.
     */
    public function addFranchise(Request $request)
    {
        if (!empty($request)) {
            $userData = auth()->user();
            $userID = $userData->id;

            $validateData = Validator::make($request->all(),[
                'franchise_name' => 'required',
                'franchise_abbrivation' => 'required',
                'year' => 'required',
                'franchise_auction_year' => 'required',
                'indian_players_acquired_before_auction' => 'required',
                'pre_auction_budget' => 'required',
                'overseas_players_acquired_before_the_auction' => 'required',
                'rtm_before_auction' => 'required'
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
        
            $checkDetails = Franchisesmst::where('franchise_name', $request->franchise_name)->count();
            
            if ($checkDetails != 1) {
                $franchise = Franchisesmst::create([
                    'franchise_name' => $request->franchise_name,
                    'franchise_abbrivation' => $request->franchise_abbrivation,
                    'year' => $request->year,
                    'franchise_auction_year' => $request->franchise_auction_year,
                    'indian_players_acquired_before_auction' => $request->indian_players_acquired_before_auction,
                    'pre_auction_budget' => $request->pre_auction_budget,
                    'overseas_players_acquired_before_the_auction' => $request->overseas_players_acquired_before_the_auction,
                    'rtm_before_auction' => $request->rtm_before_auction,
                    'franchise_created_by' => $userID,
                    'franchise_modified_by' => $userID,
                ]);

                $response = APIResponse('200', 'Data has been added successfully.');
            } else {
                $response = APIResponse('201', 'Data Already present, please enter another data.');
            }
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
        }

        return $response;
    }

    /**
     * Display the list of all the franchises.
     */
    public function listFranchise(Request $request)
    {
        if (!empty($request)) {
            $data = Franchisesmst::where('franchise_status', 1)->orderBy('player_id', 'desc')->paginate(10);
            
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
        }

        return $response;
    }

    /**
     * Edit specific franchises.
     */
    public function updateFranchise(Request $request, $id) {

        $franchises = Franchisesmst::where('franchise_id', $id)->first();

        if (!$franchises) {
            $response = APIResponse('201', 'Data not found.');
            return $response;
        }

        $userData = auth()->user();
        $userID = $userData->id;

        $validateData = Validator::make($request->all(),[
            'franchise_name' => 'required',
            'franchise_abbrivation' => 'required',
            'year' => 'required',
            'franchise_auction_year' => 'required',
            'indian_players_acquired_before_auction' => 'required',
            'pre_auction_budget' => 'required',
            'overseas_players_acquired_before_the_auction' => 'required',
            'rtm_before_auction' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $updated = Franchisesmst::where('franchise_id', $id)
                   ->where('franchise_status', '=', 1)
                   ->update([
                       'franchise_name' => $request->get('franchise_name'),
                       'franchise_abbrivation' => $request->get('franchise_abbrivation'),
                       'year' => $request->get('year'),
                       'franchise_auction_year' => $request->get('franchise_auction_year'),
                       'indian_players_acquired_before_auction' => $request->get('indian_players_acquired_before_auction'),
                       'pre_auction_budget' => $request->get('pre_auction_budget'),
                       'overseas_players_acquired_before_the_auction' => $request->get('overseas_players_acquired_before_the_auction'),
                       'rtm_before_auction' => $request->get('rtm_before_auction'),
                       'franchise_created_by' => $request->get('franchise_created_by'),
                       'franchise_modified_by' => $request->get('franchise_modified_by')
                    ]);
                   
        if ($updated) {
            $response = APIResponse('200', 'Data updated successfully.');
            return $response;
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
            return $response;
        }
    }

    /**
     * search specific franchises.
     */
    public function searchFranchise($franchiseName) {
        if (!empty($franchiseName)) {
            $data = Franchisesmst::where('franchise_name', 'LIKE', '%'.$franchiseName.'%')->where('franchise_status', '=', 1)->get();
            
            if (count($data) > 0) {
                $response = APIResponse('200', 'Success', $data);
                return $response;
            } else {
                $response = APIResponse('201', 'Data not found.');
                return $response;
            }
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
            return $response;
        }
    }

    /**
     * delete multiple franchises.
     */
    public function bulkDeleteFranchises(Request $request)
    {
        $franchiseIDs = $request->get('franchise_ids');

        $deleted = Franchisesmst::whereIn('franchise_id', $franchiseIDs)->update(['franchise_status' => 0]);

        $response = APIResponse('200', 'Data Deleted.');
        return $response;
    }

    /**
     * get franchises details by id.
     */
    public function getFranchises($id)
    {
        if (!empty($id)) {
            $data = Franchisesmst::where('franchise_id', $id)->where('franchise_status', '=', 1)->first();
            
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
                return $response;
            } else {
                $response = APIResponse('201', 'Data not found.');
                return $response;
            }         
        } else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
            return $response;
        }
    }
}