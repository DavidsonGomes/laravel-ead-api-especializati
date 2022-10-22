<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $repository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->repository = $courseRepository;
    }

    public function index()
    {
        $courses = $this->repository->getAllCourses();

        return CourseResource::collection($courses);
    }

    public function show($id)
    {
        $course = $this->repository->getCourse($id);

        return new CourseResource($course);
    }
}
