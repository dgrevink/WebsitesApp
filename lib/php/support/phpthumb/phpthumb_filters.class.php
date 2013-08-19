<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.filters.php - image processing filter functions //
//                                                         ///
//////////////////////////////////////////////////////////////

class phpthumb_filters {

    var $phpThumbObject = null;

    function phpthumb_filters() {
        return true;
    }

    function ApplyMask(&$gdimg_mask, &$gdimg_image) {
        if (phpthumb_functions::gd_version() < 2) {
            $this->DebugMessage('Skipping ApplyMask() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
            return false;
        }
        if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {

            if ($gdimg_mask_resized = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_image), ImageSY($gdimg_image))) {

                ImageCopyResampled($gdimg_mask_resized, $gdimg_mask, 0, 0, 0, 0, ImageSX($gdimg_image), ImageSY($gdimg_image), ImageSX($gdimg_mask), ImageSY($gdimg_mask));
                if ($gdimg_mask_blendtemp = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_image), ImageSY($gdimg_image))) {

                    $color_background = ImageColorAllocate($gdimg_mask_blendtemp, 0, 0, 0);
                    ImageFilledRectangle($gdimg_mask_blendtemp, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp), $color_background);
                    ImageAlphaBlending($gdimg_mask_blendtemp, false);
                    ImageSaveAlpha($gdimg_mask_blendtemp, true);
                    for ($x = 0; $x < ImageSX($gdimg_image); $x++) {
                        for ($y = 0; $y < ImageSY($gdimg_image); $y++) {
                            //$RealPixel = phpthumb_functions::GetPixelColor($gdimg_mask_blendtemp, $x, $y);
                            $RealPixel = phpthumb_functions::GetPixelColor($gdimg_image, $x, $y);
                            $MaskPixel = phpthumb_functions::GrayscalePixel(phpthumb_functions::GetPixelColor($gdimg_mask_resized, $x, $y));
                            $MaskAlpha = 127 - (floor($MaskPixel['red'] / 2) * (1 - ($RealPixel['alpha'] / 127)));
                            $newcolor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_mask_blendtemp, $RealPixel['red'], $RealPixel['green'], $RealPixel['blue'], $MaskAlpha);
                            ImageSetPixel($gdimg_mask_blendtemp, $x, $y, $newcolor);
                        }
                    }
                    ImageAlphaBlending($gdimg_image, false);
                    ImageSaveAlpha($gdimg_image, true);
                    ImageCopy($gdimg_image, $gdimg_mask_blendtemp, 0, 0, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp));
                    ImageDestroy($gdimg_mask_blendtemp);

                } else {
                    $this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
                }
                ImageDestroy($gdimg_mask_resized);

            } else {
                $this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
            }

        } else {
            // alpha merging requires PHP v4.3.2+
            $this->DebugMessage('Skipping ApplyMask() technique because PHP is v"'.phpversion().'"', __FILE__, __LINE__);
        }
        return true;
    }


    function Bevel(&$gdimg, $width, $hexcolor1, $hexcolor2) {
        $width     = ($width     ? $width     : 5);
        $hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'FFFFFF');
        $hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');

        ImageAlphaBlending($gdimg, true);
        for ($i = 0; $i < $width; $i++) {
            $alpha = round(($i / $width) * 127);
            $color1 = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1, false, $alpha);
            $color2 = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2, false, $alpha);

            ImageLine($gdimg,                   $i,                   $i + 1,                   $i, ImageSY($gdimg) - $i - 1, $color1); // left
            ImageLine($gdimg,                   $i,                   $i    , ImageSX($gdimg) - $i,                   $i    , $color1); // top
            ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i - 1, ImageSX($gdimg) - $i,                   $i + 1, $color2); // right
            ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i    ,                   $i, ImageSY($gdimg) - $i    , $color2); // bottom
        }
        return true;
    }


    function Blur(&$gdimg, $radius=0.5) {
        // Taken from Torstein Hønsi's phpUnsharpMask (see phpthumb.unsharp.php)

        $radius = round(max(0, min($radius, 50)) * 2);
        if (!$radius) {
            return false;
        }

        $w = ImageSX($gdimg);
        $h = ImageSY($gdimg);
        if ($imgBlur = ImageCreateTrueColor($w, $h)) {
            // Gaussian blur matrix:
            //    1    2    1
            //    2    4    2
            //    1    2    1

            // Move copies of the image around one pixel at the time and merge them with weight
            // according to the matrix. The same matrix is simply repeated for higher radii.
            for ($i = 0; $i < $radius; $i++)    {
                ImageCopy     ($imgBlur, $gdimg, 0, 0, 1, 1, $w - 1, $h - 1);            // up left
                ImageCopyMerge($imgBlur, $gdimg, 1, 1, 0, 0, $w,     $h,     50.00000);  // down right
                ImageCopyMerge($imgBlur, $gdimg, 0, 1, 1, 0, $w - 1, $h,     33.33333);  // down left
                ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 1, $w,     $h - 1, 25.00000);  // up right
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 1, 0, $w - 1, $h,     33.33333);  // left
                ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 0, $w,     $h,     25.00000);  // right
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 1, $w,     $h - 1, 20.00000);  // up
                ImageCopyMerge($imgBlur, $gdimg, 0, 1, 0, 0, $w,     $h,     16.666667); // down
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 0, $w,     $h,     50.000000); // center
                ImageCopy     ($gdimg, $imgBlur, 0, 0, 0, 0, $w,     $h);
            }
            return true;
        }
        return false;
    }


    function BlurGaussian(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        $this->DebugMessage('FAILED: phpthumb_filters::BlurGaussian($gdimg) [using phpthumb_filters::Blur() instead]', __FILE__, __LINE__);
        return phpthumb_filters::Blur($gdimg, 0.5);
    }


    function BlurSelective(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        // currently not implemented "the hard way"
        $this->DebugMessage('FAILED: phpthumb_filters::BlurSelective($gdimg) [function not implemented]', __FILE__, __LINE__);
        return false;
    }


    function Brightness(&$gdimg, $amount=0) {
        if ($amount == 0) {
            return true;
        }
        $amount = max(-255, min(255, $amount));

        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_BRIGHTNESS, $amount)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_BRIGHTNESS, '.$amount.')', __FILE__, __LINE__);
            // fall through and try it the hard way
        }

        $scaling = (255 - abs($amount)) / 255;
        $baseamount = (($amount > 0) ? $amount : 0);
        for ($x = 0; $x < ImageSX($gdimg); $x++) {
            for ($y = 0; $y < ImageSY($gdimg); $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                foreach ($OriginalPixel as $key => $value) {
                    $NewPixel[$key] = round($baseamount + ($OriginalPixel[$key] * $scaling));
                }
                $newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }


    function Contrast(&$gdimg, $amount=0) {
        if ($amount == 0) {
            return true;
        }
        $amount = max(-255, min(255, $amount));

        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            // ImageFilter(IMG_FILTER_CONTRAST) has range +100 to -100 (positive numbers make it darker!)
            $amount = ($amount / 255) * -100;
            if (ImageFilter($gdimg, IMG_FILTER_CONTRAST, $amount)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_CONTRAST, '.$amount.')', __FILE__, __LINE__);
            // fall through and try it the hard way
        }

        if ($amount > 0) {
            $scaling = 1 + ($amount / 255);
        } else {
            $scaling = (255 - abs($amount)) / 255;
        }
        for ($x = 0; $x < ImageSX($gdimg); $x++) {
            for ($y = 0; $y < ImageSY($gdimg); $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                foreach ($OriginalPixel as $key => $value) {
                    $NewPixel[$key] = min(255, max(0, round($OriginalPixel[$key] * $scaling)));
                }
                $newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
    }


    function Colorize(&$gdimg, $amount, $targetColor) {
        $amount      = (is_numeric($amount)                          ? $amount      : 25);
        $amountPct   = $amount / 100;
        $targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'gray');

        if ($amount == 0) {
            return true;
        }

        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if ($targetColor == 'gray') {
                $targetColor = '808080';
            }
            $r = round($amountPct * hexdec(substr($targetColor, 0, 2)));
            $g = round($amountPct * hexdec(substr($targetColor, 2, 2)));
            $b = round($amountPct * hexdec(substr($targetColor, 4, 2)));
            if (ImageFilter($gdimg, IMG_FILTER_COLORIZE, $r, $g, $b)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_COLORIZE)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }

        // overridden below for grayscale
        if ($targetColor != 'gray') {
            $TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
            $TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
            $TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));
        }

        for ($x = 0; $x < ImageSX($gdimg); $x++) {
            for ($y = 0; $y < ImageSY($gdimg); $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                if ($targetColor == 'gray') {
                    $TargetPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
                }
                foreach ($TargetPixel as $key => $value) {
                    $NewPixel[$key] = round(max(0, min(255, ($OriginalPixel[$key] * ((100 - $amount) / 100)) + ($TargetPixel[$key] * $amountPct))));
                }
                //$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
                $newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }


    function Crop(&$gdimg, $left=0, $right=0, $top=0, $bottom=0) {
        if (!$left && !$right && !$top && !$bottom) {
            return true;
        }
        $oldW = ImageSX($gdimg);
        $oldH = ImageSY($gdimg);
        if (($left   > 0) && ($left   < 1)) { $left   = round($left   * $oldW); }
        if (($right  > 0) && ($right  < 1)) { $right  = round($right  * $oldW); }
        if (($top    > 0) && ($top    < 1)) { $top    = round($top    * $oldH); }
        if (($bottom > 0) && ($bottom < 1)) { $bottom = round($bottom * $oldH); }
        $right  = min($oldW - $left - 1, $right);
        $bottom = min($oldH - $top  - 1, $bottom);
        $newW = $oldW - $left - $right;
        $newH = $oldH - $top  - $bottom;

        if ($imgCropped = ImageCreateTrueColor($newW, $newH)) {
            ImageCopy($imgCropped, $gdimg, 0, 0, $left, $top, $newW, $newH);
            if ($gdimg = ImageCreateTrueColor($newW, $newH)) {
                ImageCopy($gdimg, $imgCropped, 0, 0, 0, 0, $newW, $newH);
                ImageDestroy($imgCropped);
                return true;
            }
            ImageDestroy($imgCropped);
        }
        return false;
    }


    function Desaturate(&$gdimg, $amount, $color='') {
        if ($amount == 0) {
            return true;
        }
        return phpthumb_filters::Colorize($gdimg, $amount, (phpthumb_functions::IsHexColor($color) ? $color : 'gray'));
    }


    function DropShadow(&$gdimg, $distance, $width, $hexcolor, $angle, $fade) {
        if (phpthumb_functions::gd_version() < 2) {
            return false;
        }
        $distance = ($distance ? $distance : 10);
        $width    = ($width    ? $width    : 10);
        $hexcolor = ($hexcolor ? $hexcolor : '000000');
        $angle    = ($angle    ? $angle    : 225);
        $fade     = ($fade     ? $fade     : 1);

        $width_shadow  = cos(deg2rad($angle)) * ($distance + $width);
        $height_shadow = sin(deg2rad($angle)) * ($distance + $width);

        $scaling = min(ImageSX($gdimg) / (ImageSX($gdimg) + abs($width_shadow)), ImageSY($gdimg) / (ImageSY($gdimg) + abs($height_shadow)));

        for ($i = 0; $i < $width; $i++) {
            $WidthAlpha[$i] = (abs(($width / 2) - $i) / $width) * $fade;
            $Offset['x'] = cos(deg2rad($angle)) * ($distance + $i);
            $Offset['y'] = sin(deg2rad($angle)) * ($distance + $i);
        }

        $tempImageWidth  = ImageSX($gdimg)  + abs($Offset['x']);
        $tempImageHeight = ImageSY($gdimg) + abs($Offset['y']);

        if ($gdimg_dropshadow_temp = phpthumb_functions::ImageCreateFunction($tempImageWidth, $tempImageHeight)) {

            ImageAlphaBlending($gdimg_dropshadow_temp, false);
            ImageSaveAlpha($gdimg_dropshadow_temp, true);
            $transparent1 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, 0, 0, 0, 127);
            ImageFill($gdimg_dropshadow_temp, 0, 0, $transparent1);

            for ($x = 0; $x < ImageSX($gdimg); $x++) {
                for ($y = 0; $y < ImageSY($gdimg); $y++) {
                    $PixelMap[$x][$y] = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                }
            }
            for ($x = 0; $x < $tempImageWidth; $x++) {
                for ($y = 0; $y < $tempImageHeight; $y++) {
                    //for ($i = 0; $i < $width; $i++) {
                    for ($i = 0; $i < 1; $i++) {
                        if (!isset($PixelMap[$x][$y]['alpha']) || ($PixelMap[$x][$y]['alpha'] > 0)) {
                            if (isset($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']) && ($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha'] < 127)) {
                                $thisColor = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor, false, $PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']);
                                ImageSetPixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
                            }
                        }
                    }
                }
            }

            ImageAlphaBlending($gdimg_dropshadow_temp, true);
            for ($x = 0; $x < ImageSX($gdimg); $x++) {
                for ($y = 0; $y < ImageSY($gdimg); $y++) {
                    if ($PixelMap[$x][$y]['alpha'] < 127) {
                        $thisColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, $PixelMap[$x][$y]['red'], $PixelMap[$x][$y]['green'], $PixelMap[$x][$y]['blue'], $PixelMap[$x][$y]['alpha']);
                        ImageSetPixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
                    }
                }
            }

            ImageSaveAlpha($gdimg, true);
            ImageAlphaBlending($gdimg, false);
            //$this->is_alpha = true;
            $transparent2 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0, 0, 0, 127);
            ImageFilledRectangle($gdimg, 0, 0, ImageSX($gdimg), ImageSY($gdimg), $transparent2);
            ImageCopyResampled($gdimg, $gdimg_dropshadow_temp, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg), ImageSX($gdimg_dropshadow_temp), ImageSY($gdimg_dropshadow_temp));

            ImageDestroy($gdimg_dropshadow_temp);
        }
        return true;
    }


    function EdgeDetect(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_EDGEDETECT)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_EDGEDETECT)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        // currently not implemented "the hard way"
        $this->DebugMessage('FAILED: phpthumb_filters::EdgeDetect($gdimg) [function not implemented]', __FILE__, __LINE__);
        return false;
    }


    function Elipse($gdimg) {
        if (phpthumb_functions::gd_version() < 2) {
            return false;
        }
        // generate mask at twice desired resolution and downsample afterwards for easy antialiasing
        if ($gdimg_elipsemask_double = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg) * 2, ImageSY($gdimg) * 2)) {
            if ($gdimg_elipsemask = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {

                $color_transparent = ImageColorAllocate($gdimg_elipsemask_double, 255, 255, 255);
                ImageFilledEllipse($gdimg_elipsemask_double, ImageSX($gdimg), ImageSY($gdimg), (ImageSX($gdimg) - 1) * 2, (ImageSY($gdimg) - 1) * 2, $color_transparent);
                ImageCopyResampled($gdimg_elipsemask, $gdimg_elipsemask_double, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg), ImageSX($gdimg) * 2, ImageSY($gdimg) * 2);

                phpthumb_filters::ApplyMask($gdimg_elipsemask, $gdimg);
                ImageDestroy($gdimg_elipsemask);
                return true;

            } else {
                $this->DebugMessage('$gdimg_elipsemask = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
            }
            ImageDestroy($gdimg_elipsemask_double);
        } else {
            $this->DebugMessage('$gdimg_elipsemask_double = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
        }
        return false;
    }


    function Emboss(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_EMBOSS)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_EMBOSS)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        // currently not implemented "the hard way"
        $this->DebugMessage('FAILED: phpthumb_filters::Emboss($gdimg) [function not implemented]', __FILE__, __LINE__);
        return false;
    }


    function Flip(&$gdimg, $x=false, $y=false) {
        if (!$x && !$y) {
            return false;
        }
        if ($tempImage = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {
            if ($x) {
                ImageCopy($tempImage, $gdimg, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg));
                for ($x = 0; $x < ImageSX($gdimg); $x++) {
                    ImageCopy($gdimg, $tempImage, ImageSX($gdimg) - 1 - $x, 0, $x, 0, 1, ImageSY($gdimg));
                }
            }
            if ($y) {
                ImageCopy($tempImage, $gdimg, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg));
                for ($y = 0; $y < ImageSY($gdimg); $y++) {
                    ImageCopy($gdimg, $tempImage, 0, ImageSY($gdimg) - 1 - $y, 0, $y, ImageSX($gdimg), 1);
                }
            }
            ImageDestroy($tempImage);
        }
        return true;
    }


    function Frame(&$gdimg, $frame_width, $edge_width, $hexcolor_frame, $hexcolor1, $hexcolor2) {
        $frame_width    = ($frame_width    ? $frame_width    : 5);
        $edge_width     = ($edge_width     ? $edge_width     : 1);
        $hexcolor_frame = ($hexcolor_frame ? $hexcolor_frame : 'CCCCCC');
        $hexcolor1      = ($hexcolor1      ? $hexcolor1      : 'FFFFFF');
        $hexcolor2      = ($hexcolor2      ? $hexcolor2      : '000000');

        $color_frame = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor_frame);
        $color1      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1);
        $color2      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2);
        for ($i = 0; $i < $edge_width; $i++) {
            // outer bevel
            ImageLine($gdimg,                   $i,                   $i,                   $i, ImageSY($gdimg) - $i, $color1); // left
            ImageLine($gdimg,                   $i,                   $i, ImageSX($gdimg) - $i,                   $i, $color1); // top
            ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i, ImageSX($gdimg) - $i,                   $i, $color2); // right
            ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i,                   $i, ImageSY($gdimg) - $i, $color2); // bottom
        }
        for ($i = 0; $i < $frame_width; $i++) {
            // actual frame
            ImageRectangle($gdimg, $edge_width + $i, $edge_width + $i, ImageSX($gdimg) - $edge_width - $i, ImageSY($gdimg) - $edge_width - $i, $color_frame);
        }
        for ($i = 0; $i < $edge_width; $i++) {
            // inner bevel
            ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color2); // left
            ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color2); // top
            ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color1); // right
            ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color1); // bottom
        }
        return true;
    }


    function Gamma(&$gdimg, $amount) {
        if (number_format($amount, 4) == '1.0000') {
            return true;
        }
        return ImageGammaCorrect($gdimg, 1.0, $amount);
    }


    function Grayscale(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        return phpthumb_filters::Colorize($gdimg, 100, 'gray');
    }


    function HistogramAnalysis(&$gdimg, $calculateGray=false) {
        $ImageSX = ImageSX($gdimg);
        $ImageSY = ImageSY($gdimg);
        for ($x = 0; $x < $ImageSX; $x++) {
            for ($y = 0; $y < $ImageSY; $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                @$Analysis['red'][$OriginalPixel['red']]++;
                @$Analysis['green'][$OriginalPixel['green']]++;
                @$Analysis['blue'][$OriginalPixel['blue']]++;
                @$Analysis['alpha'][$OriginalPixel['alpha']]++;
                if ($calculateGray) {
                    $GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
                    @$Analysis['gray'][$GrayPixel['red']]++;
                }
            }
        }
        $keys = array('red', 'green', 'blue', 'alpha');
        if ($calculateGray) {
            $keys[] = 'gray';
        }
        foreach ($keys as $dummy => $key) {
            ksort($Analysis[$key]);
        }
        return $Analysis;
    }


    function HistogramStretch(&$gdimg, $band='*', $method=0, $threshold=0.1) {
        // equivalent of "Auto Contrast" in Adobe Photoshop
        // method 0 stretches according to RGB colors. Gives a more conservative stretch.
        // method 1 band stretches according to grayscale which is color-biased (59% green, 30% red, 11% blue). May give a punchier / more aggressive stretch, possibly appearing over-saturated
        $Analysis = phpthumb_filters::HistogramAnalysis($gdimg, true);
        $keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>(($method == 0) ? 'all' : 'gray'));
        $band = substr($band, 0, 1);
        if (!isset($keys[$band])) {
            return false;
        }
        $key = $keys[$band];

        // If the absolute brightest and darkest pixels are used then one random
        // pixel in the image could throw off the whole system. Instead, count up/down
        // from the limit and allow <threshold> (default = 0.1%) of brightest/darkest
        // pixels to be clipped to min/max
        $threshold = floatval($threshold) / 100;
        $clip_threshold = ImageSX($gdimg) * ImageSX($gdimg) * $threshold;
        //if ($min >= 0) {
        //    $range_min = min($min, 255);
        //} else {
            $countsum = 0;
            for ($i = 0; $i <= 255; $i++) {
                if ($method == 0) {
                    $countsum = max(@$Analysis['red'][$i], @$Analysis['green'][$i], @$Analysis['blue'][$i]);
                } else {
                    $countsum += @$Analysis[$key][$i];
                }
                if ($countsum >= $clip_threshold) {
                    $range_min = $i - 1;
                    break;
                }
            }
            $range_min = max($range_min, 0);
        //}
        //if ($max > 0) {
        //    $range_max = max($max, 255);
        //} else {
            $countsum = 0;
            for ($i = 255; $i >= 0; $i--) {
                if ($method == 0) {
                    $countsum = max(@$Analysis['red'][$i], @$Analysis['green'][$i], @$Analysis['blue'][$i]);
                } else {
                    $countsum += @$Analysis[$key][$i];
                }
                if ($countsum >= $clip_threshold) {
                    $range_max = $i + 1;
                    break;
                }
            }
            $range_max = min($range_max, 255);
        //}
        $range_scale = (($range_max == $range_min) ? 1 : (255 / ($range_max - $range_min)));
        if (($range_min == 0) && ($range_max == 255)) {
            // no adjustment neccesary - don't waste CPU time!
            return true;
        }

        $ImageSX = ImageSX($gdimg);
        $ImageSY = ImageSY($gdimg);
        for ($x = 0; $x < $ImageSX; $x++) {
            for ($y = 0; $y < $ImageSY; $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                if ($band == '*') {
                    $new['red']   = min(255, max(0, ($OriginalPixel['red']   - $range_min) * $range_scale));
                    $new['green'] = min(255, max(0, ($OriginalPixel['green'] - $range_min) * $range_scale));
                    $new['blue']  = min(255, max(0, ($OriginalPixel['blue']  - $range_min) * $range_scale));
                    $new['alpha'] = min(255, max(0, ($OriginalPixel['alpha'] - $range_min) * $range_scale));
                } else {
                    $new = $OriginalPixel;
                    $new[$key] = min(255, max(0, ($OriginalPixel[$key] - $range_min) * $range_scale));
                }
                $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $new['red'], $new['green'], $new['blue'], $new['alpha']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }

        return true;
    }


    function HistogramOverlay(&$gdimg, $bands='*', $colors='', $width=0.25, $height=0.25, $alignment='BR', $opacity=50, $margin_x=5, $margin_y=null) {
        $margin_y = (is_null($margin_y) ? $margin_x : $margin_y);

        $Analysis = phpthumb_filters::HistogramAnalysis($gdimg, true);
        $histW = round(($width > 1) ? min($width, ImageSX($gdimg)) : ImageSX($gdimg) * $width);
        $histH = round(($width > 1) ? min($width, ImageSX($gdimg)) : ImageSX($gdimg) * $width);
        if ($gdHist = ImageCreateTrueColor($histW, $histH)) {
            $color_back = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHist, 0, 0, 0, 127);
            ImageFilledRectangle($gdHist, 0, 0, $histW, $histH, $color_back);
            ImageAlphaBlending($gdHist, false);
            ImageSaveAlpha($gdHist, true);

            $HistogramTempWidth  = 256;
            $HistogramTempHeight = 100;
            if ($gdHistTemp = ImageCreateTrueColor($HistogramTempWidth, $HistogramTempHeight)) {
                $color_back_temp = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHistTemp, 255, 0, 255, 127);
                ImageAlphaBlending($gdHistTemp, false);
                ImageSaveAlpha($gdHistTemp, true);
                ImageFilledRectangle($gdHistTemp, 0, 0, ImageSX($gdHistTemp), ImageSY($gdHistTemp), $color_back_temp);

                $DefaultColors = array('r'=>'FF0000', 'g'=>'00FF00', 'b'=>'0000FF', 'a'=>'999999', '*'=>'FFFFFF');
                $Colors = explode(';', $colors);
                $BandsToGraph = array_unique(preg_split('//', $bands));
                $keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>'gray');
                foreach ($BandsToGraph as $key => $band) {
                    if (!isset($keys[$band])) {
                        continue;
                    }
                    $PeakValue = max($Analysis[$keys[$band]]);
                    $thisColor = phpthumb_functions::ImageHexColorAllocate($gdHistTemp, phpthumb_functions::IsHexColor(@$Colors[$key]) ? $Colors[$key] : $DefaultColors[$band]);
                    for ($x = 0; $x < $HistogramTempWidth; $x++) {
                        ImageLine($gdHistTemp, $x, $HistogramTempHeight - 1, $x, $HistogramTempHeight - 1 - round(@$Analysis[$keys[$band]][$x] / $PeakValue * $HistogramTempHeight), $thisColor);
                    }
                    ImageLine($gdHistTemp, 0, $HistogramTempHeight - 1, $HistogramTempWidth - 1, $HistogramTempHeight - 1, $thisColor);
                    ImageLine($gdHistTemp, 0, $HistogramTempHeight - 2, $HistogramTempWidth - 1, $HistogramTempHeight - 2, $thisColor);
                }
                ImageCopyResampled($gdHist, $gdHistTemp, 0, 0, 0, 0, ImageSX($gdHist), ImageSY($gdHist), ImageSX($gdHistTemp), ImageSY($gdHistTemp));
                ImageDestroy($gdHistTemp);
            } else {
                return false;
            }

            phpthumb_filters::WatermarkOverlay($gdimg, $gdHist, $alignment, $opacity, $margin_x, $margin_y);
            ImageDestroy($gdHist);
            return true;
        }
        return false;
    }



    function ImprovedImageRotate(&$gdimg_source, $rotate_angle=0, $config_background_hexcolor='FFFFFF', $bg=null) {
        while ($rotate_angle < 0) {
            $rotate_angle += 360;
        }
        $rotate_angle = $rotate_angle % 360;
        if ($rotate_angle != 0) {

            $background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_source, $config_background_hexcolor);

            if ((phpthumb_functions::gd_version() >= 2) && !$bg && ($rotate_angle % 90)) {

                //$this->DebugMessage('Using alpha rotate', __FILE__, __LINE__);
                if ($gdimg_rotate_mask = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_source), ImageSY($gdimg_source))) {

                    for ($i = 0; $i <= 255; $i++) {
                        $color_mask[$i] = ImageColorAllocate($gdimg_rotate_mask, $i, $i, $i);
                    }
                    ImageFilledRectangle($gdimg_rotate_mask, 0, 0, ImageSX($gdimg_rotate_mask), ImageSY($gdimg_rotate_mask), $color_mask[255]);
                    $imageX = ImageSX($gdimg_source);
                    $imageY = ImageSY($gdimg_source);
                    for ($x = 0; $x < $imageX; $x++) {
                        for ($y = 0; $y < $imageY; $y++) {
                            $pixelcolor = phpthumb_functions::GetPixelColor($gdimg_source, $x, $y);
                            ImageSetPixel($gdimg_rotate_mask, $x, $y, $color_mask[255 - round($pixelcolor['alpha'] * 255 / 127)]);
                        }
                    }
                    $gdimg_rotate_mask  = ImageRotate($gdimg_rotate_mask,  $rotate_angle, $color_mask[0]);
                    $gdimg_source = ImageRotate($gdimg_source, $rotate_angle, $background_color);

                    ImageAlphaBlending($gdimg_source, false);
                    ImageSaveAlpha($gdimg_source, true);
                    //$this->is_alpha = true;
                    $phpThumbFilters = new phpthumb_filters();
                    $phpThumbFilters->phpThumbObject = $this;
                    $phpThumbFilters->ApplyMask($gdimg_rotate_mask, $gdimg_source);

                    ImageDestroy($gdimg_rotate_mask);

                } else {
                    //$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
                }

            } else {

                if (phpthumb_functions::gd_version() < 2) {
                    //$this->DebugMessage('Using non-alpha rotate because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
                } elseif ($bg) {
                    //$this->DebugMessage('Using non-alpha rotate because $this->bg is "'.$bg.'"', __FILE__, __LINE__);
                } elseif ($rotate_angle % 90) {
                    //$this->DebugMessage('Using non-alpha rotate because ($rotate_angle % 90) = "'.($rotate_angle % 90).'"', __FILE__, __LINE__);
                } else {
                    //$this->DebugMessage('Using non-alpha rotate because $this->thumbnailFormat is "'.$this->thumbnailFormat.'"', __FILE__, __LINE__);
                }

                if (ImageColorTransparent($gdimg_source) >= 0) {
                    // ImageRotate() forgets all about an image's transparency and sets the transparent color to black
                    // To compensate, flood-fill the transparent color of the source image with the specified background color first
                    // then rotate and the colors should match

                    if (!function_exists('ImageIsTrueColor') || !ImageIsTrueColor($gdimg_source)) {
                        // convert paletted image to true-color before rotating to prevent nasty aliasing artifacts

                        //$this->source_width  = ImageSX($gdimg_source);
                        //$this->source_height = ImageSY($gdimg_source);
                        $gdimg_newsrc = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_source), ImageSY($gdimg_source));
                        $background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $config_background_hexcolor);
                        ImageFilledRectangle($gdimg_newsrc, 0, 0, ImageSX($gdimg_source), ImageSY($gdimg_source), phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $config_background_hexcolor));
                        ImageCopy($gdimg_newsrc, $gdimg_source, 0, 0, 0, 0, ImageSX($gdimg_source), ImageSY($gdimg_source));
                        ImageDestroy($gdimg_source);
                        unset($gdimg_source);
                        $gdimg_source = $gdimg_newsrc;
                        unset($gdimg_newsrc);

                    } else {

                        ImageColorSet(
                            $gdimg_source,
                            ImageColorTransparent($gdimg_source),
                            hexdec(substr($config_background_hexcolor, 0, 2)),
                            hexdec(substr($config_background_hexcolor, 2, 2)),
                            hexdec(substr($config_background_hexcolor, 4, 2)));

                        ImageColorTransparent($gdimg_source, -1);

                    }
                }

                $gdimg_source = ImageRotate($gdimg_source, $rotate_angle, $background_color);

            }
        }
        return true;
    }


    function MeanRemoval(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_MEAN_REMOVAL)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_MEAN_REMOVAL)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        // currently not implemented "the hard way"
        $this->DebugMessage('FAILED: phpthumb_filters::MeanRemoval($gdimg) [function not implemented]', __FILE__, __LINE__);
        return false;
    }


    function Negative(&$gdimg) {
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_NEGATE)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_NEGATE)', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        $ImageSX = ImageSX($gdimg);
        $ImageSY = ImageSY($gdimg);
        for ($x = 0; $x < $ImageSX; $x++) {
            for ($y = 0; $y < $ImageSY; $y++) {
                $currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, (~$currentPixel['red'] & 0xFF), (~$currentPixel['green'] & 0xFF), (~$currentPixel['blue'] & 0xFF), $currentPixel['alpha']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }


    function RoundedImageCorners(&$gdimg, $radius_x, $radius_y) {
        // generate mask at twice desired resolution and downsample afterwards for easy antialiasing
        // mask is generated as a white double-size elipse on a triple-size black background and copy-paste-resampled
        // onto a correct-size mask image as 4 corners due to errors when the entire mask is resampled at once (gray edges)
        if ($gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction($radius_x * 6, $radius_y * 6)) {
            if ($gdimg_cornermask = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {

                $color_transparent = ImageColorAllocate($gdimg_cornermask_triple, 255, 255, 255);
                ImageFilledEllipse($gdimg_cornermask_triple, $radius_x * 3, $radius_y * 3, $radius_x * 4, $radius_y * 4, $color_transparent);

                ImageFilledRectangle($gdimg_cornermask, 0, 0, ImageSX($gdimg), ImageSY($gdimg), $color_transparent);

                ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0,                           0,     $radius_x,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
                ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0, ImageSY($gdimg) - $radius_y,     $radius_x, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
                ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x, ImageSY($gdimg) - $radius_y, $radius_x * 3, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
                ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x,                           0, $radius_x * 3,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);

                phpthumb_filters::ApplyMask($gdimg_cornermask, $gdimg);
                ImageDestroy($gdimg_cornermask);
//                $this->DebugMessage('RoundedImageCorners('.$radius_x.', '.$radius_y.') succeeded', __FILE__, __LINE__);
                return true;

            } else {
                $this->DebugMessage('FAILED: $gdimg_cornermask = phpthumb_functions::ImageCreateFunction('.ImageSX($gdimg).', '.ImageSY($gdimg).')', __FILE__, __LINE__);
            }
            ImageDestroy($gdimg_cornermask_triple);

        } else {
            $this->DebugMessage('FAILED: $gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction('.($radius_x * 6).', '.($radius_y * 6).')', __FILE__, __LINE__);
        }
        return false;
    }


    function Saturation(&$gdimg, $amount, $color='') {
        if ($amount == 0) {
            return true;
        } elseif ($amount > 0) {
            $amount = 0 - $amount;
        } else {
            $amount = abs($amount);
        }
        return phpthumb_filters::Desaturate($gdimg, $amount, $color);
    }


    function Sepia(&$gdimg, $amount, $targetColor = 'A28065') {
        $amount      = (is_numeric($amount) ? max(0, min(100, $amount)) : 50);
        $amountPct   = $amount / 100;
        $targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'A28065');

        if ($amount == 0) {
            return true;
        }

        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)) {

                $r = round($amountPct * hexdec(substr($targetColor, 0, 2)));
                $g = round($amountPct * hexdec(substr($targetColor, 2, 2)));
                $b = round($amountPct * hexdec(substr($targetColor, 4, 2)));
                if (ImageFilter($gdimg, IMG_FILTER_COLORIZE, $r, $g, $b)) {
                    return true;
                }
                $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_COLORIZE)', __FILE__, __LINE__);
                // fall through and try it the hard way

            } else {

                $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)', __FILE__, __LINE__);
                // fall through and try it the hard way

            }
        }

        $TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
        $TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
        $TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));

        $ImageSX = ImageSX($gdimg);
        $ImageSY = ImageSY($gdimg);
        for ($x = 0; $x < $ImageSX; $x++) {
            for ($y = 0; $y < $ImageSY; $y++) {
                $OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                $GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);

                // http://www.gimpguru.org/Tutorials/SepiaToning/
                // "In the traditional sepia toning process, the tinting occurs most in
                // the mid-tones: the lighter and darker areas appear to be closer to B&W."
                $SepiaAmount = ((128 - abs($GrayPixel['red'] - 128)) / 128) * $amountPct;

                foreach ($TargetPixel as $key => $value) {
                    $NewPixel[$key] = round(max(0, min(255, $GrayPixel[$key] * (1 - $SepiaAmount) + ($TargetPixel[$key] * $SepiaAmount))));
                }
                $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }


    function Smooth(&$gdimg, $amount=6) {
        $amount = min(25, max(0, $amount));
        if ($amount == 0) {
            return true;
        }
        if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
            if (ImageFilter($gdimg, IMG_FILTER_SMOOTH, $amount)) {
                return true;
            }
            $this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_SMOOTH, '.$amount.')', __FILE__, __LINE__);
            // fall through and try it the hard way
        }
        // currently not implemented "the hard way"
        $this->DebugMessage('FAILED: phpthumb_filters::Smooth($gdimg, '.$amount.') [function not implemented]', __FILE__, __LINE__);
        return false;
    }


    function SourceTransparentColorMask(&$gdimg, $hexcolor, $min_limit=5, $max_limit=10) {
        $width  = ImageSX($gdimg);
        $height = ImageSY($gdimg);
        if ($gdimg_mask = ImageCreateTrueColor($width, $height)) {
            $R = hexdec(substr($hexcolor, 0, 2));
            $G = hexdec(substr($hexcolor, 2, 2));
            $B = hexdec(substr($hexcolor, 4, 2));
            $targetPixel = array('red'=>$R, 'green'=>$G, 'blue'=>$B);
            $cutoffRange = $max_limit - $min_limit;
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                    $colorDiff = phpthumb_functions::PixelColorDifferencePercent($currentPixel, $targetPixel);
                    $grayLevel = min($cutoffRange, MAX(0, -$min_limit + $colorDiff)) * (255 / MAX(1, $cutoffRange));
                    $newColor = ImageColorAllocate($gdimg_mask, $grayLevel, $grayLevel, $grayLevel);
                    ImageSetPixel($gdimg_mask, $x, $y, $newColor);
                }
            }
            return $gdimg_mask;
        }
        return false;
    }


    function Threshold(&$gdimg, $cutoff) {
        $width  = ImageSX($gdimg);
        $height = ImageSY($gdimg);
        $cutoff = min(255, max(0, ($cutoff ? $cutoff : 128)));
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                $grayPixel = phpthumb_functions::GrayscalePixel($currentPixel);
                if ($grayPixel['red'] < $cutoff) {
                    $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0x00, 0x00, 0x00, $currentPixel['alpha']);
                } else {
                    $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0xFF, 0xFF, 0xFF, $currentPixel['alpha']);
                }
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }


    function ImageTrueColorToPalette2(&$image, $dither, $ncolors) {
        // http://www.php.net/manual/en/function.imagetruecolortopalette.php
        // zmorris at zsculpt dot com (17-Aug-2004 06:58)
        $width  = ImageSX($image);
        $height = ImageSY($image);
        $image_copy = ImageCreateTrueColor($width, $height);
        //ImageCopyMerge($image_copy, $image, 0, 0, 0, 0, $width, $height, 100);
        ImageCopy($image_copy, $image, 0, 0, 0, 0, $width, $height);
        ImageTrueColorToPalette($image, $dither, $ncolors);
        ImageColorMatch($image_copy, $image);
        ImageDestroy($image_copy);
        return true;
    }

    function ReduceColorDepth(&$gdimg, $colors=256, $dither=true) {
        $colors = max(min($colors, 256), 2);
        // ImageTrueColorToPalette usually makes ugly colors, the replacement is a bit better
        //ImageTrueColorToPalette($gdimg, $dither, $colors);
        phpthumb_filters::ImageTrueColorToPalette2($gdimg, $dither, $colors);
        return true;
    }


    function WhiteBalance(&$gdimg, $targetColor='') {
        if (phpthumb_functions::IsHexColor($targetColor)) {
            $targetPixel = array(
                'red'   => hexdec(substr($targetColor, 0, 2)),
                'green' => hexdec(substr($targetColor, 2, 2)),
                'blue'  => hexdec(substr($targetColor, 4, 2))
            );
        } else {
            $Analysis = phpthumb_filters::HistogramAnalysis($gdimg, false);
            $targetPixel = array(
                'red'   => max(array_keys($Analysis['red'])),
                'green' => max(array_keys($Analysis['green'])),
                'blue'  => max(array_keys($Analysis['blue']))
            );
        }
        $grayValue = phpthumb_functions::GrayscaleValue($targetPixel['red'], $targetPixel['green'], $targetPixel['blue']);
        $scaleR = $grayValue / $targetPixel['red'];
        $scaleG = $grayValue / $targetPixel['green'];
        $scaleB = $grayValue / $targetPixel['blue'];

        for ($x = 0; $x < ImageSX($gdimg); $x++) {
            for ($y = 0; $y < ImageSY($gdimg); $y++) {
                $currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
                $newColor = phpthumb_functions::ImageColorAllocateAlphaSafe(
                    $gdimg,
                    max(0, min(255, round($currentPixel['red']   * $scaleR))),
                    max(0, min(255, round($currentPixel['green'] * $scaleG))),
                    max(0, min(255, round($currentPixel['blue']  * $scaleB))),
                    $currentPixel['alpha']
                );
                ImageSetPixel($gdimg, $x, $y, $newColor);
            }
        }
        return true;
    }

function UnsharpMask($img, $amount, $radius, $threshold)    {  

////////////////////////////////////////////////////////////////////////////////////////////////   
////   
////                  Unsharp Mask for PHP - version 2.1.1   
////   
////    Unsharp mask algorithm by Torstein Hønsi 2003-07.   
////             thoensi_at_netcom_dot_no.   
////               Please leave this notice.   
////   
///////////////////////////////////////////////////////////////////////////////////////////////   



    // $img is an image that is already created within php using  
    // imgcreatetruecolor. No url! $img must be a truecolor image.  

    // Attempt to calibrate the parameters to Photoshop:  
    if ($amount > 500)    $amount = 500;  
    $amount = $amount * 0.016;  
    if ($radius > 50)    $radius = 50;  
    $radius = $radius * 2;  
    if ($threshold > 255)    $threshold = 255;  
      
    $radius = abs(round($radius));     // Only integers make sense.  
    if ($radius == 0) {  
        return $img; imagedestroy($img); break;        }  
    $w = imagesx($img); $h = imagesy($img);  
    $imgCanvas = imagecreatetruecolor($w, $h);  
    $imgBlur = imagecreatetruecolor($w, $h);  
      

    // Gaussian blur matrix:  
    //                          
    //    1    2    1          
    //    2    4    2          
    //    1    2    1          
    //                          
    //////////////////////////////////////////////////  
          

    if (function_exists('imageconvolution')) { // PHP >= 5.1   
            $matrix = array(   
            array( 1, 2, 1 ),   
            array( 2, 4, 2 ),   
            array( 1, 2, 1 )   
        );   
        imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h);  
        imageconvolution($imgBlur, $matrix, 16, 0);   
    }   
    else {   

    // Move copies of the image around one pixel at the time and merge them with weight  
    // according to the matrix. The same matrix is simply repeated for higher radii.  
        for ($i = 0; $i < $radius; $i++)    {  
            imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left  
            imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right  
            imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center  
            imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);  

            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up  
            imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down  
        }  
    }  

    if($threshold>0){  
        // Calculate the difference between the blurred pixels and the original  
        // and set the pixels  
        for ($x = 0; $x < $w-1; $x++)    { // each row 
            for ($y = 0; $y < $h; $y++)    { // each pixel  
                      
                $rgbOrig = ImageColorAt($img, $x, $y);  
                $rOrig = (($rgbOrig >> 16) & 0xFF);  
                $gOrig = (($rgbOrig >> 8) & 0xFF);  
                $bOrig = ($rgbOrig & 0xFF);  
                  
                $rgbBlur = ImageColorAt($imgBlur, $x, $y);  
                  
                $rBlur = (($rgbBlur >> 16) & 0xFF);  
                $gBlur = (($rgbBlur >> 8) & 0xFF);  
                $bBlur = ($rgbBlur & 0xFF);  
                  
                // When the masked pixels differ less from the original  
                // than the threshold specifies, they are set to their original value.  
                $rNew = (abs($rOrig - $rBlur) >= $threshold)   
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))   
                    : $rOrig;  
                $gNew = (abs($gOrig - $gBlur) >= $threshold)   
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))   
                    : $gOrig;  
                $bNew = (abs($bOrig - $bBlur) >= $threshold)   
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))   
                    : $bOrig;  
                  
                  
                              
                if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {  
                        $pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);  
                        ImageSetPixel($img, $x, $y, $pixCol);  
                    }  
            }  
        }  
    }  
    else{  
        for ($x = 0; $x < $w; $x++)    { // each row  
            for ($y = 0; $y < $h; $y++)    { // each pixel  
                $rgbOrig = ImageColorAt($img, $x, $y);  
                $rOrig = (($rgbOrig >> 16) & 0xFF);  
                $gOrig = (($rgbOrig >> 8) & 0xFF);  
                $bOrig = ($rgbOrig & 0xFF);  
                  
                $rgbBlur = ImageColorAt($imgBlur, $x, $y);  
                  
                $rBlur = (($rgbBlur >> 16) & 0xFF);  
                $gBlur = (($rgbBlur >> 8) & 0xFF);  
                $bBlur = ($rgbBlur & 0xFF);  
                  
                $rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;  
                    if($rNew>255){$rNew=255;}  
                    elseif($rNew<0){$rNew=0;}  
                $gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;  
                    if($gNew>255){$gNew=255;}  
                    elseif($gNew<0){$gNew=0;}  
                $bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;  
                    if($bNew>255){$bNew=255;}  
                    elseif($bNew<0){$bNew=0;}  
                $rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;  
                    ImageSetPixel($img, $x, $y, $rgbNew);  
            }  
        }  
    }  
    imagedestroy($imgCanvas);  
    imagedestroy($imgBlur);  
      
    return $img;  

} 


    function DebugMessage($message, $file='', $line='') {
//    	d($message . $file . $line);
        return false;
    }
}

