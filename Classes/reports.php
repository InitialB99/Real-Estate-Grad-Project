<?php
require_once 'connect.php';

class Report {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPropertiesByPriceRange($minPrice, $maxPrice) {
        $query = "SELECT * FROM properties WHERE price BETWEEN ? AND ?";
        $params = [$minPrice, $maxPrice];
        return $this->db->read($query, $params);
    }

    public function getPropertiesByLocation($location) {
        $query = "SELECT * FROM properties WHERE location LIKE ?";
        $params = ['%' . $location . '%'];
        return $this->db->read($query, $params);
    }

    public function getPropertiesByAgent($agentId){
        $query = "SELECT * FROM properties WHERE agentid = ?";
        $params = [$agentId];
        return $this->db->read($query, $params);
    }

    public function getPropertiesByType($type) {
        $query = "SELECT * FROM properties WHERE listing_type LIKE ?";
        $params = [$type];
        return $this->db->read($query, $params);
    }
}