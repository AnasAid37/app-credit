<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnedByUser implements Scope
{
    /**
     * تطبيق الـ scope على الاستعلام
     */
    public function apply(Builder $builder, Model $model)
    {
        // فقط إذا كان المستخدم مسجل دخول
        if (auth()->check()) {
            $builder->where($model->getTable() . '.user_id', auth()->id());
        }
    }
}
