<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playersmst;
use Storage;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $players = Playersmst::where('player_status', '!=', '0')->orderBy('player_id', 'desc')->paginate(10);

            if ($players->count() > 0)
                $response = APIResponse('200', 'Success', $players);
            else 
                $response = APIResponse('201', 'No players found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
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
        try {

            $userData = auth()->user();

            $userID = $userData->id;

            $validateData = Validator::make($request->all(),[
                'name' => 'required',
                'nationality' => 'required',
                'speciality' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'reserve_price' => 'required|numeric',
                'year' => 'required|numeric'
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }

            $photoURL = '';
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $folderpath = 'ipl/addplayer/';
                $result = uploadFileToS3($file, $folderpath);
                $photoURL = $result['ObjectURL'] ?? '';
            }

            $player = new Playersmst([
                'player_name' => $request->get('name'),
                'player_nationality' => $request->get('nationality'),
                'marquee_player' => $request->get('marquee_player'),
                'bought_via_rtm' => $request->get('bought_via_rtm'),
                'player_speciality' => $request->get('speciality'),
                'player_auction_status' => $request->get('status'),
                'user_photo_url' => $photoURL,
                'reserve_price' => $request->get('reserve_price'),
                'year' => $request->get('year'),
                'player_created_by' => $userID,
                'player_modified_by' => $userID
            ]);
    
            if ($player->save())
                $response = APIResponse('200', 'Player has been added successfully.', $player);
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $player = Playersmst::find($id);

            if (!$player)
                return APIResponse('201', 'Player not found.');
            
            $response = APIResponse('200', 'Success', $player);

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
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
        try {

            $player = Playersmst::find($id);
    
            if (!$player)
                return APIResponse('201', 'Player not found.');
    
            $userData = auth()->user();
    
            $userID = $userData->id;
    
            $validateData = Validator::make($request->all(),[
                'name' => 'required',
                'nationality' => 'required',
                'speciality' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'reserve_price' => 'required|numeric',
                'year' => 'required|numeric',
                'hammer_price' => 'required|numeric'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $photoURL = '';
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $folderpath = 'ipl/addplayer/';
                $result = uploadFileToS3($file, $folderpath);
                $photoURL = $result['ObjectURL'] ?? '';
            }
    
            $player->player_name = $request->get('name');
            $player->player_nationality = $request->get('nationality');
            $player->marquee_player = $request->get('marquee_player');
            $player->bought_via_rtm = $request->get('bought_via_rtm');
            $player->player_speciality = $request->get('speciality');
            $player->player_auction_status = $request->get('status');
            $player->user_photo_url = $photoURL;
            $player->reserve_price = $request->get('reserve_price');
            $player->year = $request->get('year');
            $player->hammer_price = $request->get('hammer_price');
            $player->franchise_id = $request->get('franchise_id');
            $player->player_modified_by = $userID;
    
            $udpated = $player->update();
    
            if ($udpated)
                $response = APIResponse('200', 'Player has been updated successfully.', $player);
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $player = Playersmst::find($id);
    
            if (!$player)
                return APIResponse('201', 'Player not found.');
    
            $player->player_status = 0;
    
            $udpated = $player->update();
    
            if ($udpated)
                $response = APIResponse('200', 'Player has been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Store a multi players via CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertMultiPlayers(Request $request)
    {
        try {

            $this->validate($request, [
                'file' => 'required|mimes:csv|max:10240',
            ]);
    
            if($request->hasfile('file'))
            {
                $file = $request->file('file');
                $original_name = $file->getClientOriginalName();
    
                $serverFileName = time().'_'.$original_name;
                $header = null;
                $data = $errorData = array();
                if (($handle = fopen($file, 'r')) !== false) {
                    $rowCount = 1;
                    while ( ($row = fgetcsv($handle, 200, ",")) !== false ) {
                        if(!$header){
                            foreach ($row as $key => $value) {
                                $header[] = strtolower(str_replace(" ", "_", $value));
                            }
                        } else {
                            $rowData = array_combine($header, $row);
                            if ($rowData['player_name'] == '' || $rowData['player_nationality'] == '' || $rowData['player_speciality'] == '') {
                                if ($rowData['player_name'] == '')
                                    $errorData[$rowCount][] = 'Player Name is required.';
    
                                if ($rowData['player_nationality'] == '')
                                    $errorData[$rowCount][] = 'Player Nationality is required.';
    
                                if ($rowData['player_speciality'] == '')
                                    $errorData[$rowCount][] = 'Player Speciality is required.';
                            } else {
                                $rowData['player_modified_by'] = $rowData['player_created_by'];
                                $rowData['created_at'] = now();
                                $rowData['updated_at'] = now();
                                $data[] = $rowData;
                            }
                            
                        }
                        $rowCount++;
                    }
                    if (!empty($errorData)) {
                        
                        $content = '';
                        foreach ($errorData as $rowNum => $error) {
                            foreach ($error as $errorStr) {
                                $content .= 'Row '. $rowNum . ': ' . $errorStr . "\n";
                            }
                        }
    
                        $errorFileName = 'error_' . $serverFileName . '.txt';
                        Storage::disk('public')->put('multi_players_error/' . $errorFileName, $content);
                        $errorFilePath = Storage::disk('public')->path('multi_players_error/' .$errorFileName);
    
                        $headers = ['Content-type'=>'text/plain', 
                                    'Content-Disposition'=>sprintf('attachment; filename="%s"', $errorFileName)
                                    ];
    
                        return response()->file($errorFilePath, $headers);
                    } else {
                        Playersmst::insert($data);
    
                        return APIResponse('200', 'Player has been imported successfully.');
                    }
                    fclose($handle);
                } else {
                    return APIResponse('201', 'Something went wrong, please try again.');
                }
            } else {
                return APIResponse('201', 'File is required.');
            }

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
    }

    /**
     * Store a multi players via CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPlayers(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'search' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $search = $request->get('search');
            $players = Playersmst::where('player_name', 'like', '%'.$search.'%')
                        ->where('player_status', '!=', '0')
                        ->orderBy('player_id', 'desc')
                        ->paginate(10);
    
            if ($players->count() > 0)
                $response = APIResponse('200', 'Success', $players);
            else
                $response = APIResponse('200', 'No Results Found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Bulk Delete Players
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDeletePlayers(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'player_ids.*' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $playerIDs = $request->get('player_ids');
    
            $deleted = Playersmst::whereIn('player_id', $playerIDs)->update(['player_status' => 0]);
    
            if ($deleted)
                return APIResponse('200', 'Players have been deleted successfully.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
    }
}
