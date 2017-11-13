<?php


$untrustedUrl = urldecode($_SERVER['REQUEST_URI']);

/**
 * check pattern to get resize type and filename
 */
if (false !== strpos($untrustedUrl, ' ')
    || !preg_match('=(?x)                                            # allow comment and space
                ^(?P<cacheFileName>                                         # start capturing the full url.
                    /cache/                                                # file will be in /cache/
                    (?P<resizeType>\d+x\d+)/?                              # allowed template
                    (?P<relativeFileName>[^\?]*)                            # relative name : anything but "?"
                    \.(?P<extension>jpe?g|png)                            # allowed format : jpg, jpeg and png
                 )
                 $                                                         # end of string
                 =', $untrustedUrl, $m)) {
    header('HTTP/1.1 400 Invalide image path', true, 400);
    die(0);
}

/**
 * set variable from pattern
 */
$cacheFileName = __DIR__ . $m['cacheFileName'];
$filename = __DIR__ . '/' . $m['relativeFileName'] . '.' . $m['extension'];
$resizeType = $m['resizeType'];
$extension = $m['extension'];

header('Content-Type: image/' . $extension);

if (!file_exists($filename)) {
    header('HTTP/1.1 404 Image not found', true, 404);
    die(0);
}

switch ($resizeType) {
    case '1190x440':
        $rszWidthGoal = 1190;
        $rszHeightGoal = 440;
        break;
    case '240x130':
        $rszWidthGoal = 240;
        $rszHeightGoal = 130;
        break;
    case '340x190':
        $rszWidthGoal = 340;
        $rszHeightGoal = 190;
        break;
    default:
        /**
         * not found ? go away ddosser
         */
        header('HTTP/1.1 400 invalide template', true, 400);
        die(0);
}

$im = new Imagick();
$im->readImage($filename);
switch($extension){
    case 'png':
        $im->setImageFormat('png');
        break;
    case 'jpg':
    case 'jpeg':
    $im->setImageFormat('jpeg');
        break;
}
$im->resizeImage($rszWidthGoal, $rszHeightGoal, Imagick::FILTER_LANCZOS, 1);

if(!file_exists(dirname($cacheFileName))) {
    mkdir(dirname($cacheFileName), 0777, true);
}

$im->writeImage($cacheFileName);

header('HTTP/1.1 200 resized image generated', true, 200);
header('Cache-Control: max-age=604800, public');
$now = time();
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $now) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', $now + 604800 /* 7 days */) . ' GMT');



echo $im->getImageBlob();
