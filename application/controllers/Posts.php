<?php
class Posts extends CI_Controller {
    public function index() {
        // Load the Post_model
        $this->load->model('Post_model');

        // Get posts from the model
        $data['posts'] = $this->Post_model->get_posts();

        // Set the title for the view
        $data['title'] = 'Latest Posts';

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($slug = NULL) {
        // Load the Post_model
        $this->load->model('Post_model');

        // Get the specific post based on the slug
        $data['post'] = $this->Post_model->get_posts($slug);

        if (empty($data['post'])) {
            show_404();
        }

        // Set the title for the view
        $data['title'] = $data['post']['title'];

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('posts/view', $data);
        $this->load->view('templates/footer');
    }
}
?>



