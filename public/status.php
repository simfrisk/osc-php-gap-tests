<?php
header("Content-Type: application/json");
echo json_encode(["version"=>"B","host"=>gethostname(),"time"=>gmdate("c")]);