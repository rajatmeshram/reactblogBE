<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
class BlogController extends Controller
{
    public function index(Request $req){
        $blogs =  Blog::orderBy('created_at','DESC');
        if(!empty($req->keyword)){
            $blog =  $blogs->where('title','like','%'.$req->keyword.'%');
        }
        $blog =  $blogs->get();
        return response()->json([
            'status'=>true,
            'data'=>$blog
        ]);
    }
    public function singleBlog($id){
        //$blog = DB::table('blogs')->where('id',$id)->get();
        $blog = Blog::find($id);
        if( $blog == NULL){
            return response()->json([
                'status'=>false,
                'message'=>'No Blog Found'
                
            ]);

        }
        $blog['date'] = \Carbon\Carbon::parse($blog->created_at)->format('d M, Y');
        return response()->json([
            'status'=>true,
            'data'=>$blog
            
        ]);
    }
    public function storeBlog(Request $req){
        $validator = Validator::make($req->all(),[
            'title'=>'required|min:10',
            'author'=>'required|min:3'

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Some Error Occur',
                'errors'=>$validator->errors()
            ]);

        }
        $blog = new Blog();
        $blog->title = $req->title;
        $blog->author = $req->author;
        $blog->description = $req->description;
        $blog->shortDesc = $req->shortDesc;
        $blog->save();
        $tempImage = TempImage::find($req->image);
        if($tempImage != NULL){
            $imageExtarr = explode('.',$tempImage->name);
            $ext = last($imageExtarr);
            $imageName = time().'-'.$blog->Id.'.'.$ext;
            $blog->image = $imageName;
            $blog->save();
            $spath = public_path('uploads/temp/'.$tempImage->name);
            $dpath = public_path('uploads/blogs/'.$imageName);
            File::copy($spath,$dpath);
        }
        return response()->json([
            'status'=>true,
            'message'=>'A New Post Created',
            'data'=>$blog
        ]);
    }
    public function updateBlog($id, Request $req){
        $blog = Blog::find($id);
        if($blog == NULL){
            return response()->json([
                'status'=>false,
                'message'=>'Blog Not Found'
            ]);
        }

        $validator = Validator::make($req->all(),[
            'title'=>'required|min:10',
            'author'=>'required|min:3'

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Some Error Occur',
                'errors'=>$validator->errors()
            ]);

        }
        
        $blog->title = $req->title;
        $blog->author = $req->author;
        $blog->description = $req->description;
        $blog->shortDesc = $req->shortDesc;
        $blog->save();
        $tempImage = TempImage::find($req->image);
        if($tempImage != NULL){
            File::delete(public_path('uploads/blogs/'.$blog->image));
            $imageExtarr = explode('.',$tempImage->name);
            $ext = last($imageExtarr);
            $imageName = time().'-'.$blog->Id.'.'.$ext;
            $blog->image = $imageName;
            $blog->save();
            $spath = public_path('uploads/temp/'.$tempImage->name);
            $dpath = public_path('uploads/blogs/'.$imageName);
            File::copy($spath,$dpath);
        }
        return response()->json([
            'status'=>true,
            'message'=>'Blog updated',
            'data'=>$blog
        ]);
        
    }
    public function destroyBlog($id){
        $blog = Blog::find($id);
        if($blog == NULL){
            return response()->json([
                'status'=>false,
                'message'=>'Blog Not Found'
            ]);
        }
        File::delete(public_path('uploads/blogs/'.$blog->image));
        $blog->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Blog Deleted'
        ]);
        
    }
}
