<?php

$types = [];
$workingDirectory = scandir('.');
$columnClassFilesnames = array_slice($workingDirectory, 2);

foreach ($columnClassFilesnames as $filename)
{
  if (str_ends_with($filename, 'Column.php'))
  {
    $className = substr($filename, 0, -4);
    array_push($types, "Assegai\\Database\\Attributes\\Columns\\${className}");
  }
}

return $types;

?>