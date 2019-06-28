<?php

namespace App\Http\Controllers;

use App\Comment;
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
        $user = auth()->user();
        $posts = Post::all();
        return response()->json([
            'posts' => $posts,
            'user' => $user
        ]);

    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,gif,bmp,jpeg,PNG,JPG,JPEG,GIF,BMP|max:2048'
        ]);
        $user = auth()->user();
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


        $multiImages = $request->pics;

        foreach($multiImages as $image){

            $name = 'post_image' . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/images');
            $image->move($path, $name);

            $postImage = new Image();
            $postImage->title = $name;
            $postImage->post_id = $post->id;

            $postImage->save();

        }


        return response()->json('Successfully added.');
    }

    public function edit($id)
    {

        $comments = [];
        $commentsData = Comment::where('post_id', $id)->with('user')->get();
        $post = Post::with('user')->find($id);
        $images = Image::where('post_id', $id)->get();
        $user = auth()->user();

        if($user->is_admin == 1){
            foreach ($commentsData as $comment) {

                $comments [] = [
                    'id' => $comment->id,
                    'author' => $comment->user->name,
                    'content' => $comment->text
                ];
            }

            return response()->json([
                'post' => $post,
                'comments' => $comments,
                'user' => $user,
                'images' => $images
            ]);
        }else{

            if( $post->user_id == $user->id){

                foreach ($commentsData as $comment) {

                    $comments [] = [
                        'id' => $comment->id,
                        'author' => $comment->user->name,
                        'content' => $comment->text
                    ];
                }
                return response()->json([
                    'post' => $post,
                    'comments' => $comments,
                    'user' => $user,
                    'images' => $images
                ]);
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Permission denied.'
                ]);
            }

        }


    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);
        $user = auth()->user();

        if($user->is_admin == 1){

            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => 'image|mimes:jpg,png,gif,bmp,jpeg,PNG,JPG,JPEG,GIF,BMP|max:2048'
            ]);
            if ($request->hasFile('image')) {

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


            $multiImages = $request->pics;

            foreach($multiImages as $image){

                $name = 'post_image' . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
                $path = public_path('/images');
                $image->move($path, $name);

                $postImage = new Image();
                $postImage->title = $name;
                $postImage->post_id = $post->id;

                $postImage->save();

            }

            return response()->json('Successfully updated.');
        }else{

            if( $post->user_id == $user->id){
                $request->validate([
                    'title' => 'required',
                    'description' => 'required',
                    'image' => 'image|mimes:jpg,png,gif,bmp,jpeg,PNG,JPG,JPEG,GIF,BMP|max:2048'
                ]);
                if ($request->hasFile('image')) {

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


                $multiImages = $request->pics;

                foreach($multiImages as $image){

                    $name = 'post_image' . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/images');
                    $image->move($path, $name);

                    $postImage = new Image();
                    $postImage->title = $name;
                    $postImage->post_id = $post->id;

                    $postImage->save();

                }

                return response()->json('Successfully updated.');
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Permission denied.'
                ]);
            }

        }

    }

    public function show($id)
    {
        $comments = [];
        $commentsData = Comment::where('post_id', $id)->with('user')->get();
        $post = Post::with('user')->find($id);
        $images = Image::where('post_id', $id)->get();
        $user = auth()->user();

            foreach ($commentsData as $comment) {

                $comments [] = [
                    'id' => $comment->id,
                    'author' => $comment->user->name,
                    'content' => $comment->text
                ];
            }

            return response()->json([
                'post' => $post,
                'comments' => $comments,
                'user' => $user,
                'images' => $images
            ]);

    }

    public function comment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;
        $comment->text = $request->comment;
        $comment->save();

        $comment = [
            'id' => $comment->id,
            'author' => $comment->user->name,
            'content' => $comment->text
        ];

        return response()->json($comment);
    }

    public function deleteImage($id)
    {
        $user = auth()->user();
        $image = Image::find($id);

        if($user->is_admin == 1){
            File::delete(public_path('images/' . $image->title));
            $image->delete();
            return response()->json('Successfully deleted');
        }else{
            if( $image->post->user_id == $user->id){
                File::delete(public_path('images/' . $image->title));
                $image->delete();
                return response()->json('Successfully deleted');
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Permission denied.'
                ]);
            }

        }

    }

    public function deleteComment($id)
    {
        $user = auth()->user();
        $comment = Comment::find($id);

        if($user->is_admin == 1){
            $comment->delete();
            return response()->json('Successfully deleted');
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Permission denied.'
            ]);
        }

    }

    public function destroy($id)
    {
        $user = auth()->user();
        $post = Post::find($id);

        if($user->is_admin == 1){

            File::delete(public_path('images/' . $post->image));
            $post->delete();
            return response()->json('Successfully deleted');
        }else{

            if( $post->user_id == $user->id){

                File::delete(public_path('images/' . $post->image));
                $post->delete();
                return response()->json('Successfully deleted');
            }else{

                return response()->json([
                    'status_code' => 400,
                    'message' => 'Permission denied.'
                ]);
            }
        }


    }
}
