<?php
namespace App\Traits\Scopes;

use App\Enums\AppEnums;
use Illuminate\Database\Eloquent\Builder;

trait StatusScope{
    public function scopeActive(Builder $builder){
        return $builder->where("status", AppEnums::active);
    }
}
