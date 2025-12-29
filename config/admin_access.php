<?php
if (!in_array($_SESSION['role'], ['senior_tech','manager'])) {
    http_response_code(403);
    exit("Access denied");
}
