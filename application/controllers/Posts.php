<?php
class Posts extends CI_Controller {
    public function index() {
        // Load the Post_model
        $this->load->model('Post_model'); // Corrected syntax

        // Get posts from the model
        $data['posts'] = $this->Post_model->get_posts();

        // Set the title for the view
        $data['title'] = 'Latest Posts';

        // Load views
        $this->load->view('templates/header', $data); // Corrected syntax
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }
}
?>
