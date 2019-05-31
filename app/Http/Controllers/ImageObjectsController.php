<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImageObject;

class ImageObjectsController extends Controller
{

    public function index() {
        if(request('test_question_id') != '') {
            return ImageObject::where('test_question_id', request('test_question_id'))
                ->latest()
                ->get();
        }
    }

    public function store() {
        $data = request()->validate([
            'description' => 'required|max:191',
            'width' => 'required',
            'height' => 'required',
            'size' => 'required',
            'image' => 'required',
            'test_question_id' => 'required'
        ]);

        $imagePath = request('image')->store('uploads', 'public');

        $image = ImageObject::create([
            'description' => $data['description'],
            'width' => $data['width'],
            'height' => $data['height'],
            'size' => $data['size'],
            'path' => $imagePath,
            'test_question_id' => $data['test_question_id']
        ]);


        return $image;

    }
}
