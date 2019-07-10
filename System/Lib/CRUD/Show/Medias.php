<?php

namespace CRUD\Show;

class Medias extends \CRUD\Show\Unit\Nomin {
  public function val($medias) {
    $medias = implode('', array_map(function($media) {
      
      if (is_array($media))
        if ($media['type'] == 'video')
          return '<div class="video">' . (($media['video'] = $media['video'] instanceof \_M\FileUploader ? $media['video']->url() : $media['video']) ? '<video controls src="' . $media['video'] . '">你的瀏覽器可能無法播放喔！</video>' : '') . '</div>';
        else
          return '<div class="image">' . (($media['image'] = $media['image'] instanceof \_M\ImageUploader ? $media['image']->url() : $media['image']) ? '<img src="' . $media['image'] . '"><div></div>' : '') . '</div>';
      else
        return '<div class="image">' . (($media = $media instanceof \_M\ImageUploader ? $media->url() : $media) ? '<img src="' . $media . '"><div></div>' : '') . '</div>';
    }, $medias));

    parent::val($medias);

    return $this->className('medias');
  }
}