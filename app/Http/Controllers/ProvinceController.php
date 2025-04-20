<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{

    public function index()
    {
        return response()->json(Province::with('country')->get());
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:provinces,name',
            'country_id' => 'required|exists:countries,id',
        ]);

        $province = Province::create($validated);

        return response()->json([
            'message' => 'استان با موفقیت ثبت شد.',
            'data' => $province,
        ], 201);
    }
    public function show(Province $province)
    {
        return response()->json($province->load('country'));
    }

    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:provinces,name,' . $province->id,
            'country_id' => 'required|exists:countries,id',
        ]);

        $province->update($validated);

        return response()->json([
            'message' => 'استان با موفقیت ویرایش شد.',
            'data' => $province,
        ]);
    }

    public function destroy(Province $province)
    {
        $province->delete();

        return response()->json(['message' => 'استان با موفقیت حذف شد.']);
    }
}
