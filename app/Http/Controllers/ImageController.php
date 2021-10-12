<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\AssetsCount;
use DB;
use DateTime;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        DB::beginTransaction();
        try {
        
        $imageList = Image::where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
        
        if (!empty($imageList)) {
            $response = APIResponse('200', 'Success', $imageList);
        } else {
            $response = APIResponse('201', 'No Images found.');
        }          
        return $response;

    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }
  
   // Add Images
    public function store(Request $request)
    {
       
   
   
            $validateData = Validator::make($request->all(),[
                'title' => 'required',
                // 'image_url'=>'required',
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return response()->json(['status'=>['code'=>201,'message' => $messages]]);
            }

            DB::beginTransaction();
            try {
               
            $imageUrl = '';
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $folderpath = 'bcci/images/';
                $result = uploadFileToS3($file,$folderpath);
                $imageUrl = $result['ObjectURL'] ?? '';
            }

            $imageCount = AssetsCount::all();
            if (isset($imageCount)) { 
                $imageCount = $imageCount[0]['image_count'] + 1;
            }
            //convert date format 
            $create_date  = date("Y-m-d H:i:s", strtotime($request->created_date))??"";
            $publish_date  = date("Y-m-d H:i:s", strtotime($request->publish_date))??"";
            $imgContent = new Image();
            $imgContent->ID                        = $imageCount;
            $imgContent->title                     = $request->title;
            $imgContent->description               = $request->description;
            $imgContent->image_url                 = $imageUrl;
            $imgContent->keywords                  =$request->keywords;
            $imgContent->additionalInfo            = $request->additionalInfo;
            $imgContent->subtitle                  = $request->subtitle;
            $imgContent->match_formats             = $request->match_formats;
            $imgContent->created_date              = $create_date;
            $imgContent->published_by              = $request->published_by;
            $imgContent->publish_date              = $publish_date;
            $imgContent->related                   = $request->related;
            $imgContent->references                = $request->references;
            $imgContent->coordinates               = $request->coordinates;
            $imgContent->metadata                  = $request->metadata;
            $imgContent->status                    = $request->status;
            $imgContent->currentstatus             = $request->currentstatus;
            $imgContent->platform                  = $request->platform;
            $imgContent->commentsOn                = $request->commentsOn;
            $imgContent->copyright                 = $request->copyright;
            $imgContent->language                  = $request->language;
            $imgContent->url_segment                  = $request->url_segment;
            $imgContent->image_status = true;
            $imgContent->status = true;
            $imgContent->save();
            $totalCount = AssetsCount::where('ID', 1)->update(['image_count' => $imageCount]);
       
            if ($imgContent) {
                $response = APIResponse('200', 'Data has been added successfully.');
                return $response;
            } else {
               $response = APIResponse('201', 'Something went wrong, please try again.'); 
               return $response;
            }
        } catch(\Exception $e) {
            DB::rollBack();
            $response = APIResponse('201', 'Oops. Something went wrong.');
             return $response;
        }
                    
       
    }

  

 // Upadate Single Record
 public function update(Request $request)
 {
     
    $validateData= Validator::make($request->all(),[
        'title' => 'required',
        // 'image_url'=>'required',
        'image_id'=>'required',
       ]);
    if ($validateData->fails()) {
        $messages = $validateData->errors()->all();
        return response()->json(['status'=>['code'=>201,'message' => $messages]]);
    }
    DB::beginTransaction();
    try {
    
      $imgages = Image::where('ID', (int)$request->image_id)->first();
     
     if (!$imgages)
         return APIResponse('201', 'Images Not found found.');
    
    $img = $request->hasFile('image_url');

    if($img===true)
         {
             if ($request->hasFile('image_url')) {
                 $file = $request->file('image_url');
                 $folderpath = 'bcci/images/';
                 $result = uploadFileToS3($file,$folderpath);
                 $imageUrl = $result['ObjectURL'] ?? '';
             }
         } 
         $create_date  = date("Y-m-d H:i:s", strtotime($request->created_date))??"";
         $publish_date  = date("Y-m-d H:i:s", strtotime($request->publish_date))??"";
     $doc_Update = Image::where('ID',(int)$request->image_id)
     ->update([
        'title'                     => $request->title,
        'description'               => $request->description,
        // 'image_url'                 => $imageUrl,
        'keywords'                  =>$request->keywords,
        'additionalInfo'            => $request->additionalInfo,
        'subtitle'                  => $request->subtitle,
        'match_formats'             => $request->match_formats,
        'created_date'              => $create_date,
        'published_by'              => $request->published_by,
        'publish_date'              => $publish_date,
        'related'                   => $request->related,
        'references'                => $request->references,
        'coordinates'               => $request->coordinates,
        'metadata'                  => $request->metadata,
        'status'                    => $request->status,
        'currentstatus'             => $request->currentstatus,
        'platform'                  => $request->platform,
        'commentsOn'                => $request->commentsOn,
        'copyright'                 => $request->copyright,
        'language'                  => $request->language,
        'updated_date'              => date("Y-m-d H:i:s"),
        'image_status' => true,
        'status' => true,
        'url_segment'              =>$request->url_segment,
     ]);
    if($img===true)
            {
              
                Image::where('ID',(int)$request->image_id)
                ->update([
                   'image_url'                 => $imageUrl,
                  
                ]);
            }
    
     if ($doc_Update)
         $response = APIResponse('200', 'Images has been updated successfully.');
     else
         $response = APIResponse('201', 'Image can not be update.');

     return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        // dd($e->getMessage());
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }

 }



   // Search by title
   public function searchItem(Request $request)
   {
     
       $validateData = Validator::make($request->all(),[
           'search_item' => 'required'
       ]);
      
       if ($validateData->fails()) {
           $messages = $validateData->errors()->all();
           return APIResponse('201', 'Validation errors.', $messages);
       }
       DB::beginTransaction();
       try {
       $data1 = $request->get('search_item');

       $data = Image::where('status', '<>',false)
       ->where(function($query) use ($data1){
        $query->where('title', 'LIKE', '%'.$data1.'%')
        ->orWhere('language', 'LIKE', '%'.$data1.'%')
        ->orWhere('currentstatus', 'LIKE', '%'.$data1.'%');
         })->orderBy('ID', 'DESC')->paginate(10);

       if (!empty($data)) {
           $response = APIResponse('200', 'Success', $data);
       } else {
           $response = APIResponse('201', 'No data found.');
       }          
       return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
   }


   // Search by title
    public function searchByTitle(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'search_title' => 'required'
        ]);
       
        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $title = $request->get('search_title');

        $data = Image::where('title', '=' , $title)->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No data found.');
        }          
        return $response;
    }



     /**
     * Filter Images Search by Language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function searchByLanguage(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'language' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $langauge = $request->get('language');
       
        $data = Image::where('language', '=' , (string)$langauge)->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No data found.');
        }          
        return $response;
    }

  

    /**
     * Filter Images by status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function searchByStatus(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'status' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }

        $status = $request->get('status');

        $data = Image::where('currentstatus', '=' , (string)$status)->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No data found.');
        }          
        return $response;
    }

  

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function  destroy($id)
    {
        DB::beginTransaction();
        try {
        $checkImg  =   Image::where('ID', (int)$id)->where('status' , true)->count();
        if($checkImg>0)
        {
            $udpated = Image::where('ID', (int)$id)->update(['status' => false]);
            $response = APIResponse('200', 'Images has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('200', 'No Images found of ID '.$id);
        }
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
        $images = Image::where('ID',(int)$id)->where('status' , true)->first();

        if (!empty($images)) {
            $response = APIResponse('200', 'Success', $images);
        } else {
            $response = APIResponse('201', 'No Images found of ID '.$id);
        }

        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }
   

        /**
     * Bulk Delete Images
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

  

    public function bulkDeleteImages(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'images_ids.*' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {
        $images_Ids = $request->get('images_ids');
        $checkImg = Image::whereIn('ID',   $images_Ids)->where('status' , true)->count();
        if($checkImg>0)
        {
            $deleted = Image::whereIn('ID', $images_Ids)->update(['status' => false]);
            $response = APIResponse('200', 'Images has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('201', 'Images not found.');
        }
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }

    }



     /**
     * Filter Images Search by Title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function listImage(Request $request)
    {
       
        $query = Image::where('status', true);

        if ($request->get('title') != '')
            $query->where('title', 'like', '%'.$request->get('title').'%');
          
        if (is_array($request->get('language')) && count(array_filter($request->get('language')))>0)
            $query->whereIn('language', $request->get('language'));

        if (is_array($request->get('current_status')) && count(array_filter($request->get('current_status')))>0)
            $query->whereIn('currentstatus',$request->get('current_status'));

        if ($request->get('max_items') != '')
            $query->limit((int) $request->get('max_items'));

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

        if ($request->get('sort_by') != ''  && $request->get('order') != '') {
            $sortBy = $request->get('sort_by');
            $order = $request->get('order');
            switch ($sortBy) {
                case 'Last updated':
                    $query->orderBy('updated_date', $order);
                    break;
                case 'Status':
                    $query->orderBy('currentstatus', $order);
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

        $img = $query->get();
        if (!empty($img))
            $response = APIResponse('200', 'Success', $img);
        else
            $response = APIResponse('201', 'No Image found.');
                  
        return $response;
    }

}
