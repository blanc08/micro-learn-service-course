<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;

class MentorController extends Controller
{

    public function index()
    {
        $mentor = Mentor::all();

        return response()->json(['status' => 'success', 'data' => $mentor]);
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(['status' => 'error', 'message' => 'Resource not found.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $mentor]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|string',
            'email' => 'required|email',
            'profession' => 'required|string',
        ];

        $data = $request->all();

        $validate = validator($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'profile' => 'string',
            'profession' => 'string',
            'email' => 'email',
        ];

        $data = $request->all();

        $validate = validator($data, $rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found'
            ], 404);
        }

        $mentor->fill($data);
        $mentor->save();

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    public function destroy($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found'
            ], 404);
        }

        $mentor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor deleted'
        ]);
    }
}
