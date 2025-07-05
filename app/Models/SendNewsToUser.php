<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SendNewsToUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['email'];
    public $timestamps = false;
}
