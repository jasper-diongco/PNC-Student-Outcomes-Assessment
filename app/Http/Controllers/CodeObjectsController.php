<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\TextOnly;
use App\CodeObject;

class CodeObjectsController extends Controller
{


    public function index() {
        $code_objects = CodeObject::where('ref_id', request('ref_id'))->latest()->get();

        return $code_objects;
    }

    public function show(CodeObject $code_object) {

        return $code_object;

    }

    public function store() {

        $data = $this->validateData();

        $code_obj = CodeObject::create($data);

        return $code_obj;
    }

    public function update(CodeObject $code_object) {
        
        $data = $this->validateData();

        $code_object->update($data);

        return $code_object;
    }

    public function validateData() {
        return request()->validate([
            'description' => ['required', 'max:191', new TextOnly],
            'code' => ['required', 'max:1500'],
            'ref_id' => 'required',
            'language' => 'required'
        ]);
    }
}
