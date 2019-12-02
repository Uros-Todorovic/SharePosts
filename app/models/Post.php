<?php

class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
    public function get_posts(){
        $this->db->query("SELECT *,
                         posts.id as postId,
                         users.id as userId,
                         posts.created_at as postCreated,
                         users.created_at as userCreated
                         FROM posts
                         INNER JOIN users
                         ON posts.user_id = users.id
                         ORDER BY posts.created_at DESC
                         ");

        $results = $this->db->result_set();
        return $results;
    }

    public function add_post($data){
        $this->db->query('INSERT INTO posts (title, user_id, body) VALUES (:title, :user_id, :body)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':body', $data['body']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_post_by_id($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row;
    }

    public function update_post($data){
        $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_post($id){
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}