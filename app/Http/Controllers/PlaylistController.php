<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Playlist;
use App\Models\AssetsCount;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $playlists = Playlist::where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($playlists))
                $response = APIResponse('200', 'Success', $playlists);
            else
                $response = APIResponse('201', 'No playlist found.');

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
                    'headline' => 'required',
                    'playlist_type' => 'required'
                ]);
    
                if ($validateData->fails()) {
                    $messages = $validateData->errors()->all();
                    return APIResponse('201', 'Validation errors.', $messages);
                }
    
                $playlistCount = AssetsCount::all();
                $playlistCount = $playlistCount[0]['playlist_count'] + 1;
    
                $playContent = new Playlist();
                $playContent->ID = $playlistCount;
                $playContent->title = $request->get('title');   
                $playContent->url_segment = $request->get('url_segment');
                $playContent->subtitle = $request->get('subtitle');
                $playContent->summary= $request->get('summary');
                $playContent->slug = $request->get('slug');
                $playContent->cover= $request->get('cover');
                $playContent->display_date = $request->get('display_date');
                $playContent->location = $request->get('location');
                $playContent->latitude = $request->get('latitude');
                $playContent->longitude = $request->get('longitude');
                $playContent->status = true;
                $playContent->created_date = date("Y-m-d H:i:s");
                $playContent->publish_date = $request->get('publish_date');
                $playContent->updated_date = $request->get('updated_date');
                $playContent->language = $request->get('language');
                $playContent->headline = $request->get('headline');
                $playContent->tags = $request->get('tags');
                $playContent->references = $request->get('references');
                $playContent->expiryDate = $request->get('expiryDate');
                $playContent->playlist_type = $request->get('playlist_type');
                $playContent->content_type = $request->get('content_type');
                $playContent->current_status = $request->get('current_status');
               
                $playContent->save();
    
                $totalCount = AssetsCount::where('ID', 1)->update(['playlist_count' => $playlistCount]);
                
                if ($playContent)
                    $response = APIResponse('200', 'Playlist has been added successfully.');
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

            $playlist = Playlist::where('ID', (int) $id)->first();
    
            if (!empty($playlist)) {
                $response = APIResponse('200', 'Success', $playlist);
            } else {
                $response = APIResponse('201', 'No playlist found.');
            }
            
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

            $playlist = Playlist::where('ID', (int) $id)->get();
    
            if (!$playlist)
                return APIResponse('201', 'No playlist found.');
    
            $validateData = Validator::make($request->all(),[
                'title' => 'required',
                'headline' => 'required',
                'playlist_type' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $dataArr = [
                'title' => $request->get('title'),
                'subtitle' => $request->get('subtitle'), 
                'summary' => $request->get('summary'),
                'url_segment' => $request->get('url_segment'),      
                'language' => $request->get('language'),  
                'short_description' => $request->get('short_description'), 
                'slug' => $request->get('slug'),
                'cover' => $request->get('cover'),
                'display_date' => $request->get('display_date'),
                'location' => $request->get('location'),
                'published_by' => $request->get('published_by'),
                'publish_date' => $request->get('publish_date'),  
                'related' => $request->get('related'), 
                'content_type' => $request->get('content_type'),
                'coordinates' => $request->get('coordinates'),
                'meta_languages' => $request->get('meta_languages'),
                'status' => true,
                'updated_date' => $request->get('updated_date'),
                'keywords' => $request->get('keywords'),  
                'playlist_type' => $request->get('playlist_type'), 
                'commentsOn' => $request->get('commentsOn'), 
                'current_status' => $request->get('current_status')
            ];
    
            $udpated = Playlist::where('ID', (int) $id)->update($dataArr);
    
            if ($udpated)
                $response = APIResponse('200', 'Playlist has been updated successfully.');
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

            $udpated = Playlist::where('ID', (int) $id)->update(['status' => false]);
    
            if ($udpated)
                $response = APIResponse('200', 'Playlist has been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * List playlists with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listPlaylists(Request $request)
    {
        try {

            $query = Playlist::where('status', true);
    
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
                    
            $playlists = $query->get();
    
            if (!empty($playlists))
                $response = APIResponse('200', 'Success', $playlists);
            else
                $response = APIResponse('201', 'No playlist found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Playlists by title.
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
    
            $playlists = Playlist::where('title', 'like', '%'.$search_term.'%')
                                ->where('status', true)
                                ->orderBy('ID', 'desc')
                                ->get();
    
            if (!empty($playlists))
                $response = APIResponse('200', 'Success', $playlists);
            else
                $response = APIResponse('201', 'No playlists found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Playlists by language.
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
    
            $playlists = Playlist::where('language', $language)
                            ->where('status', true)
                            ->orderBy('ID', 'desc')
                            ->get();
    
            if (!empty($playlists))
                $response = APIResponse('200', 'Success', $playlists);
            else
                $response = APIResponse('201', 'No data found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Playlists by status.
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
    
            $playlists = Playlist::where('current_status', $status)
                            ->where('status', true)
                            ->orderBy('ID', 'desc')
                            ->get();
    
            if (!empty($playlists))
                $response = APIResponse('200', 'Success', $playlists);
            else
                $response = APIResponse('201', 'No data found.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Bulk Delete Playlists
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDeletePlaylists(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'playlist_ids.*' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $playlistIds = $request->get('playlist_ids');
            $playlistIds = array_map('intval', $playlistIds);
    
            $deleted = Playlist::whereIn('ID', $playlistIds)->update(['status' => false]);
    
            if ($deleted)
                $response = APIResponse('200', 'Playlists have been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');
            
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }
}
