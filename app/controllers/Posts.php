<?php 

class Posts extends Controller {

    public function __construct(){
        if (!is_logged_in()) {
            redirect('users/login');
        }
        $this->post_model = $this->model('Post');
        $this->user_model = $this->model('User');
    }

    public function index(){
        $posts =$this->post_model->get_posts();

        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);
    }

    public function add(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_error' => '',
                'body_error' => ''
            ];

            if (empty($data['title'])) {
                $data['title_error'] = 'Please enter title';
            }
            if (empty($data['body'])) {
                $data['body_error'] = 'Please enter body text';
            }

            if(empty($data['title_error']) && empty($data['body_error'])) {
                if ($this->post_model->add_post($data)) {
                    flash('post_message', 'Post Added');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('posts/add', $data);
            }

        } else {
            $data = [
                'title' => '',
                'body' => ''
            ];
    
            $this->view('posts/add', $data);
        }
    }

    public function edit($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_error' => '',
                'body_error' => ''
            ];

            if (empty($data['title'])) {
                $data['title_error'] = 'Please enter title';
            }
            if (empty($data['body'])) {
                $data['body_error'] = 'Please enter body text';
            }

            if(empty($data['title_error']) && empty($data['body_error'])) {
                if ($this->post_model->update_post($data)) {
                    flash('post_message', 'Post Updated');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('posts/edit', $data);
            }

        } else {
            $post = $this->post_model->get_post_by_id($id);
            if ($post->user_id != $_SESSION['user_id'] ) {
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];
    
            $this->view('posts/edit', $data);
        }
    }


    public function show($id){
        $post = $this->post_model->get_post_by_id($id);
        $user = $this->user_model->find_user_by_id($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user
        ];
        $this->view('posts/show', $data);
    }

    public function delete($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->post_model->get_post_by_id($id);
            if ($post->user_id != $_SESSION['user_id'] ) {
                redirect('posts');
            }
            if ($this->post_model->delete_post($id)) {
                flash('post_message', 'Post Removed');
                redirect('posts');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('post');
        }
    }
}