<?php
header("Content-Type: application/json");
echo json_encode(["version"=>"A","host"=>gethostname(),"time"=>gmdate("c")]);