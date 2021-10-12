<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Audio;
use App\Models\AssetsCount;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $audios = Audio::where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($audios))
                $response = APIResponse('200', 'Success', $audios);
            else
                $response = APIResponse('201', 'No audio found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
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

            if (!empty($request)) {
                $data_details = AssetsCount::all();
    
                $validateData = Validator::make($request->all(),[
                    'title' => 'required',
                    'description' => 'required',
                ]);
    
                if ($validateData->fails()) {
                    $messages = $validateData->errors()->all();
                    return APIResponse('201', 'Validation errors.', $messages);
                }
    
                $audioCount = AssetsCount::all();
                $audioCount = $audioCount[0]['audio_count'] + 1;
    
                $audio = new Audio();
                $audio->ID = $audioCount;
                $audio->title = $request->get('title');   
                $audio->description = $request->get('description');
                $audio->short_description = $request->get('short_description');
                $audio->audio_duration= $request->get('audio_duration');
                $audio->match_id = $request->get('match_id');
                $audio->content_type= $request->get('content_type');
                $audio->audio_scope = $request->get('audio_scope');
                $audio->audio_url = $request->get('audio_url');
                $audio->match_formats = $request->get('match_formats');
                $audio->keywords = $request->get('keywords');
                $audio->status = true;
                $audio->created_date = date("Y-m-d H:i:s");
                $audio->publish_date = $request->get('publish_date');
                $audio->publish_by = $request->get('publish_by');
                $audio->meta_languages = $request->get('meta_languages');
                $audio->language = $request->get('language');
                $audio->asset_type = $request->get('asset_type');
                $audio->total_viewcount = $request->get('total_viewcount');
                $audio->titleslug = $request->get('titleslug');
                $audio->thumbnail = $request->get('thumbnail');
                $audio->commentsOn = $request->get('commentsOn');
                $audio->platform = $request->get('platform');
                $audio->current_status = $request->get('current_status');
               
                $audio->save();
    
                $totalCount = AssetsCount::where('ID', 1)->update(['audio_count' => $audioCount]);
                
                if ($audio)
                    $response = APIResponse('200', 'Audio has been added successfully.');
                else
                   $response = APIResponse('201', 'Something went wrong, please try again.'); 
                        
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }
            
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

            $audio = Audio::where('ID', (int) $id)->first();
    
            if (!empty($audio))
                $response = APIResponse('200', 'Success', $audio);
            else
                $response = APIResponse('201', 'No audio found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
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

            $audio = Audio::where('ID', (int) $id)->get();
    
            if (!$audio)
                return APIResponse('201', 'No audio found.');
    
            $validateData = Validator::make($request->all(),[
                'title' => 'required',
                'description' => 'required',
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $dataArr = [
                'title' => $request->get('title'),
                'description' => $request->get('description'), 
                'short_description' => $request->get('short_description'),
                'audio_duration' => $request->get('audio_duration'),      
                'match_id' => $request->get('match_id'),  
                'content_type' => $request->get('content_type'), 
                'audio_scope' => $request->get('audio_scope'),
                'audio_url' => $request->get('audio_url'),
                'match_formats' => $request->get('match_formats'),
                'keywords' => $request->get('keywords'),
                'publish_date' => $request->get('publish_date'),
                'published_by' => $request->get('published_by'),
                'meta_languages' => $request->get('meta_languages'),  
                'language' => $request->get('language'), 
                'asset_type' => $request->get('asset_type'),
                'total_viewcount' => $request->get('total_viewcount'),
                'titleslug' => $request->get('titleslug'),
                'status' => true,
                'thumbnail' => $request->get('thumbnail'),
                'commentsOn' => $request->get('commentsOn'),  
                'platform' => $request->get('platform'), 
                'current_status' => $request->get('current_status'),
                'updated_date' => date("Y-m-d H:i:s"),
            ];
    
            $udpated = Audio::where('ID', (int) $id)->update($dataArr);
    
            if ($udpated)
                $response = APIResponse('200', 'Audio has been updated successfully.');
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

            $udpated = Audio::where('ID', (int) $id)->update(['status' => false]);
    
            if ($udpated)
                $response = APIResponse('200', 'Audio has been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }

    /**
     * List audios with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listAudios(Request $request)
    {
        try {

            $query = Audio::where('status', true);
    
            if ($request->get('title') != '')
                $query->where('title', 'like', '%'.$request->get('title').'%');
            
            if (is_array($request->get('language')) && count(array_filter($request->get('language'))) > 0)
                $query->whereIn('language', $request->get('language'));
            
            if (is_array($request->get('current_status')) && count(array_filter($request->get('current_status'))) > 0)
                $query->whereIn('current_status', $request->get('current_status'));
    
            if ($request->get('max_items') != '')
                $query->limit((int) $request->get('max_items'));
    
            if ($request->get('content_from') != '') {
                $contentFrom = $request->get('content_from');
                switch ($contentFrom) {
                    case 'The last year':
                        $query->whereYear('publish_date', date('Y', strtotime('-1 year')));
                        break;
                    
                    case 'Last 2 years':
                        $query->whereYear('publish_date', date('Y', strtotime('-2 year')));
                        break;
    
                    case 'Last 3 years':
                        $query->whereYear('publish_date', date('Y', strtotime('-3 year')));
                        break;
                }
            }
    
            if ($request->get('sort_by') != '' && $request->get('order') != '') {
                $sortBy = $request->get('sort_by');
                $order = $request->get('order');
                switch ($sortBy) {
                    case 'Last updated':
                        $query->orderBy('updated_date', $order);
                        break;

                    case 'Status':
                        $query->orderBy('current_status', $order);
                        break;
                    
                    case 'Publication date':
                        $query->orderBy('publish_date', $order);
                        break;

                    default:
                        $query->orderBy('ID', $order);
                        break;
                }
            } else {
                $query->orderBy('ID', 'desc');
            }
                    
            $audios = $query->get();
    
            if (!empty($audios))
                $response = APIResponse('200', 'Success', $audios);
            else
                $response = APIResponse('201', 'No audio found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Audios by title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByTitle(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'search_term' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $search_term = $request->get('search_term');
    
            $audios = Audio::where('title', 'like', '%'.$search_term.'%')
                                ->where('status', true)
                                ->orderBy('ID', 'desc')
                                ->get();
    
            if (!empty($audios))
                $response = APIResponse('200', 'Success', $audios);
            else
                $response = APIResponse('201', 'No audios found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Audios by language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByLanguage(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'language' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $language = $request->get('language');
    
            $audios = Audio::where('language', $language)
                            ->where('status', true)
                            ->orderBy('ID', 'desc')
                            ->get();
    
            if (!empty($audios))
                $response = APIResponse('200', 'Success', $audios);
            else
                $response = APIResponse('201', 'No audios found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                
        return $response;
    }

    /**
     * Filter Audios by status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByStatus(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'status' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $status = $request->get('status');
    
            $audios = Audio::where('current_status', $status)
                            ->where('status', true)
                            ->orderBy('ID', 'desc')
                            ->get();
    
            if (!empty($audios))
                $response = APIResponse('200', 'Success', $audios);
            else
                $response = APIResponse('201', 'No audios found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Bulk Delete Audios
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDeleteAudios(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'audio_ids.*' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
            
            $audioIds = $request->get('audio_ids');
            $audioIds = array_map('intval', $audioIds);
    
            $deleted = Audio::whereIn('ID', $audioIds)->update(['status' => false]);
    
            if ($deleted)
                $response = APIResponse('200', 'Audios have been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }
}
