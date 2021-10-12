<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoContent;
use App\Models\AssetsCount;
use Illuminate\Support\Facades\Validator;

class VideoContentController extends Controller
{
    /**
     * Add video in the content management.
     */
    public function addVideo(Request $request)
    {
        try{
            if (!empty($request)) {
                $validateData = Validator::make($request->all(),[
                    'title' => 'required',
                    'asset_type' => 'required',
                    'slug' => 'required',
                    'short_description' => 'required|max:520',
                    'description' => 'required',
                    'content_type' => 'required'
                ]);

                if ($validateData->fails()) {
                    $messages = $validateData->errors()->all();
                    return APIResponse('201', 'Validation errors.', $messages);
                }

                $video_data = VideoContent::where('title', '=', $request->get('title'))->first();
               
                if ($video_data) {
                    return APIResponse('201', 'Data already present in the database.');
                }

                $photoURL = '';
                if ($request->hasFile('thumbnail_image')) {
                    $file = $request->file('thumbnail_image');
                    $folderpath = 'bcci/videos/';
                    $result = uploadFileToS3($file, $folderpath, '');
                    $photoURL = $result['ObjectURL'] ?? '';
                }
            
                $videoUrl = '';
                if ($request->hasFile('video_url')) {
                    $file = $request->file('video_url');
                    $folderpath = 'Input/';
                    $result = uploadFileToS3($file, $folderpath, 'video');
                    $videoUrl = $result['ObjectURL'] ?? '';
                }
            
                $videoCount = AssetsCount::all();
                if (isset($videoCount)) {
                    $videosCount = $videoCount[0]['video_count'] + 1;
                }

                $videoContent = new VideoContent();
                $videoContent->ID = $videosCount;
                $videoContent->title = $request->get('title');
                $videoContent->short_description = $request->get('short_description'); 
                $videoContent->description = $request->get('description');
                $videoContent->video_duration = $request->get('video_duration');  // duration and video-duration is same 
                $videoContent->match_id = $request->get('match_id');
                $videoContent->content_type = $request->get('content_type'); //type and content-type is same
                $videoContent->video_scope = $request->get('video_scope');
                $videoContent->video_url = $videoUrl; 
                $videoContent->match_formats = $request->get('match_formats');
                $videoContent->keywords = $request->get('keywords');   
                $videoContent->created_date = $request->get('created_date');
                $videoContent->publish_date = $request->get('publish_date');
                $videoContent->publish_by = $request->get('publish_by'); // publishFrom and publish by is same
                $videoContent->meta_languages = $request->get('meta_languages');
                $videoContent->langauge = $request->get('langauge');
                $videoContent->asset_type = $request->get('asset_type');
                $videoContent->expiry_date = $request->get('expiry_date'); 
                $videoContent->total_viewcount = $request->get('total_viewcount'); 
                $videoContent->titleslug = $request->get('titleslug');
                $videoContent->varients = $request->get('varients'); 
                $videoContent->views_count = $request->get('views_count'); 
                $videoContent->comments = $request->get('comments'); //comments and commentson is same
                $videoContent->platform = $request->get('platform'); 
                $videoContent->current_status = $request->get('current_status');
                $videoContent->lastModified = $request->get('lastModified');
                $videoContent->thumbnail_image= $photoURL; // thumbnail image nad thumbnail is same
                $videoContent->video_status = 'pending';
                $videoContent->location = $request->get('location');
                $videoContent->titleUrlSegment = $request->get('titleUrlSegment');
                $videoContent->subtitle = $request->get('subtitle');
                $videoContent->titleTranslations = $request->get('titleTranslations');
                $videoContent->coordinates = $request->get('coordinates');
                $videoContent->lastModified = $request->get('lastModified');
                $videoContent->publishTo = $request->get('publishTo');
                $videoContent->mediaId = $request->get('mediaId');
                $videoContent->references = $request->get('references');
                $videoContent->closedCaptioned = $request->get('closedCaptioned');
                $videoContent->status = true;
                $videoContent->save();

                $totalCount = AssetsCount::where('ID', 1)->update(['video_count' => $videosCount]);
                if ($videoContent) {
                    $response = APIResponse('200', 'Data has been added successfully.');
                } else {
                   $response = APIResponse('201', 'Something went wrong, please try again.'); 
                }
                    
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }  
        }  catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Display the list of all the franchises.
     */
    public function listVideo()
    {
        try {
            $videoList = VideoContent::where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

            if ($videoList->count() > 0) {
                $response = APIResponse('200', 'Success', $videoList);
            } else {
                $response = APIResponse('201', 'No videos found.');
            }          
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        return $response;
    }

    /**
     * Delete videos from index.
     */
    public function bulkDeleteVideo(Request $request)
    {
        try {
            $validateData = Validator::make($request->all(),[
                'video_ids.*' => 'required'
            ]);
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
            $videoIds = $request->get('video_ids');

            foreach ($videoIds as $id) {
                $deleted= VideoContent::where('ID','=', (int) $id)->update(['status' => false]);
            }

            if ($deleted) {
                $response = APIResponse('200', 'Video has been deleted successfully.');
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        return $response;
    }

     /**
     * Search data by title.
     */
    public function searchByTitle($title) 
    {
        try {
            $data = VideoContent::where('title','LIKE','%'.$title.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No data found.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                 
        return $response;
    }

    /**
     * Filter by language.
     */
    public function filterByLanguage($langauge) 
    {
        try {
            $data = VideoContent::where('langauge','LIKE','%'.$langauge.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No data found.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }       
        return $response;
    }

    /**
     * Filter by status.
     */
    public function filterByStatus($status) 
    {
        try {
            $data = VideoContent::where('current_status','LIKE','%'.$status.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No data found.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Delete video content by Id.
     */
    public function deleteVideo($id)
    {
        try {
            $udpated=VideoContent::where('ID',(int) $id)->update(['status' => false]);

            if ($udpated) {
                $response = APIResponse('200', 'Video has been deleted successfully.');
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        return $response;
    }

    /**
     * View video content by Id.
     */
    public function viewVideoById($id)
    {
        try {
          $video=VideoContent::where('ID',(int) $id)->first();

            if (!empty($video)) {
                $response = APIResponse('200', 'Success', $video);
            } else {
                $response = APIResponse('201', 'No video found.');
            }  
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }

    /**
     * Update video content by Id.
     */
    public function updateVideo(Request $request, $id)
    {
        try {
            $video = VideoContent::where('ID', '=' ,(int) $id)->first();
            if (!$video) {
                return APIResponse('201', 'No videos found.');
            }

            $validateData = Validator::make($request->all(),[
                'title' => 'required',
                'asset_type' => 'required',
                'slug' => 'required',
                'short_description' => 'required|max:520',
                'description' => 'required',
                'content_type' => 'required'
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }

            $photoURL = '';
            if ($request->hasFile('thumbnail_image')) {
                $file = $request->file('thumbnail_image');
                $folderpath = 'bcci/videos/';
                $result = uploadFileToS3($file, $folderpath, '');
                $photoURL = $result['ObjectURL'] ?? '';
            }

            $videoUrl = '';
            if ($request->hasFile('video_url')) {
                $file = $request->file('video_url');
                $folderpath = 'Input/';
                $result = uploadFileToS3($file, $folderpath, 'video');
                $videoUrl = $result['ObjectURL'] ?? '';
            }

            $dataArr = [
                'title' => $request->get('title'),
                'short_description' => $request->get('short_description'), 
                'description' => $request->get('description'),
                'match_id' => $request->get('match_id'),      
                'video_duration' => $request->get('video_duration'),  
                'content_type' => $request->get('content_type'), 
                'video_scope' => $request->get('video_scope'),
                'video_url' => $videoUrl,
                'match_formats' => $request->get('match_formats'),
                'keywords' => $request->get('keywords'),
                'published_by' => $request->get('published_by'),
                'publish_date' => $request->get('publish_date'),  
                'meta_languages' => $request->get('meta_languages'), 
                'langauge' => $request->get('langauge'),
                'asset_type' => $request->get('asset_type'),
                'expiry_date' => $request->get('expiry_date'),
                'total_viewcount' => $request->get('total_viewcount'),
                'titleslug' => $request->get('titleslug'),  
                'varients' => $request->get('varients'), 
                'views_count' => $request->get('views_count'), 
                'comments' => $request->get('comments'),
                'platform' => $request->get('platform'),
                'thumbnail_image' => $photoURL,
                'current_status'=> $request->get('current_status'),
                'lastModified'=> $request->get('lastModified'),
                'video_status'=> $request->get('video_status'),
                'location'=> $request->get('location'),
                'titleUrlSegment'=> $request->get('titleUrlSegment'),
                'subtitle'=> $request->get('subtitle'),
                'titleTranslations'=> $request->get('titleTranslations'),
                'coordinates'=> $request->get('coordinates'),
                'lastModified'=> $request->get('lastModified'),
                'publishTo'=> $request->get('publishTo'),
                'mediaId'=> $request->get('mediaId'),
                'references'=> $request->get('references'),
                'closedCaptioned'=> $request->get('closedCaptioned'),
                'status'=> true
            ];

            $updated = VideoContent::where('ID', (int) $id)->update($dataArr);

            if ($updated) {
                $response = APIResponse('200', 'Video has been updated successfully.');
            }
            else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        return $response;
    }

    /**
     * List videos with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listVideoByTag(Request $request) {

        try {
            $query = VideoContent::where('status', true);

            if ($request->get('title') != '') {
                $query->where('title', 'like', '%'.$request->get('title').'%');
            }

            if (is_array($request->get('language')) && count(array_filter($request->get('language')))>0) {
                $query->whereIn('language', $request->get('language'));
            }

            if (is_array($request->get('current_status')) && count(array_filter($request->get('current_status')))>0) {
                $query->whereIn('current_status', $request->get('current_status'));
            }

            if ($request->get('max_items') != '') {
                $query->limit((int) $request->get('max_items'));
            }

            if ($request->get('content_from') != '') {
                $contentFrom = $request->get('content_from');
                switch ($contentFrom) {
                    case 'The last year':
                        $query->where('publish_date', '>=', date('Y', strtotime('-1 year')));
                        break;
                    
                    case 'Last 2 years':
                        $query->where('publish_date', '>=', date('Y', strtotime('-2 year')));
                        break;

                    case 'Last 3 years':
                        $query->where('publish_date', '>=', date('Y', strtotime('-3 year')));
                        break;
                }
            }

            if ($request->get('sort_by') != '' && $request->get('order') != '') {
                $sortBy = $request->get('sort_by');
                $order = $request->get('order');
                switch ($sortBy) {
                    case 'Last updated':
                        $query->orderBy('updated_at', $order);
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

            $videos = $query->get();

            if (!empty($videos)) {
                $response = APIResponse('200', 'Success', $videos);
            } else {
                $response = APIResponse('201', 'No videos found.');
            }

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }
}