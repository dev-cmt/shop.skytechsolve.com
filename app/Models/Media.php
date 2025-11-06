<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'model_type',
        'model_id',
        'type',
        'alt_text',
        'size',
        'sort_order',
        'user_id'
    ];

    protected $casts = [
        'size' => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent model (polymorphic relationship)
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for images only
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope for videos only
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope for documents only
     */
    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for specific model
     */
    public function scopeForModel($query, $modelType, $modelId)
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }

    /**
     * Accessor for full URL
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Accessor for thumbnail URL (for images)
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->type === 'image') {
            $pathInfo = pathinfo($this->path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

            return file_exists(storage_path('app/public/' . $thumbnailPath))
                ? asset('storage/' . $thumbnailPath)
                : $this->url;
        }

        return $this->url;
    }

    /**
     * Accessor for file extension
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Accessor for file size in human readable format
     */
    public function getSizeFormattedAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->size;
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    /**
     * Accessor for display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: pathinfo($this->path, PATHINFO_FILENAME);
    }

    /**
     * Check if media is an image
     */
    public function getIsImageAttribute()
    {
        return $this->type === 'image';
    }

    /**
     * Check if media is a video
     */
    public function getIsVideoAttribute()
    {
        return $this->type === 'video';
    }

    /**
     * Check if media is a document
     */
    public function getIsDocumentAttribute()
    {
        return $this->type === 'document';
    }

    /**
     * Get supported image MIME types
     */
    public static function getImageMimeTypes()
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];
    }

    /**
     * Get supported video MIME types
     */
    public static function getVideoMimeTypes()
    {
        return [
            'video/mp4',
            'video/mpeg',
            'video/ogg',
            'video/webm',
            'video/quicktime'
        ];
    }

    /**
     * Get supported document MIME types
     */
    public static function getDocumentMimeTypes()
    {
        return [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ];
    }

    /**
     * Get file type from MIME type
     */
    public static function getTypeFromMime($mimeType)
    {
        if (in_array($mimeType, self::getImageMimeTypes())) {
            return 'image';
        } elseif (in_array($mimeType, self::getVideoMimeTypes())) {
            return 'video';
        } elseif (in_array($mimeType, self::getDocumentMimeTypes())) {
            return 'document';
        }

        return 'document'; // default fallback
    }
}
