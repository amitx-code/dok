<?php

    namespace App\Libs;

    use Illuminate\Support\Facades\Config;

    class Images {
        public $src = false;
        public $srcParams = false;
        protected $deleteAfterProcessing = false;
        protected $docRoot = false;
        protected $convert = false;


        public function __construct() {
            $this->docRoot = public_path();
            $this->convert = Config::get('imageprocessor.convert');
        }


        public function __destruct() {
            if ($this->deleteAfterProcessing) {
                if ($this->src) {
                    if (file_exists($this->src)) {
                        unlink($this->src);
                    }
                }
            }
        }


        public function Convert($dst, $dimensions, $cropSize = false) {
            if (empty($this->src)) return false;

            $convertParams = array();
            $sharpen = "1.0x0.5+1.0+0.10";

            if ($this->srcParams[2] == IMAGETYPE_GIF) {
                $convertParams[] = ' -coalesce -set colorspace RGB ';
                $animationSuffix = ' -layers OptimizeFrame "gif:';

            } else {
                $convertParams[] = ' -set colorspace RGB ';
                $animationSuffix = '';
            }

            if (!empty($cropSize)) {
                $convertParams[] = "-crop " . round($cropSize[2]) . 'x' . round($cropSize[3]) . '+' . round($cropSize[0]) . '+' . round($cropSize[1]);
                $convertParams[] = "+repage";

                $srcWidth = $cropSize[2];
                $srcHeight = $cropSize[3];
            } else {
                $srcWidth = $this->srcParams[0];
                $srcHeight = $this->srcParams[1];
            }

            if (strpos($dst, $this->docRoot) !== 0) {
                $dst = $this->docRoot . $dst;
            }

            $dir = dirname($dst);
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
                chmod($dir, 0775);
                if (!file_exists($dir)) {
                    return false;
                }
            }


            if (preg_match('#([0-9]+)b([0-9]+)#', $dimensions)) {
                list ($maxWidth, $maxHeight) = explode('b', $dimensions);

                if ($srcWidth <= $maxWidth && $srcHeight <= $maxHeight) {
                    //copy($this->src, $dst);
                    // return true;

                } else if (($srcWidth / $maxWidth) < ($srcHeight / $maxHeight)) {
                    // Сжатие по ширине больше, надо резать по высоте
                    $x = round(((($srcWidth * $maxHeight) / $srcHeight) - $maxWidth) / 2);
                    $convertParams[] = '-scale x' . $maxHeight;
                    // $convertParams[] = "-crop ${maxWidth}x${maxHeight}+$x+0";

                } else {
                    // Сжатие по высоте больше, надо резать по ширине
                    $y = round(((($srcHeight * $maxWidth) / $srcWidth) - $maxHeight) / 2);
                    if ($srcHeight > $srcWidth && $maxWidth > $maxHeight) {
                        $y = $y / 3;
                    }

                    $convertParams[] = '-scale ' . $maxWidth . 'x';
                    // $convertParams[] = "-crop ${newWidth}x${newHeight}+0+$y";
                }

            } else if (preg_match('#([0-9]+)m([0-9]+)#', $dimensions)) {
                list ($newWidth, $maxHeight) = explode('m', $dimensions);

                //$newHeight = $newWidth * $srcHeight / $srcWidth;
                $convertParams[] = '-scale ' . $newWidth . 'x';
                $convertParams[] = '+repage';
                $convertParams[] = '-crop ${newWidth}x${maxHeight}+0+0';

            } else {
                if ($this->srcParams[0] == 0 || $this->srcParams[1] == 0) return false;
                list ($newWidth, $newHeight) = array_pad(explode('x', $dimensions), 2, false);

                if (!$newHeight) {
                    $convertParams[] = '-scale ' . $newWidth . 'x';

                } else if (!$newWidth) {
                    $convertParams[] = '-scale x' . $newHeight;

                } else {
                    if ($srcWidth == $newWidth && $srcHeight == $newHeight) {
                        copy($this->src, $dst); // CHECK
                        return true;

                    } else if (($srcWidth / $newWidth) > ($srcHeight / $newHeight)) {
                        // Сжатие по ширине больше, надо резать по высоте
                        $x = round(((($srcWidth * $newHeight) / $srcHeight) - $newWidth) / 2);
                        $convertParams[] = '-scale x' . $newHeight;
                        $convertParams[] = "-crop ${newWidth}x${newHeight}+$x+0";

                    } else {
                        // Сжатие по высоте больше, надо резать по ширине
                        $y = round(((($srcHeight * $newWidth) / $srcWidth) - $newHeight) / 2);
                        if ($srcHeight > $srcWidth && $newWidth > $newHeight) {
                            $y = $y / 3;
                        }

                        $convertParams[] = '-scale ' . $newWidth . 'x';
                        $convertParams[] = "-crop ${newWidth}x${newHeight}+0+$y";
                    }
                }
            }

            if ($sharpen) $convertParams[] = "-unsharp $sharpen";
            $convertParams[] = "+repage";
            $convertParams[] = '+profile \'*\'';
            $convertParams[] = '-quality 91';



            $execStr = $this->convert . ' ' . escapeshellarg($this->src) . ' ' . join(' ', $convertParams) . ' ' . $animationSuffix . escapeshellarg($dst);
            exec($execStr);

            return true;
        }


        public function Store($dst) {
            if (strpos($dst, $this->docRoot) !== 0) {
                $dst = $this->docRoot . $dst;
            }

            $dir = dirname($dst);
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
                if (!file_exists($dir)) return false;
                chmod($dir, 0775);
            }

            copy($this->src, $dst);
            if (!file_exists($dst)) return false;

            return true;
        }


        public function Source($src, $localOnly = false) {
            if (empty($src)) {
                return false;
            }

            if ($localOnly) {
                if (file_exists($this->docRoot . $src)) {
                    $this->src = $this->docRoot . $src;

                } else {
                    return false;
                }

            } else if (file_exists($src)) {
                $this->src = $src;

            } else if (file_exists($this->docRoot . $src)) {
                $this->src = $this->docRoot . $src;

            } else if (strpos($src, 'http:/') === 0 || strpos($src, 'https:/') === 0 || strpos($src, 'ftp:/') === 0) {
                $data = file_get_contents($src);
                if ($data) {
                    $this->src = $this->docRoot . '/tmp/' . md5(uniqid(time())) . '.jpg';
                    file_put_contents($this->src, $data);
                    $this->deleteAfterProcessing = true;

                } else {
                    return false;
                }

            } else {
                return false;
            }

            $this->srcParams = getimagesize($this->src);
            if (!$this->srcParams) {
                $this->src = false;
            }

            return true;
        }



    }