<?php
require_once 'connect.php';

class Upload
{
    private $error = "";
    private $target_file = "";

    public function evaluate($post_data,$files_data)
    {
        
        $target_dir = "uploads/";

            foreach ($post_data as $key => $value){

                if(empty($value))
                {
                    $this->error .= $key . " is empty!<br>";
                }
            }

            if (isset($files_data['image']) && $files_data['image']['error'] == 0) {
                $this->target_file = $target_dir . basename($files_data["image"]["name"]);
                $imageFileType = strtolower(pathinfo($this->target_file, PATHINFO_EXTENSION));
    
                // Check if image file is an actual image or fake image
                $check = getimagesize($files_data["image"]["tmp_name"]);
                if ($check === false) {
                    $this->error .= "File is not an image.<br>";
                }

                // Check if file already exists
                if (file_exists($this->target_file)) {
                    $this->error .= "Sorry, file already exists.";
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

        if ($this->error == "") {
            // no error
            $this->upload_image($post_data,$files_data);
        }else {
            return $this->error;
        }
    }

    public function upload_image($post_data,$files_data){
            if(move_uploaded_file($files_data["image"]["tmp_name"], $this->target_file)){

                $title = ucfirst($post_data["title"]);
                $description = ucfirst($post_data["description"]);
                $location = ucfirst($post_data["location"]);
                $price = $post_data["price"];
                $rooms = $post_data["rooms"];
                $bathrooms = $post_data["bathrooms"];
                $imagePath = $this->target_file;

                $query = "insert into properties (title, description, location, price, rooms, bathrooms, image, featured) VALUES (?,?,?,?,?,?,?,?)";
                $params = [$title, $description, $location, $price, $rooms, $bathrooms, $imagePath, 0];

                $DB = new Database();
                $DB->save($query, $params);

            }else {
                $this->error = "Sorry, there was an error uploading your file.";
                return $this->error;
            }
        }
    
}