<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts;

        $response = APIResponse('200', 'Data has been added successfully.', $posts);
        return $response;
    }
 
    public function show($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            $response = APIResponse('201', 'Post not found');
            return $response;
        }

        $response = APIResponse('200', 'Success', $post->toArray());
        return $response;
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);
 
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
 
        if (auth()->user()->posts()->save($post))
            $response = APIResponse('200', 'Success', $post->toArray());
        else
            $response = APIResponse('201', 'Post not added.');
    
        return $response;
    }
 
    public function update(Request $request, $id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            $response = APIResponse('201', 'Post not found');
            return $response;
        }
 
        $updated = $post->fill($request->all())->save();
 
        if ($updated)
            $response = APIResponse('200', 'Success');
        else
            $response = APIResponse('201', 'Post can not be updated');

        return $response;
    }
 
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            $response = APIResponse('201', 'Post not found');
            return $response;
        }
 
        if ($post->delete()) {
            $response = APIResponse('200', 'Success');
        } else {
            $response = APIResponse('201', 'Post can not be deleted');
        }

        return $response;
    }
}