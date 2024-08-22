<?php

namespace App\DTOs;

class MessageAttachmentDTO
{
    /**
     * Create a new class instance.
     */
    public $name;
    public $type;
    public $size;
    public $mime;
    public $mimeType;
    public $url;
    public $width;
    public $height;
    public $description;
    public $caption;
    public function __construct(
        $name,
        $type,
        $mime,
        $url,
        $size,
        $caption = null,
        $description = null,
        $mimeType = null,
        $width = null,
        $height = null
    ) {
        //
        $this->name = $name;
        $this->type = $type;
        $this->mime = $mime;
        $this->url = $url;
        $this->size = $size;
        $this->caption = $caption;
        $this->description = $description;
        $this->mimeType  = $mimeType;
        $this->width  = $width;
        $this->height  = $height;
    }
}
