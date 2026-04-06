<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingType extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'discount_percentage',
    'priority',
    'status_id'
  ];

  public function status()
  {
    return $this->belongsTo(ProjectStatus::class);
  }
}
