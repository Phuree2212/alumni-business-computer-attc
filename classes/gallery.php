<?php

class Gallery
{
    private $news;
    private $activity;

    public function __construct(News $news, Activities $activity)
    {
        $this->news = $news;
        $this->activity = $activity;
    }

    public function getAllImages()
    {
        $images = [];

        $image_activity = explode(",", implode(",", $this->activity->getAllImageActivity()));
        $image_news = explode(",", implode(",", $this->news->getAllImageNews()));

        foreach ($image_activity as $image) {
            $images[] = [
                'type' => 'activity',
                'image' => $image
            ];
        }

        foreach ($image_news as $image) {
            $images[] = [
                'type' => 'news',
                'image' => $image
            ];
        }

        return $images;
    }
}
