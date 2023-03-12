<?php
/**
* Built by webpack-php-manifest
* Class WebpackBuiltFiles
*/
class WebpackBuiltFiles {
  static $assets;
  static function init(){
      self::$assets=json_decode(file_get_contents(__DIR__.'/assets-manifest.json'), true);
  }
}

WebpackBuiltFiles::init();

