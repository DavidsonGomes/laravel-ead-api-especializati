<?php

namespace App\Repositories;

use App\Models\Lesson;

class LessonRepository
{
    protected $entity;

    public function __construct(Lesson $model)
    {
        $this->entity = $model;
    }

    public function getLesson($id)
    {
        return $this->entity->query()->findOrFail($id);
    }

    public function getLessonsByModuleId(String $moduleId)
    {
        return $this->entity
            ->query()
            ->where('module_id', $moduleId)
            ->get();
    }
}
