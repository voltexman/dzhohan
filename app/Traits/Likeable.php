<?php

namespace App\Traits;

trait Likeable
{
    public function like()
    {
        return $this->likes()->firstOrCreate([
            'visitor_token' => request()->cookie('visitor_token'),
        ]);
    }

    public function unlike()
    {
        return $this->likes()
            ->where('visitor_token', request()->cookie('visitor_token'))
            ->delete();
    }

    public function isLiked(): bool
    {
        return $this->likes()
            ->where('visitor_token', request()->cookie('visitor_token'))
            ->exists();
    }
}
