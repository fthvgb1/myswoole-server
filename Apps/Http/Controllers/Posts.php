<?php


namespace Apps\Http\Controllers;


class Posts
{
    public function view(\Apps\Models\Posts $posts)
    {
        print_r($posts->toArray());
        return '';
        //return $posts->toArray();
    }

    public function aa()
    {
        return 'miss';
    }
}