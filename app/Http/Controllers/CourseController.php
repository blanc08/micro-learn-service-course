<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::query();

        $courses->when($request->has('q') && $request->q, function ($query) use ($request) {
            $query->where('name', 'like', "%" . strtolower($request->q) . "%");
        });

        $courses->when($request->has('status') && $request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        });

        return response()->json(['status' => 'success', 'data' => $courses->paginate(10)]);
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
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'required|string',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:beginner,intermediate,advanced',
            'mentor_id' => 'required|integer',
            'description' => 'string',
        ];

        $data = $request->all();

        $validate = Validator($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $mentor = Mentor::find($data['mentor_id']);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found'
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
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
        $course = Course::with(['chapters.lessons', 'mentor', 'images'])->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $reviews = Review::where('course_id', $id)->get()->toArray();
        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            if ($users['status'] === 'error') {
                $reviews = [];
            } else {
                // $reviews = array_map(function ($review) use ($users) {
                //     $review['user'] = $users['data'][array_search($review['user_id'], $users)];
                //     return $review;
                // }, $reviews);

                foreach ($reviews as $key => $value) {
                    $userIndex = array_search($value['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['user'] = $users['data'][$userIndex];
                }
            }
        }

        $totalStudents = MyCourse::where('course_id', $id)->count();
        $totalVideos = Chapter::where('course_id', $id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_videos'] = $finalTotalVideos;
        $course['total_students'] = $totalStudents;

        return response()->json([
            'status' => 'success',
            'data' => $course
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

        $rules =  $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:beginner,intermediate,advanced',
            'mentor_id' => 'integer',
            'description' => 'string',
        ];

        $data = $request->all();

        $validate = Validator($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $mentorId = $request->input('mentor_id');
        if ($mentorId) {   
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mentor not found'
                ], 404);
            }
        }

        $course->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
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
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted'
        ], 200);
    }
}
