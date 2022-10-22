<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository
{
    protected $entity;

    public function __construct(Module $model)
    {
        $this->entity = $model;
    }

    public function getModulesByCourseId(String $courseId)
    {
        return $this->entity
            ->query()
            ->where('course_id', $courseId)
            ->get();
    }
}
