<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    protected $entity;

    public function __construct(Course $model)
    {
        $this->entity = $model;
    }

    public function getAllCourses()
    {
        return $this->entity->query()->get();
    }

    public function getCourse(String $id)
    {
        return $this->entity->query()->findOrFail($id);
    }
}
