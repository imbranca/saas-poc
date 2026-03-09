<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
  use SoftDeletes;
  protected $primaryKey = 'id';
  protected $fillable = [
    'name',
    'description',
    'status',//Add cast enum
    'created_by'
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  public function createdBy(): BelongsTo
  {
      return $this->belongsTo(User::class, 'created_by');
  }
}