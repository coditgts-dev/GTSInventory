<?php
if ($_SESSION['role'] !== 'manager') {
    http_response_code(403);
    exit("Access denied");
}
