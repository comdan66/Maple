<?php

namespace M;

class Ckeditor extends Model {}

Ckeditor::imageUploader('image')
        ->default()
        ->version('w100', ['resize', 100, 100, 'width'])
        ->version('c120x120', ['adaptiveResizeQuadrant', 120, 120, 'c']);