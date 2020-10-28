<?php

namespace App\Services;


use Intervention\Image\Facades\Image;

class FaceCutter
{
    protected $image;

    protected $pathname;

    protected $points;

    /**
     * FaceCutter constructor.
     * @param $image
     * @param $points
     */
    public function __construct($pathname, $points)
    {
        $this->pathname = $pathname;
        $this->points = $points;
    }

    public function cut()
    {
        $this->image = Image::make($this->pathname);

        $w = $this->points->width + 20;
        $h = $this->points->height + 20;
        $x = $this->points->left - 10;
        $y = $this->points->top - 10;

        $this->image->crop($w, $h, $x, $y);

        return $this;
    }

    public function getAsBase64()
    {
        return (string)$this->image->encode('data-url');
    }
}