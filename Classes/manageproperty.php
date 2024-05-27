<?php
class manageProperty
{
    public function saveProperty($userid, $propertyid)
    {
            $db = new Database();
            $query = "insert into saved_properties (userid, spropertyid) 
                      values (?, ?)";
            $params = [$userid, $propertyid];
            $result = $db->save($query, $params);

            if($result === true){
                return true;
            } else{
                return "Error saving property.";
            }
    }

    public function unsaveProperty($userid, $propertyid)
    {
            $db = new Database();
            $query = "DELETE FROM saved_properties WHERE userid = ? AND spropertyid = ?";
            $params = [$userid, $propertyid];
            $result = $db->save($query, $params);

            if($result === true){
                return true;
            } else{
                return "Error unsaving property.";
            }
    }

    public function checkSaved($userid, $propertyid)
    {
        $db = new Database();
        $query = "SELECT * FROM saved_properties WHERE userid = ? AND spropertyid = ?";
        $params = [$userid, $propertyid];
        $result = $db->read($query, $params);
        if($result){
            return $result;
        } else{
            return $result;
        }
    }

    public function update_property($propertyid, $userid, $post_data, $files_data)
    {
        $db = new Database();
        $error = "";

        $title = $post_data['title'];
        $location = $post_data['location'];
        $price = $post_data['price'];
        $rooms = $post_data['rooms'];
        $bathrooms = $post_data['bathrooms'];
        $description = $post_data['description'];

        $query = "UPDATE properties SET title = ?, location = ?, price = ?, rooms = ?, bathrooms = ?, description = ? WHERE propertyid = ? AND agentid = ?";
        $params = [$title, $location, $price, $rooms, $bathrooms, $description, $propertyid, $userid];

        if (!empty($files_data['image']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($files_data["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check = getimagesize($files_data["image"]["tmp_name"]);
            if ($check === false) {
                $error .= "File is not an image.<br>";
            }

            // Check file size
            if ($files_data["image"]["size"] > 5000000) { // 5MB limit
                $error .= "Sorry, your file is too large.<br>";
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error .= "Only JPG, JPEG, PNG & GIF files allowed.<br>";
            }

            if (empty($error) && move_uploaded_file($files_data["image"]["tmp_name"], $target_file)) {
                $query = "UPDATE properties SET image = ? WHERE propertyid = ? AND agentid = ?";
                $params = [$target_file, $propertyid, $userid];
                $db->save($query, $params);
            } else {
                return $error;
            }
        }

        if (!empty($files_data['image2']['name'])) {
            $target_dir = "uploads/";
            $target_file2 = $target_dir . basename($files_data["image2"]["name"]);
            $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check2 = getimagesize($files_data["image2"]["tmp_name"]);
            if ($check2 === false) {
                $error .= "Second file is not an image.<br>";
            }

            // Check file size
            if ($files_data["image2"]["size"] > 5000000) { // 5MB limit
                $error .= "Sorry, your second file is too large.<br>";
            }

            // Allow certain file formats
            if ($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif") {
                $error .= "Only JPG, JPEG, PNG & GIF files allowed.<br>";
            }

            if (empty($error) && move_uploaded_file($files_data["image2"]["tmp_name"], $target_file2)) {
                $query = "UPDATE properties SET image2 = ? WHERE propertyid = ? AND agentid = ?";
                $params = [$target_file2, $propertyid, $userid];
                $db->save($query, $params);
            } else {
                return $error;
            }
        }

        if (empty($error)) {
            $result = $db->save($query, $params);
            if ($result) {
                return true;
            } else {
                return "Failed to update property.";
            }
        } else {
            return $error;
        }
    }

    public function delete_property($propertyid)
    {
        $db = new Database();
        $query = "DELETE FROM properties WHERE propertyid = ?";
        $params = [$propertyid];
        $result = $db->save($query, $params);

        if ($result) {
            return true;
        } else {
            return "Failed to delete property.";
        }
    }
}