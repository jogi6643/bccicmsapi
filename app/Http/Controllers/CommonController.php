<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\VideoContent;
use App\Models\Image;

class CommonController extends Controller
{
    /**
     * Search data inside Article, Videos & Images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchData(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'search_term' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $type = $request->get('type');
            $searchTerm = $request->get('search_term');
    
            if ($type != '') {
                switch ($type) {
                    case 'articles':
                        $data = Article::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
                        break;
    
                    case 'videos':
                        $data = VideoContent::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
                        break;
                    
                    case 'images':
                        $data = Image::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->paginate(10);
                        break;
                }
            } else {
                $articles = Article::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
                $videos = VideoContent::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
                $images = Image::where('title','LIKE','%'.$searchTerm.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
                $data = $articles->merge($videos)->merge($images)->paginate(10);
            }
    
            if (!empty($data))
                $response = APIResponse('200', 'Success', $data);
            else 
                $response = APIResponse('201', 'No results found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }
}
