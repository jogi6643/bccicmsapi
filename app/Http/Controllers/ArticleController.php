<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\AssetsCount;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            $data = Article::where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($data))
                $response = APIResponse('200', 'Success', $data);
            else 
                $response = APIResponse('201', 'No articles found.');

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
            $validateData = Validator::make($request->all(),[
                'description' => 'required',
                'title' => 'required',
                'short_description' => 'required',
                'content' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $photoURL = '';
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $folderpath = 'bcci/articles/';
                $result = uploadFileToS3($file, $folderpath);
                $photoURL = $result['ObjectURL'] ?? '';
            }
    
            $articleCount = AssetsCount::all();
            $articleCount = $articleCount[0]['article_count'] + 1;
    
            $article = new Article();
            $article->ID = $articleCount;
            $article->title = $request->get('title');
            $article->short_description = $request->get('short_description'); 
            $article->description = $request->get('description');
            $article->subtitle = $request->get('subtitle');   
            $article->article_owner = $request->get('article_owner');
            $article->content = $request->get('content'); 
            $article->video_duration = $request->get('video_duration');
            $article->match_Id = $request->get('match_Id'); 
            $article->content_type = $request->get('content_type');
            $article->image_url = $photoURL;   
            $article->author = $request->get('author');
            $article->keywords = $request->get('keywords');
            $article->leadMedia = $request->get('leadMedia');
            $article->additionalInfo = $request->get('additionalInfo');
            $article->match_formats = $request->get('match_formats');
            $article->created_date = date("Y-m-d H:i:s");
            $article->published_by = $request->get('published_by');
            $article->publish_date = $request->get('publish_date'); 
            $article->language = $request->get('language'); 
            $article->location = $request->get('location'); 
            $article->references = $request->get('references'); 
            $article->commentsOn = $request->get('commentsOn'); 
            $article->expiryDate = $request->get('expiryDate'); 
            $article->total_viewcount = $request->get('total_viewcount');
            $article->slug = $request->get('slug'); 
            $article->platform = $request->get('platform');
            $article->meta_languages = $request->get('meta_languages');
            $article->current_status = $request->get('current_status');
            $article->status = true;
            $article->save();
    
            $totalCount = AssetsCount::where('ID', 1)->update(['article_count' => $articleCount]);
    
            if ($article)
    
                $response = APIResponse('200', 'Article has been added successfully.');
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

            $article = Article::where('ID', (int) $id)->orderBy('ID', 'DESC')->first();

            if (!empty($article))
                $response = APIResponse('200', 'Success', $article);
            else 
                $response = APIResponse('201', 'No article found.');

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

            $article = Article::where('ID', '=',  (int)$id)->first();

            if (!$article)
                return APIResponse('201', 'No article found.');

            $validateData = Validator::make($request->all(),[
                'description' => 'required',
                'title' => 'required',
                'short_description' => 'required',
                'content' => 'required'
            ]);

            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }

            $photoURL = '';
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $folderpath = 'bcci/articles/';
                $result = uploadFileToS3($file, $folderpath);
                $photoURL = $result['ObjectURL'] ?? '';
            }

            $dataArr = [
                'title' => $request->get('title'),
                'short_description' => $request->get('short_description'),
                'description' => $request->get('description'),
                'subtitle' => $request->get('subtitle'),
                'article_owner' => $request->get('article_owner'),
                'content' => $request->get('content'),
                'video_duration' => $request->get('video_duration'),
                'match_Id' => $request->get('match_Id'),
                'content_type' => $request->get('content_type'),
                'author' => $request->get('author'),
                'keywords' => $request->get('keywords'),
                'leadMedia' => $request->get('leadMedia'),
                'additionalInfo' => $request->get('additionalInfo'),
                'match_formats' => $request->get('match_formats'),
                'published_by' => $request->get('published_by'),
                'publish_date' => $request->get('publish_date'),
                'language' => $request->get('language'),
                'location' => $request->get('location'),
                'references' => $request->get('references'),
                'commentsOn' => $request->get('commentsOn'),    
                'expiryDate' => $request->get('expiryDate'),
                'total_viewcount' => $request->get('total_viewcount'),
                'slug' => $request->get('slug'),
                'summary' => $request->get('summary'),
                'platform' => $request->get('platform'),
                'image_url' => $photoURL,
                'meta_languages' => null,
                'current_status' => $request->get('current_status'),
                'updated_date' => date("Y-m-d H:i:s"),
                'status' => true
            ];

            $updated = Article::where('ID', '=', (int) $id)->update($dataArr);

            if ($updated)
                $response = APIResponse('200', 'Article has been updated successfully.');
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

            $udpated = Article::where('ID', (int) $id)->update(['status' => false]);

            if ($udpated)
                $response = APIResponse('200', 'Article has been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
        
        return $response;
    }

    /**
     * List articles with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listArticles(Request $request)
    {
        try {

            $query = Article::where('status', true);

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

            $articles = $query->get();

            if (!empty($articles))
                $response = APIResponse('200', 'Success', $articles);
            else
                $response = APIResponse('201', 'No article found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }
                  
        return $response;
    }

    /**
     * Filter Articles by title.
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
    
            $data = Article::where('title','LIKE','%'.$search_term.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($data))
                $response = APIResponse('200', 'Success', $data);
            else 
                $response = APIResponse('201', 'No articles found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Filter Articles by language.
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
    
            $data = Article::where('language','LIKE','%'.$language.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($data))
                $response = APIResponse('200', 'Success', $data);
            else 
                $response = APIResponse('201', 'No articles found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Filter Articles by status.
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
    
            $data = Article::where('currentstatus','LIKE','%'.$status.'%')->where('status', '=', true)->orderBy('ID', 'DESC')->get();
    
            if (!empty($data))
                $response = APIResponse('200', 'Success', $data);
            else 
                $response = APIResponse('201', 'No articles found.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }

    /**
     * Bulk Delete Articles
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDeleteArticles(Request $request)
    {
        try {

            $validateData = Validator::make($request->all(),[
                'article_ids.*' => 'required'
            ]);
    
            if ($validateData->fails()) {
                $messages = $validateData->errors()->all();
                return APIResponse('201', 'Validation errors.', $messages);
            }
    
            $articleIds = $request->get('article_ids');
            $articleIds = array_map('intval', $articleIds);
    
            $udpated = Article::whereIn('ID', $articleIds)->update(['status' => false]);
    
            if ($udpated)
                $response = APIResponse('200', 'Articles have been deleted successfully.');
            else
                $response = APIResponse('201', 'Something went wrong, please try again.');

        } catch (\Throwable $e) {
            $response = APIResponse('201', $e->getMessage());
        }

        return $response;
    }
}
