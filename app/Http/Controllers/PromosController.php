<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetsCount;
use Illuminate\Support\Facades\Validator;
use App\Models\PromosContent;
use DB;
class PromosController extends Controller
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
        $promosList = PromosContent::where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
       
        if (!empty($promosList)) {
            $response = APIResponse('200', 'Success', $promosList);
        } else {
            $response = APIResponse('201', 'No Promos found.');
        }          
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $validateData = Validator::make($request->all(),[
                'title' => 'required',
                'display_date'=>'required',
                'metadata'=>'required',
                'photo_editor'=>'required',
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }

            DB::beginTransaction();
            try {
            $promo_data = PromosContent::where('title', '=', $request->get('title'))->first();
           
            if ($promo_data) {
                return APIResponse('201', 'Title already present in the database.');
            }

            $promosCount = AssetsCount::all();
            if (isset($promosCount)) {
            $promosCount = $promosCount[0]['promos_count'] + 1;
            }
            $create_date  = date("Y-m-d H:i:s", strtotime($request->get('created_date')))??"";
            $publish_date  = date("Y-m-d H:i:s", strtotime($request->get('publish_date')))??"";
            $expiry_date  = date("Y-m-d H:i:s", strtotime($request->get('expiry_date')))??"";
            $promo_url = '';
            if ($request->hasFile('promo_url')) {
                $file = $request->file('promo_url');
                $folderpath = 'bcci/promos/';
                $result = uploadFileToS3($file,$folderpath);
                $promo_url = $result['ObjectURL'] ?? '';
            }

            $promosContent = new PromosContent();
            $promosContent->ID              =   $promosCount;
            $promosContent->title           = $request->get('title');
            $promosContent->display_date    = $request->get('display_date'); 
            $promosContent->metadata        = $request->get('metadata'); 
            $promosContent->photo_editor    = $request->get('photo_editor');
            $promosContent->short_description = $request->get('short_description'); 
            $promosContent->description     = $request->get('description');
            $promosContent->promo_duration  = $request->get('promo_duration');   
            $promosContent->match_id        = $request->get('match_id');
            $promosContent->content_type    = $request->get('content_type'); 
            $promosContent->promo_scope     = $request->get('promo_scope');
            $promosContent->promo_url       =  $promo_url; 
            $promosContent->match_formats   = $request->get('match_formats');
            $promosContent->keywords        = $request->get('keywords');   
            $promosContent->created_date    =  $create_date;
            $promosContent->publish_date    = $publish_date;
            $promosContent->publish_by      = $request->get('publish_by');
            $promosContent->meta_languages  = $request->get('meta_languages');
            $promosContent->langauge        = $request->get('langauge');
            $promosContent->asset_type      = $request->get('asset_type');
            $promosContent->expiry_date     = $expiry_date; 
            $promosContent->total_viewcount = $request->get('total_viewcount'); 
            $promosContent->titleslug       = $request->get('titleslug'); 
            $promosContent->thumbnail       = $request->get('thumbnail'); 
            $promosContent->varients        = $request->get('varients'); 
            $promosContent->views_count     = $request->get('views_count'); 
            $promosContent->comments        = $request->get('comments');
            $promosContent->platform        = $request->get('platform'); 
            $promosContent->language       = $request->get('language');
            $promosContent->location       = $request->get('location');
            $promosContent->location_label = $request->get('location_label');
            $promosContent->location_search= $request->get('location_search');
            $promosContent->latitude       = $request->get('latitude');
            $promosContent->longitude      = $request->get('longitude'); 
            $promosContent->current_status  = $request->get('current_status');
            $promosContent->status = true;
            $promosContent->save();

            $totalCount = AssetsCount::where('ID', 1)->update(['promos_count' => $promosCount]);
            if ($promosContent) {
                $response = APIResponse('200', 'Data has been added successfully.');
            } else {
               $response = APIResponse('201', 'Something went wrong, please try again.'); 
            }
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        DB::beginTransaction();
        try {
        $list = PromosContent::where('ID',(int)$id)->where('status', true)->first();

        if (!empty($list)) {
            $response = APIResponse('200', 'Success', $list);
        } else {
            $response = APIResponse('201', 'No Promos found of ID '.$id);
        }

        return $response;
    }
        catch(\Exception $e) {
            DB::rollBack();
            $response = APIResponse('201', 'Oops. Something went wrong.');
             return $response;
        } 
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
    public function update(Request $request)
    {
        

        $validateData = Validator::make($request->all(),[
            'title' => 'required',
            'display_date'=>'required',
            'metadata'=>'required',
            'photo_editor'=>'required',
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {
        $promo = PromosContent::where('ID', '=' ,(int) $request->promo_id)->first();
        
        if (!$promo) {
            return APIResponse('201', 'No Promos found.');
        }
        $create_date  = date("Y-m-d H:i:s", strtotime($request->get('created_date')))??"";
        $publish_date  = date("Y-m-d H:i:s", strtotime($request->get('publish_date')))??"";
        $expiry_date  = date("Y-m-d H:i:s", strtotime($request->get('expiry_date')))??"";
        $promo_check = $request->hasFile('promo_url');

        if($promo_check===true)
             {
                 if ($request->hasFile('promo_url')) {
                     $file = $request->file('promo_url');
                     $folderpath = 'bcci/images/';
                     $result = uploadFileToS3($file,$folderpath);
                     $promo_url = $result['ObjectURL'] ?? '';
                 }
             } 

        $updated = PromosContent::where('ID', '=' ,(int) $request->promo_id)->update(['title' => $request->get('title'),
                                    'display_date'          => $request->get('display_date'), 
                                    'metadata'              => $request->get('metadata'),
                                    'photo_editor'          => $request->get('photo_editor'),
                                    'short_description'     => $request->get('short_description'),
                                    'description'           => $request->get('description'),
                                    'match_id'              => $request->get('match_id'),
                                    'promo_duration'        => $request->get('promo_duration'),
                                    'content_type'          => $request->get('content_type'),
                                    'promo_scope'           => $request->get('promo_scope'),
                                    'match_formats'         => $request->get('match_formats'),
                                    'keywords'              => $request->get('keywords'),
                                    'created_date'          => $create_date,
                                    'publish_date'          => $publish_date,
                                    'publish_by'            => $request->get('publish_by'),
                                    'meta_languages'        => $request->get('meta_languages'),
                                    'langauge'              => $request->get('langauge'),
                                    'asset_type'            => $request->get('asset_type'),
                                    'expiry_date'           => $expiry_date,
                                    'total_viewcount'       => $request->get('total_viewcount'),
                                    'titleslug'             => $request->get('titleslug'),
                                    'thumbnail'             => $request->get('thumbnail'),    
                                    'varients'              => $request->get('varients'),
                                    'views_count'           => $request->get('views_count'),
                                    'comments'              => $request->get('comments'),
                                    'platform'              => $request->get('platform'),
                                    'language'              => $request->get('language'),
                                    'location'              => $request->get('location'),
                                    'location_label'        => $request->get('location_label'),
                                    'location_search'       => $request->get('location_search'),
                                   'latitude'               => $request->get('latitude'),
                                    'longitude'             => $request->get('longitude'), 
                                    'current_status'        => $request->get('current_status'),
                                    'status'                => true,
                                ]);
                if($promo_check===true)
                {
                    
                    PromosContent::where('ID',(int)$request->promo_id)
                    ->update([
                        'promo_url'                 => $promo_url,
                        
                    ]);
                }
        if ($updated) {
            $response = APIResponse('200', 'Promos has been updated successfully.');
        }
        else {
            $response = APIResponse('201', 'Something went wrong, please try again.');
        }

        return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        DB::beginTransaction();
        try {
        $checkDoc  =   PromosContent::where('ID', (int)$id)->where('status' , true)->count();
        if($checkDoc>0)
        {
            $udpated = PromosContent::where('ID', (int)$id)->update(['status' => false]);
            $response = APIResponse('200', 'Promos has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('200', 'No Promos found of ID '.$id);
        }
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }

    }

      /**
     * Search data by title.
     */
    public function searchByTitle(Request $request) 
    {
        $validateData = Validator::make($request->all(),[
            'search_title' => 'required'
        ]);
       
        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {

            $title = $request->search_title;
           
            $data = PromosContent::where('title', 'LIKE' , '%'.$title.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
    
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No Promos found.');
            }          
            return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }  
    }

      /**
     * Search data by title.
     */
    public function filterByLanguage(Request $request) 
    {
        $validateData = Validator::make($request->all(),[
            'language' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {

            $language = $request->get('language');
           
            $data = PromosContent::where('langauge', 'LIKE' , '%'.$language.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
    
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No Promos found.');
            }          
            return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }  
    }


    public function searchByStatus(Request $request) 
    {
        $validateData = Validator::make($request->all(),[
            'status' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {

            $status = (string)$request->status;
           
            $data = PromosContent::where('current_status','LIKE','%'.$status.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
    
            if (!empty($data)) {
                $response = APIResponse('200', 'Success', $data);
            } else {
                $response = APIResponse('201', 'No Promos found.');
            }          
            return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }  
    }


    public function bulkDeletePromos(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'promo_ids.*' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {
        $promoIds = $request->get('promo_ids');
        
        $checkPro = PromosContent::whereIn('ID', $promoIds)->where('status' , true)->count();
        if($checkPro>0)
        {
            $deleted = PromosContent::whereIn('ID', $promoIds)->update(['status' => false]);
            $response = APIResponse('200', 'promo has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('201', 'promo not found.');
        }
        return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
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

       $data = PromosContent::where('status', '<>',false)
       ->where(function($query) use ($data1){
        $query->where('title', 'LIKE', '%'.$data1.'%')
        ->orWhere('langauge', 'LIKE', '%'.$data1.'%')
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

   public function listPromos(Request $request)
   {


    $query = PromosContent::where('status', true);

    if ($request->get('title') != '')
        $query->where('title', 'like', '%'.$request->get('title').'%');
       
  
    if (is_array($request->get('language')) && count(array_filter($request->get('language')))>0)
        $query->whereIn('langauge', $request->get('language'));

    if (is_array($request->get('current_status')) && count(array_filter($request->get('current_status')))>0)
        $query->whereIn('current_status',$request->get('current_status'));

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

    $doc = $query->get();
    if (!empty($doc))
        $response = APIResponse('200', 'Success', $doc);
    else
        $response = APIResponse('201', 'No Documents found.');
              
    return $response;
   }

}
