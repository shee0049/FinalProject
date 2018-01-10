<?php

//Database
define(HOST, "localhost");
define(USER_NAME, "PHPSCRIPT");
define(PASSWORD, "1234");
define(DB_NAME, "CST8257");



//Upload pictures.
define(ORIGINAL_IMAGE_DESTINATION, './Pictures/OriginalPictures');

define(IMAGE_DESTINATION, "./Pictures/AlbumPictures");
define(IMAGE_MAX_HEIGHT, 600);
define (IMAGE_MAX_WIDTH, 800);

define(THUMBNAIL_DESTINATION, "./Pictures/AlbumThumbnails");
define(THUMBNAIL_MAX_HEIGHT, 100);
define(THUMBNAIL_MAX_WIDTH, 100);
        
$supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
date_default_timezone_set("America/Toronto");

