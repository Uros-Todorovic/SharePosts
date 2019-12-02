<?php

class Pages extends Controller {

    public function __construct(){
      
    }

    public function index(){
        if (is_logged_in()) {
            redirect('posts');
        }

        $data = [
                'title' => 'Share Your Posts ',
                'description' => 'Simple social network built on the PHP MVC framework '
                ];

        $this->view('pages/index', $data);
    }

    public function about(){
        $data = [
            'title'=>'About Us',
            'description' => 'App to share posts with other users '
        ];
        $this->view('pages/about', $data);
    }
}