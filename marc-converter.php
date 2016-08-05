<?php
require 'vendor/autoload.php';
require 'File/MARC.php';

$xml_directory = $argv[1];
$mrc_path = $argv[2];
// Read MARC records from a stream (a file, in this case)
$marc_source = new File_MARC($mrc_path);
$marc_xmls = [];
while ($marc_record = $marc_source->next()) {
  $identifierFields = $marc_record->getFields('952');
  $biblioId = '';
  foreach ($identifierFields as $field) {
    $biblio = $field->getSubfield('p');
    if ($biblio) {
      $biblioId = $biblio->getData();
    }
    break;
  }

  if ($biblioId) {
    $marc_xmls[$biblioId] = $marc_record->toXML();
    $fp = fopen("$xml_directory/$biblioId.xml","wb");
    fwrite($fp,$marc_xmls[$biblioId]);
    fclose($fp);
  }
}