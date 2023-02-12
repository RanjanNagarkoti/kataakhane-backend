<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $data = Restaurant::all();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {

        $data = $request->all();

        $cover_extension = $data['picture']->extension();
        $logo_extension = $data['logo']->extension();

        $unique = date('ymd') . time();

        $cover_name = $unique . '.' . $cover_extension;
        $logo_name = $unique . '.' . $logo_extension;

        $data['picture'] = $cover_name;
        $data['logo'] = $logo_name;

        $request->picture->move(public_path('/imgs/cover'), $cover_name);
        $request->logo->move(public_path('/imgs/logo'), $logo_name);

        Restaurant::create($data);

        return response()->json("Restaurant added successfully", 201);
    }

    public function show($id)
    {
        $data = Restaurant::find($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $data = $request->all();

        if ($request->hasFile('picture')) {
            $cover_extension = $data[' picture']->extension();
            $unique = date('ymd') . time();
            $cover_name = $unique . '.' . $cover_extension;
            $data[' picture'] = $cover_name;
            $request->picture->move(public_path('/imgs/cover'), $cover_name);
        }

        if ($request->hasFile('logo')) {
            $logo_extension = $data['logo']->extension();
            $unique = date('ymd') . time();
            $logo_name = $unique . '.' . $logo_extension;
            $data['logo'] = $logo_name;
            $request->logo->move(public_path('/imgs/logo'), $logo_name);
        }

        $restaurant->update($data);

        return response()->json("success", 200);
    }


    public function destroy($id)
    {
        $data = Restaurant::find($id);
        $data->delete();
        return response()->json(['message' => 'Restaurant deleted successfully']);
    }
}
