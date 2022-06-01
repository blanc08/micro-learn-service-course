<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chapters = Chapter::query();

        $chapters->when($request->has('course_id') && $request->course_id, function ($query) use ($request) {
            $query->where('course_id', $request->course_id);
        });

        return response()->json(['status' => 'success', 'data' => $chapters->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "course_id" => "required|integer",
        ];

        $data = $request->all();

        $validate = Validator($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validate->errors()
            ], 400);
        }

        $course = Course::find($data["course_id"]);
        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "Course not found"
            ], 404);
        }

        $chapter = Chapter::create($data);

        return response()->json([
            "status" => "success",
            "data" => $chapter
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "data" => $chapter
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "string",
            "course_id" => "integer",
        ];

        $data = $request->all();

        $validate = Validator($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validate->errors()
            ], 400);
        }

        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found"
            ], 404);
        }

        $course_id = $request->input("course_id");
        if ($course_id) {
            $course = Course::find($course_id);
            if (!$course) {
                return response()->json([
                    "status" => "error",
                    "message" => "Course not found"
                ], 404);
            }
        }

        $chapter->update($data);

        return response()->json([
            "status" => "success",
            "data" => $chapter
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found"
            ], 404);
        }

        $chapter->delete();

        return response()->json([
            "status" => "success",
            "message" => "Chapter deleted"
        ], 200);
    }
}
