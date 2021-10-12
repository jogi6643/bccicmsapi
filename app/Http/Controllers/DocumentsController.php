<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Document;
use App\Models\AssetsCount;
use DB;
class DocumentsController extends Controller
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
        $docsList = Document::where('status', '=', "true")->orderBy('ID', 'DESC')->paginate(10);
       
        if (!empty($docsList)) {
            $response = APIResponse('200', 'Success', $docsList);
        } else {
            $response = APIResponse('201', 'No Documents found.');
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
                'title'=>'required',
                'summary'=>'required',
                'metadata'=>'required', 
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
            DB::beginTransaction();
            try {
                $doc_data = Document::where('title', '=', $request->get('title'))->first();
               
                if ($doc_data) {
                    return APIResponse('201', 'Title already present in the database.');
                }

            $doc_url = '';
            if ($request->hasFile('doc_url')) {
                $file = $request->file('doc_url');
                $folderpath = 'bcci/documents/';
                $result = uploadFileToS3($file,$folderpath);
                $doc_url = $result['ObjectURL'] ?? '';
            }

            $documentsCount = AssetsCount::all();
            if (isset($documentsCount)) {
            $documentsCount = $documentsCount[0]['document_count'] + 1;
            }
            $create_date                            =           date("Y-m-d H:i:s", strtotime($request->get('created_date')))??"";
            $publish_date                           =           date("Y-m-d H:i:s", strtotime($request->get('published_date')))??"";

            $documentContent                        =           new Document();
            $documentContent->ID                    =           $documentsCount;
            $documentContent->title                 =           $request->get('title');
            $documentContent->metadata              =           $request->get('metadata');
            $documentContent->summary               =           $request->get('summary');
            $documentContent->url_segment           =           $request->get('url_segment'); 
            $documentContent->doc_url               =           $doc_url; 
            $documentContent->language              =           $request->get('language');
            $documentContent->status                =           $request->get('status');      
            $documentContent->short_description     =           $request->get('short_description'); 
            $documentContent->description           =           $request->get('description');
            $documentContent->display_date          =           $request->get('display_date');  
            $documentContent->published_date        =           $publish_date; 
            $documentContent->match_formats         =           $request->get('match_formats');
            $documentContent->created_date          =           $create_date;
            $documentContent->published_by          =           $request->get('published_by');
            $documentContent->meta_languages        =           $request->get('meta_languages'); 
            $documentContent->assest_type           =           $request->get('assest_type');
            $documentContent->expiry_date           =           $request->get('expiry_date');
            $documentContent->content_type          =           $request->get('content_type');
            $documentContent->last_updated          =           $request->get('last_updated');
            $documentContent->updated_date          =           now();
            $documentContent->keywords              =           $request->get('keywords');  
            $documentContent->read_time             =           $request->get('read_time');
            $documentContent->language              =           $request->get('language');
            $documentContent->location              =           $request->get('location');
            $documentContent->location_label        =           $request->get('location_label');
            $documentContent->location_search       =           $request->get('location_search');
            $documentContent->latitude              =           $request->get('latitude');
            $documentContent->longitude             =           $request->get('longitude');
            $documentContent->currentstatus         =           $request->get('currentstatus');
            $documentContent->save();
            $documentsCount = AssetsCount::where('ID', 1)->update(['document_count' => $documentsCount]);
            if ($documentContent) {
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
     * Upadate existing record with id .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        
            $validateData= Validator::make($request->all(),[
                'title'=>'required',
                'summary'=>'required',
                'metadata'=>'required',
               ]);
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
        DB::beginTransaction();
        try {
            $doc = Document::where('ID', (int)$request->doc_id)->first();
        
        if (!$doc)
            return APIResponse('201', 'Documents Not found found.');

            $create_date  = date("Y-m-d H:i:s", strtotime($request->created_date))??"";
            $publish_date  = date("Y-m-d H:i:s", strtotime($request->published_date))??"";

            $doc_check = $request->hasFile('doc_url');

            if($doc_check===true)
                 {
                     if ($request->hasFile('doc_url')) {
                         $file = $request->file('doc_url');
                         $folderpath = 'bcci/documents/';
                         $result = uploadFileToS3($file,$folderpath);
                         $doc_url = $result['ObjectURL'] ?? '';
                     }
                 } 

        $doc_Update = Document::where('ID',(int)$request->doc_id)
        ->update([
            "title"                     => $request->title,
            "metadata"                  => $request->metadata,
            "url_segment"               => $request->url_segment,
            "language"                  => $request->language,
            "status"                    => $request->status,
            "short_description"         => $request->short_description,
            "description"               => $request->description,
            "display_date"              => $request->display_date,
            "published_date"            =>  $publish_date,
            "published_by"              => $request->published_by,
            "meta_languages"            => $request->meta_languages,
            "created_date"              => $create_date,
            "assest_type"               => $request->assest_type,
            "expiry_date"               => $request->expiry_date,
            "content_type"              => $request->content_type,
            "last_updated"              => $request->last_updated,
            "updated_date"              => $request->updated_date,
            "keywords"                  => $request->keywords,
            "read_time"                 => $request->keywords,
            "location"                  => $request->location,
            "location_label"            => $request->location_label,
            "location_search"           => $request->location_search,
            "latitude"                  => $request->latitude,
            "longitude"                 => $request->longitude,
            "currentstatus"             => $request->currentstatus
        ]);
        if($doc_check===true)
        {
          
            Document::where('ID',(int)$request->doc_id)
            ->update([
               'doc_url'                 => $doc_url,
              
            ]);
        }
        if ($doc_Update)
            $response = APIResponse('200', 'documents has been updated successfully.');
        else
            $response = APIResponse('201', 'Something went wrong, please try again.');

        return $response;
    }
        catch(\Exception $e) {
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
        $list = Document::where('ID',(int)$id)->where('status', 'true')->first();

        if (!empty($list)) {
            $response = APIResponse('200', 'Success', $list);
        } else {
            $response = APIResponse('201', 'No Documents found of ID '.$id);
        }

        return $response;
    }
        catch(\Exception $e) {
            DB::rollBack();
            $response = APIResponse('201', 'Oops. Something went wrong.');
             return $response;
        }  
    }
// Field Search by Title 
    public function  searchByTitle(Request $request) 
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
        
        $data = Document::where('title', '=' , $title)->where('status', '=', 'true')->orderBy('ID', 'DESC')->paginate(10);

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No Documents found.');
        }          
        return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }  
    }

// Search by status
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
        $data = Document::where('status', '=', $status)->orderBy('ID', 'DESC')->paginate(10);
      
        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No Documents found.');
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

    // public function deleteDocument($id)
    public function destroy($id)
    {
       
        DB::beginTransaction();
        try {
        $checkDoc  =   Document::where('ID', (int)$id)->where('status' , 'true')->count();
        if($checkDoc>0)
        {
            $udpated = Document::where('ID', (int)$id)->update(['status' => false]);
            $response = APIResponse('200', 'Documents has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('200', 'No Documents found of ID '.$id);
        }
        return $response;
    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }
  

// bulk Delete
    
    public function bulkDeleteDocuments(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'doc_ids.*' => 'required'
        ]);

        if ($validateData->fails()) {
            $messages = $validateData->errors()->all();
            return APIResponse('201', 'Validation errors.', $messages);
        }
        DB::beginTransaction();
        try {
        $doc_Ids = $request->get('doc_ids');
        $checkDoc = Document::whereIn('ID', $doc_Ids)->where('status' , 'true')->count();
        if($checkDoc>0)
        {
            $deleted = Document::whereIn('ID', $doc_Ids)->update(['status' => false]);
            $response = APIResponse('200', 'Documents has been deleted successfully.');
        }
        else
        {
            $response = APIResponse('201', 'Documents not found.');
        }

        return $response;
    }
    catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }  
    }

    // Searcch by Language
    public function searchByLanguage(Request $request)
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
        $langauge = $request->get('language');

        $data = Document::where('language', '=' , (string)$langauge)->where('status', '=', "true")->orderBy('ID', 'DESC')->paginate(10);

        if (!empty($data)) {
            $response = APIResponse('200', 'Success', $data);
        } else {
            $response = APIResponse('201', 'No documents found.');
        }          
        return $response;

    } catch(\Exception $e) {
        DB::rollBack();
        $response = APIResponse('201', 'Oops. Something went wrong.');
         return $response;
    }
    }


     // Search by Item
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

       $data = Document::where('status', '<>',false)
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

   public function listDocuments(Request $request)
   {

    $query = Document::where('status', 'true');

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

    $doc = $query->get();
    if (!empty($doc))
        $response = APIResponse('200', 'Success', $doc);
    else
        $response = APIResponse('201', 'No Documents found.');
              
    return $response;
   }
}
