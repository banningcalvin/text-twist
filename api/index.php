<?php
$verb = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['PATH_INFO'];
$routes = explode("/", $uri);

$dbhandle = new PDO("sqlite:basicdatabase.db") or die("Failed to open DB");
if (!$dbhandle) die ($error);

function readall(){ 
    $query = "SELECT value FROM numbers";
    $statement = $dbhandle->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
};
function readone($id){
    $query = "SELECT value FROM numbers where id=:id";
    $query->bindParam(':id', $id);
    $statement = $dbhandle->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
};
function createone(){ return "create onehere";};
function updateone($id){ return "update onehere ".$id;};
function deleteone($id){ return "delete onehere ".$id;};

$results = "Usage: GET /number[/:id], POST /number, PUT /number/:id, DELETE /number/:id";

if ($routes[1] == "number"){
  if ($verb == "GET"){
    if (count($routes) < 3){
      $results = readall();
    } else {
      $results = readone($routes[2]);
    }
  }
  if ($verb == "POST"){
    $results = createone();
  }
  if ($verb == "PUT"){
    if (count($routes) < 3){
      $results = "Usage: PUT /number/:id";
    } else {
      $results = updateone($routes[2]);
    }
  }
  if ($verb == "DELETE"){
    if (count($routes) < 3){
      $results = "Usage: DELETE /number/:id";
    } else {
      $results = deleteone($routes[2]);
    }
  }
} 

header('HTTP/1.1 200 OK');
header('Content-Type: application/json');
echo json_encode($results);

?>
