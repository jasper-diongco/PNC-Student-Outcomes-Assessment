<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MathObject;

class MathObjectsController extends Controller
{

    public function index() {
        return MathObject::where('ref_id', request('ref_id'))->latest()->get();
    }

    public function show(MathObject $math_object) {
        return $math_object;
    }

    public function store() {

        $data = $this->validateData();

        $math_obj = MathObject::create($data);

        return $math_obj;
    }

    public function update(MathObject $math_object) {

        $data = $this->validateData();

        $math_object->update($data);

        return $math_object;
    }

    public function validateData() {

        return request()->validate([
            'formula' => 'required',
            'ref_id' => 'required'
        ]);

    }
}
