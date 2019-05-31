<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImageObject;
use App\Rules\TextOnly;

class ImageObjectsController extends Controller
{

    public function index() {
        if(request('ref_id') != '') {
            return ImageObject::where('ref_id', request('ref_id'))
                ->latest()
                ->get();
        }
    }

    public function show(ImageObject $image_object) {

        return $image_object;
    }

    public function store() {
        $data = request()->validate([
            'description' => ['required', 'max:191', new TextOnly],
            'width' => 'required',
            'height' => 'required',
            'size' => 'required',
            'image' => 'required',
            'ref_id' => 'required'
        ]);

        $imagePath = request('image')->store('uploads', 'public');

        $image = ImageObject::create([
            'description' => $data['description'],
            'width' => $data['width'],
            'height' => $data['height'],
            'size' => $data['size'],
            'path' => $imagePath,
            'ref_id' => $data['ref_id']
        ]);


        return $image;

    }

    public function update(ImageObject $image_object) {

        $data = request()->validate([
            'description' => ['required', 'max:191', new TextOnly],
            'width' => 'required',
            'height' => 'required',
            'size' => 'required'
        ]);

        $image_object->update($data);

        return $image_object;
    }
}
