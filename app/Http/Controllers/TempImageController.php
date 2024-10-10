<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
class TempImageController extends Controller
{
    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'image'=>'required|image'

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Some Error Occur in image',
                'errors'=>$validator->errors()
            ]);
    }
    $image = $req->image;
    $ext = $image->getClientOriginalExtension();
    $imageName = time().'.'.$ext;

    $tempImage = new TempImage();
    $tempImage->name = $imageName;
    $tempImage->save();
    $image->move(public_path('uploads/temp'),$imageName);

    return response()->json([
        'status'=>true,
        'message'=>'Image Uploaded',
        'image'=>$tempImage
    ]);
}
}
