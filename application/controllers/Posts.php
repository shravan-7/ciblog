<?php
class Posts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
    }
    public function index($offset = 0)
    {
        
        $this->load->model('Post_model'); // Load the Post_model

        $config['base_url'] = site_url('posts/index/'); // Use site_url() to generate base URL
        $config['total_rows'] = $this->db->count_all('posts');
        $config['per_page'] = 3;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['title'] = 'Latest Posts';
        $data['posts'] = $this->post_model->get_posts(FALSE, $config['per_page'], $offset);

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }
    


    public function view($slug = NULL)
    {

        // Load the Post_model
        $this->load->model('Post_model');

        // Get the specific post based on the slug
        $data['post'] = $this->post_model->get_posts($slug);
        $post_id = $data['post']['id'];
        $data['comments'] = $this->comment_model->get_comments($post_id);
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
    public function create()
    {
        //check login
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'Create Post';
        $data['categories'] = $this->post_model->get_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('posts/create', $data);
            $this->load->view('templates/footer');
        } else {
            $image_path = realpath(APPPATH . '../images/posts-images');
            $config['upload_path'] = $image_path;
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size'] = '2048';
            $config['max_width'] = '5000';
            $config['max_height'] = '5000';

            $this->load->library('upload', $config);
            //checking if image uploaded or Not
            if (!$this->upload->do_upload()) {
                $errors = array('error' => $this->upload->display_errors());
                $post_image = 'noimage.png';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $post_image = $_FILES['userfile']['name'];
            }
            $this->post_model->create_post($post_image);
            $this->session->set_flashdata('post_created', 'Your post has been created');
            redirect('posts');
        }
    }
    public function delete($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $this->post_model->delete_post($id);
        $this->session->set_flashdata('post_deleted', 'Your post has been deleted');
        redirect('posts');
    }
    public function edit($slug)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $this->load->model('Post_model');

        // Get the specific post based on the slug
        $data['post'] = $this->Post_model->get_posts($slug);
        //check user
        if ($this->session->userdata('user_id') !== $this->post_model->get_posts($slug)['user_id']) {
            redirect('posts');
        }
        $data['categories'] = $this->post_model->get_categories();

        if (empty($data['post'])) {
            show_404();
        }

        // Set the title for the view
        $data['title'] = 'Edit Post';

        // Load views
        $this->load->view('templates/header');
        $this->load->view('posts/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $this->post_model->update_post();
        $this->session->set_flashdata('post_updated', 'Your post has been updated');
        redirect('posts');
    }
}
