<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
use Illuminate\Support\Facades\Storage;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\User;


class PostsController extends Controller
{
    use EntrustUserTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts=Post::orderBy('title', 'desc')->get();
        //$posts=DB::select('SELECT * FROM posts');
        $posts = Post::orderBy('created_at', 'desc')->paginate(11);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->id());
        if ($user->hasRole('poster')) {
            return view('posts.create');
        }
        else {
            abort(404);
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        //Handle image
        if($request->hasFile('cover_image')) {
            $complete_filename = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($complete_filename, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $filenNameToStore = $filename.'_'.time().'.'.$extension;
            //Image upload
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenNameToStore);
        } else {
            $filenNameToStore = 'noimage.jpg';
        }

        //Create Post
        $post=new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $filenNameToStore;
        $post->save();
        return redirect('/home')->with('success', 'Post Created');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        return view ('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if (auth()->user()->id != $post->user_id) {
            return redirect('/posts')->with('error','Permission Denied');
        }
        return view('posts.edit')->with('post',$post);
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
        $post=Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $complete_filename = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($complete_filename, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $filenNameToStore = $filename.'_'.time().'.'.$extension;
            //Image upload
            $path=$request->file('cover_image')->storeAs('public/cover_images', $filenNameToStore);
            $post->cover_image = $filenNameToStore;
        }
        $post->save();
        return redirect('/posts')->with('success', 'Post Updated');
    }
    /*
    * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!(empty($post))) {
            if (auth()->user()->id != $post->user_id) {
                return redirect('/posts')->with('error', 'Permission Denied');
            }
            if ($post->cover_image != 'noimage.jpg') {
                Storage::delete('public/cover_images/' . $post->cover_image);
            }
            $post->delete();
        }
        return redirect('/home')->with('error', 'Post Removed');
    }
}
