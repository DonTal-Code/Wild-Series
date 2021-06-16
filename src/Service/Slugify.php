<?php

namespace App\Service;

class Slugify {

    public function generate(string $input): string {

/* return(string) preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'),
     array('', '-', ''), $input);*/

        $slug = preg_replace('~[^\pL\d]+~u', '-', $input);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('~-+~', '-', $slug);
        return strtolower($slug);
    }
}
