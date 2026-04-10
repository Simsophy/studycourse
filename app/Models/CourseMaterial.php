<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'type',
        'file_path',
        'description',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the course that owns the material.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the full URL to the stored file.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the file's MIME type.
     */
    public function getMimeTypeAttribute(): string
    {
        return mime_content_type(storage_path('app/public/' . $this->file_path));
    }

    /**
     * Get the file size in bytes.
     */
    public function getFileSizeAttribute(): int
    {
        return file_exists(storage_path('app/public/' . $this->file_path))
            ? filesize(storage_path('app/public/' . $this->file_path))
            : 0;
    }

    /**
     * Scope to order materials by the order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
