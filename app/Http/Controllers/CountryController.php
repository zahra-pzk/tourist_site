<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // لیست همه کشورها
    public function index()
    {
        return response()->json([
            'data' => Country::all()
        ]);
    }

    // ثبت کشور جدید
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name',
        ]);

        $country = Country::create($validated);

        return response()->json([
            'message' => 'کشور با موفقیت ثبت شد.',
            'data'    => $country,
        ], 201);
    }

    // نمایش اطلاعات یک کشور خاص
    public function show(Country $country)
    {
        return response()->json([
            'data' => $country
        ]);
    }

    // ویرایش اطلاعات کشور
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name,' . $country->id,
        ]);

        $country->update($validated);

        return response()->json([
            'message' => 'کشور با موفقیت ویرایش شد.',
            'data'    => $country,
        ]);
    }

    // حذف کشور
    public function destroy(Country $country)
    {
        $country->delete();

        return response()->json([
            'message' => 'کشور با موفقیت حذف شد.'
        ]);
    }
    public function cities(Country $country)
{
    $cities = $country->cities()->withCount('places')->get();
    return response()->json($cities);
}

}
