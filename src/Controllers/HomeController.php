<?php

namespace App\Controllers;

use Exception;

class HomeController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPaginatedUsers($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // Base query (UNION of adopters and ngos)
        $baseQuery = "
        SELECT id, name, email, 'adopter' AS role FROM adopters
        UNION
        SELECT id, name, email, 'ngo' AS role FROM ngos
        ";

        // Count total records
        $countQuery = "SELECT COUNT(*) as total FROM ({$baseQuery}) AS combined";
        $countResult = $this->conn->query($countQuery);
        if (!$countResult) {
            throw new Exception("Count query failed: " . $this->conn->error);
        }
        $total = (int) $countResult->fetch_assoc()['total'];
        $totalPages = ceil($total / $perPage);

        // Fetch paginated data
        $pagedQuery = $baseQuery . " ORDER BY role, name LIMIT $perPage OFFSET $offset";
        $dataResult = $this->conn->query($pagedQuery);
        if (!$dataResult) {
            throw new Exception("Data query failed: " . $this->conn->error);
        }
        $data = $dataResult->fetch_all(MYSQLI_ASSOC);

        // Return paginated structure
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }
}
