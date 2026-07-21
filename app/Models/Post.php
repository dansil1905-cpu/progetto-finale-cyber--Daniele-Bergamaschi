<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->string('status')->default('pending');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    });
}
