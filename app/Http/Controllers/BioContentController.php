<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BioContent;
use App\Models\AssetsCount;
use Illuminate\Support\Facades\Validator;

class BioContentController extends Controller
{
    /**
     * Add Bio in the content management.
     */
    public function addBio(Request $request)
    {
        try {
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

                $imageUrl = '';
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $result = uploadFileToS3($file);
                    $imageUrl = $result['ObjectURL'] ?? '';
                }

                $bioCount = AssetsCount::all();
                if (isset($bioCount)) {
                    $bioCount = $bioCount[0]['bio_count'] + 1;
                }

                $bioContent = new BioContent();
                $bioContent->ID = $bioCount;
                $bioContent->title = $request->get('title');
                $bioContent->slug = $request->get('slug');
                $bioContent->image_url = $imageUrl;
                $bioContent->short_description = $request->get('short_description');
                $bioContent->description = $request->get('description');
                $bioContent->known_name = $request->get('known_name');
                $bioContent->surname = $request->get('surname');
                $bioContent->first_name = $request->get('first_name');
                $bioContent->nationality = $request->get('nationality');
                $bioContent->date_of_birth = $request->get('date_of_birth');
                $bioContent->date_of_death = $request->get('date_of_death');
                $bioContent->langauge = $request->get('langauge');
                $bioContent->place_of_birth = $request->get('place_of_birth');
                $bioContent->summary = $request->get('summary');
                $bioContent->display_date = $request->get('display_date');
                $bioContent->city = $request->get('city');
                $bioContent->position = $request->get('position');
                $bioContent->career_start_date = $request->get('career_start_date');
                $bioContent->career_end_date = $request->get('career_end_date');
                $bioContent->asset_type = $request->get('asset_type');
                $bioContent->publish_date = $request->get('publish_date');
                $bioContent->content_type = $request->get('content_type');
                $bioContent->platform = $request->get('platform');
                $bioContent->current_status = $request->get('current_status');
                $bioContent->status = true;
                
                $bioContent->save();

                $totalCount = AssetsCount::where('ID', 1)->update(['bio_count' => $bioCount]);
                if ($bioContent) {
                    $response = APIResponse('200', 'Data has been added successfully.');
                } else {
                   $response = APIResponse('201', 'Something went wrong, please try again.'); 
                }
                    
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        return $response;
    }

    /**
     * Display the list of all the franchises.
     */
    public function listBio()
    {
        try {
            $bioList = BioContent::where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

            if (!empty($bioList)) {
                $response = APIResponse('200', 'Success', $bioList);
            } else {
                $response = APIResponse('201', 'No bios found.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }    
        return $response;
    }

    /**
     * Delete videos from index.
     */
    public function bulkDeleteBio(Request $request)
    {
        try {
            $validateData = Validator::make($request->all(),[
                'bio_ids.*' => 'required'
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }

            $bioIds = $request->get('bio_ids');

            foreach ($bioIds as $id) {
                $deleted= BioContent::where('ID','=', (int) $id)->update(['status' => false]);
            }

            if ($deleted) {
                $response = APIResponse('200', 'Bio has been deleted successfully.');
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
    public function searchByTitleBio($title) 
    {
        try {
            $data = BioContent::where('title','LIKE','%'.$title.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

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
    public function filterByLanguageBio($langauge) 
    {
        try {
            $data = BioContent::where('langauge','LIKE','%'.$langauge.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

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
    public function filterByStatusBio($status) 
    {
        try {
            $data = BioContent::where('current_status','LIKE','%'.$status.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

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
     * Delete bio content by Id.
     */
    public function deleteBio($id)
    {
        try {
            $udpated= BioContent::where('ID',(int) $id)->update(['status' => false]);

            if ($udpated) {
                $response = APIResponse('200', 'Bio has been deleted successfully.');
            } else {
                $response = APIResponse('201', 'Something went wrong, please try again.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }

    /**
     * View bio content by Id.
     */
    public function viewBioById($id)
    {
        try {
            $video= BioContent::where('ID',(int) $id)->first();

            if (!empty($video)) {
                $response = APIResponse('200', 'Success', $video);
            } else {
                $response = APIResponse('201', 'No bio found.');
            }
        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }

    /**
     * Update video content by Id.
     */
    public function updateBio(Request $request, $id)
    {
        try {
            $video = BioContent::where('ID', '=' ,(int) $id)->first();

            if (!$video) {
                return APIResponse('201', 'No bio found.');
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

            $imageUrl = '';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $folderpath = 'bcci/bios/';
                $result = uploadFileToS3($file, $folderpath);
                $imageUrl = $result['ObjectURL'] ?? '';
            }

            $dataArr = [
                'title' => $request->get('title'),
                'slug' => $request->get('slug'), 
                'description' => $request->get('description'),
                'image_url' => $imageUrl,      
                'short_description' => $request->get('short_description'),  
                'known_name' => $request->get('known_name'), 
                'surname' => $request->get('surname'),
                'first_name' => $request->get('first_name'),
                'nationality' => $request->get('nationality'),
                'date_of_birth' => $request->get('date_of_birth'),
                'langauge' => $request->get('langauge'),
                'date_of_death' => $request->get('date_of_death'),  
                'place_of_birth' => $request->get('place_of_birth'), 
                'summary' => $request->get('summary'),
                'display_date' => $request->get('display_date'),
                'city' => $request->get('city'),
                'position' => $request->get('position'),
                'career_start_date' => $request->get('career_start_date'),  
                'publish_date' => $request->get('publish_date'), 
                'career_end_date' => $request->get('career_end_date'), 
                'asset_type' => $request->get('asset_type'),
                'content_type' => $request->get('content_type'),
                'platform' => $request->get('platform'),
                'current_status' => $request->get('current_status')
            ];

            $updated = BioContent::where('ID', (int) $id)->update($dataArr);

            if ($updated) {
                $response = APIResponse('200', 'Bio has been updated successfully.');
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
     * List bios with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listBioByTag(Request $request) {
        try {
            $query = BioContent::where('status', true);

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
                
            $bios = $query->get();

            if (!empty($bios))
                $response = APIResponse('200', 'Success', $bios);
            else
                $response = APIResponse('201', 'No data found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
              
        return $response;
    }
}