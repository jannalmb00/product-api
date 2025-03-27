<?php

namespace App\Services;

use App\Core\Result;
use App\Models\CategoriesModel;

class CategoriesServices
{
    public function __construct(private CategoriesModel $model) {}

    function CreateCategories(array $categories_info): Result
    {
        //TODO: 1. validate the received resource data about the new resource to be created
        //TODO: 2. Insert the new resource into the  DB table
        $cat = $categories_info[0];
        $id = $this->model->insetCategory($cat);
        //return a successsful result
        return Result::success("Bueno",  $id);
    }
}
