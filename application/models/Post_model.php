<?php
class Post_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    public function get_posts($slug = FALSE, $limit=FALSE,  $offset=FALSE)
    {
        if($limit){
            $this->db->limit($limit, $offset);
        }
        if ($slug === FALSE) {
            $this->db->order_by('posts.id', 'DESC');
            $this->db->join('categories', 'categories.id=posts.category_id');
            $query = $this->db->get('posts');
            return $query->result_array();
        }
        $query = $this->db->get_where('posts', array('slug' => $slug));
        return $query->row_array();
    }
    public function create_post($post_image)
    {
        $slug = url_title($this->input->post('title'));
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'body' => $this->input->post('body'),
            'category_id' => $this->input->post('category_id'),
            'user_id'=>$this->session->userdata('user_id'),
            'post_image' => $post_image

        );
        return $this->db->insert('posts', $data);
    }
    public function delete_post($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('posts');
        return true;
    }
    public function update_post()
    {
        $slug = url_title($this->input->post('title'));

        // Get the existing file name
        $existing_file = $this->db->select('post_image')->get_where('posts', array('id' => $this->input->post('id')))->row('post_image');

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'body' => $this->input->post('body'),
            'category_id' => $this->input->post('category_id'),

            // Add more fields as needed
        );

        // Handle file update
        if ($_FILES['userfile']['name']) {
            $image_path = realpath(APPPATH . '../images/posts-images');
            $config['upload_path'] = $image_path;
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size'] = '0';
            $config['max_width'] = '0';
            $config['max_height'] = '0';
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('userfile')) {
                // Delete the existing file
                if ($existing_file) {
                    unlink($image_path . $existing_file);
                }

                $data['post_image'] = $this->upload->data('post_image');
            }
        } else {
            // Keep the existing file
            $data['post_image'] = $existing_file;
        }

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('posts', $data);
    }

    public function get_categories()
    {
        $this->db->order_by('name');
        $query = $this->db->get('categories');
        return $query->result_array();
    }
    public function get_posts_by_category($category_id)
    {
        $this->db->order_by('posts.id', 'DESC');
        $this->db->join('categories', 'categories.id=posts.category_id');
        $query = $this->db->get_where('posts', array('category_id' => $category_id));
        return $query->result_array();
    }
}
