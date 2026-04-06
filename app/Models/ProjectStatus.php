<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{

  /** The table associated with the model. */
  protected $table = 'project_statuses';

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'description',
    'color',
    'is_default',
    'is_final',
    'order'
  ];
}
