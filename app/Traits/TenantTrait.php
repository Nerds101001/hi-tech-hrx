<?php

namespace App\Traits;

use App\Models\Tenant;

trait TenantTrait
{
  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }


  protected static function bootTenantTrait(): void
  {
    //Set tenant_id on creation
    static::creating(function ($model) {
      $model->tenant_id = '';
    });
  }
}
