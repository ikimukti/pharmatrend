<?php
require_once('config.php');
$term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends
$query = "SELECT * FROM item WHERE name LIKE '%".$term."%' ORDER BY name ASC";
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        'label' => $row['name'],
        'value' => $row['name']
    );
}
echo json_encode($data);
?>