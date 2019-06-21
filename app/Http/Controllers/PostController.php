<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Image;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\File;
use Auth;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
       $posts = Post::all();
        return response()->json([
            'posts' => $posts,
            'user'  => $user
        ]);

    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $image = $request->file('image');
        $name = 'post_' . time() . '.' . $image->getClientOriginalExtension();
        $path = public_path('/images');
        $image->move($path, $name);
        $post = new Post();
        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->image = $name;
        $post->user_id = $user->id;

        $post->save();


//        $multiImages = $request->multi_images;
//
//        foreach($multiImages as $image){
//
//            $name = 'post_image' . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
//            $path = public_path('/images');
//            $image->move($path, $name);
//
//            $postImage = new Image();
//            $postImage->title = $name;
//            $postImage->post_id = $post->id;
//
//            $post->save();
//
//        }


        return response()->json('Successfully added.');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);

        if($request->hasFile('image')){

            File::delete(public_path('images/' . $post->image));
            $image = $request->file('image');
            $name = 'post_' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('/images');
            $image->move($path, $name);
            $post->image = $name;
        }

        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->save();

        return response()->json('Successfully updated.');
    }

    public function show($id)
    {
        $post = Post::find($id);
        return response()->json($post);
        //
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        File::delete(public_path('images/' . $post->image));
        $post->delete();
        return response()->json('success');
    }
}
