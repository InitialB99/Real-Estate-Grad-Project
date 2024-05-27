<?php

require_once 'connect.php';

class Upload
{
    private $error = "";
    private $target_file = "";
    private $target_file2 = "";

    public function evaluate($post_data, $files_data)
    {
        $target_dir = "uploads/";

        foreach ($post_data as $key => $value) {
            if (empty($value)) {
                $this->error .= $key . " is empty!<br>";
            }
        }

        // Process first image
        if (isset($files_data['image']) && $files_data['image']['error'] == 0) {
            $this->target_file = $target_dir . basename($files_data["image"]["name"]);
            $imageFileType = strtolower(pathinfo($this->target_file, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check = getimagesize($files_data["image"]["tmp_name"]);
            if ($check === false) {
                $this->error .= "File is not an image.<br>";
            }

            // Check file size
            if ($files_data["image"]["size"] > 5000000) { // 5MB limit
                $this->error .= "Sorry, your file is too large.";
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $this->error .= "Only JPG, JPEG, PNG & GIF files allowed";
            }
        }else {
            $this->error .= "File is empty or not uploaded correctly.<br>";
        }

        // Process second image
        if (isset($files_data['image2']) && $files_data['image2']['error'] == 0) {
            $this->target_file2 = $target_dir . basename($files_data["image2"]["name"]);
            $imageFileType2 = strtolower(pathinfo($this->target_file2, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check2 = getimagesize($files_data["image2"]["tmp_name"]);
            if ($check2 === false) {
                $this->error .= "File is not an image.<br>";
            }

            // Check file size
            if ($files_data["image2"]["size"] > 5000000) { // 5MB limit
                $this->error .= "Sorry, your file is too large.";
            }

            // Allow certain file formats
            if ($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif") {
                $this->error .= "Only JPG, JPEG, PNG & GIF files allowed";
            }
        }else {
            $this->error .= "File is empty or not uploaded correctly.<br>";
        }

        if ($this->error == "") {
            // no error
            return $this->upload_image($post_data, $files_data);
        } else {
            return $this->error;
        }
    }

    public function upload_image($post_data, $files_data)
    {
        if (move_uploaded_file($files_data["image"]["tmp_name"], $this->target_file) && move_uploaded_file($files_data["image2"]["tmp_name"], $this->target_file2)) {

            $agentid = ($post_data["agentid"]);
            $title = ucfirst($post_data["title"]);
            $description = ucfirst($post_data["description"]);
            $location = ucfirst($post_data["location"]);
            $price = $post_data["price"];
            $rooms = $post_data["rooms"];
            $bathrooms = $post_data["bathrooms"];
            $imagePath = $this->target_file;
            $imagePath2 = $this->target_file2;

            $query = "insert into properties (agentid, title, description, location, price, rooms, bathrooms, image, image2, featured) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $params = [$agentid, $title, $description, $location, $price, $rooms, $bathrooms, $imagePath, $imagePath2, 0];

            $DB = new Database();
            $DB->save($query, $params);

            return true;
        } else {
            $this->error = "Sorry, there was an error uploading your files.";
            return $this->error;
        }
    }
}
