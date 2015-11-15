<?php
require_once 'extlibs/isosceles/libs/class.Loader.php';
Loader::register(array(
// dirname(__FILE__).'/libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/'
));

/**
 * Image proxy and cache
 *
 * Accepts 3 $_GET parameters:
 * t (type) = either 'p' or 'm' (product or maker)
 * url = valid URL
 * s (signature) = a token proving the loader is legit
 *
 * v 1.0
 * If request has a valid signature and is for an avatar:
 *     Retrieve image from filesystem
 *     If it exists, return it
 *     If it does not exist, request URL
 *     If URL is 200, save to file system and return file
 *     If URL is 404, return blank image
 * else redirect to the url
 * else redirect to the url
 *
 * Filesystem store: md5($url)
 */

class ImageProxyCacheController extends Controller {
    /**
     * URL of image getting cached and displayed
     * @var str
     */
    var $url;
    /**
     * Local filename of the image being cached and displayed.
     * @var str
     */
    var $local_filename;

    public function control() {
        if ($this->isValidRequest()) {
            $this->url = $_GET['url'];
            $parsed_url = parse_url($this->url);

            //Get the path
            $url_path = $parsed_url['path'];
            //Get the file extension
            $extension = pathinfo($url_path, PATHINFO_EXTENSION);
            //Construct the local cache filename
            $this->local_filename = $this->getLocalFilename($this->url, $extension);

            if (isset($parsed_url['host']) &&
                ($parsed_url['host'] == 'makerbase.co' || $parsed_url['host'] == $_SERVER['SERVER_NAME'])) {
                $this->redirect($this->url);
            } elseif (file_exists($this->local_filename)) {
                $type = 'image/'.$extension;
                header('Content-Type:'.$type);
                header('Content-Length: ' . filesize($this->local_filename));
                readfile($this->local_filename);
            } else {
                if ($_GET['t'] == 'm' || $_GET['t'] == 'u') {
                    $blank_image = (FileDataManager::getDataPath()).'image-cache/blank-maker.png';
                } elseif ($_GET['t'] == 'p') {
                    $blank_image = (FileDataManager::getDataPath()).'image-cache/blank-project.png';
                }
                $this->cacheAndDisplayImage($this->url, $this->local_filename, $blank_image, $extension);
            }
        } else {
            $this->redirect($this->url);
        }
    }
    /**
     * Check if the request for an image is valid - has all required parameters and a valid signature.
     * @return bool
     */
    public function isValidRequest() {
        $cfg = Config::getInstance();
        $passphrase = $cfg->getValue('image_proxy_passphrase');

        return (
            isset($_GET['url'])
            //image type
            && (isset($_GET['t']) && ($_GET['t'] == 'p' || $_GET['t'] == 'm' || $_GET['t'] == 'u'))
            //request signature is a simple MD5 hash of a passphrase
            && (isset($_GET['s']) && $_GET['s'] == md5($passphrase))
        );
    }
    /**
     * Determine the full path and filename for a given URL.
     * @param  str $url
     * @param  str $extension File extension of image
     * @return str Full local file path
     */
    public function getLocalFilename($url, $extension) {
        $data_path = FileDataManager::getDataPath();
        $local_file = $data_path.'image-cache/'.(md5($url).'.'.$extension);
        return $local_file;
    }
    /**
     * Cache and display image. If image exists on filesystem, retrieve. If not, call the URL and save it to filesystem
     * or return blank image if not a 200.
     * @param  str $url
     * @param  str $local_filename
     * @param  str $blank
     * @param  str $extension
     * @return void
     */
    public function cacheAndDisplayImage($url, $local_filename, $blank, $extension){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $raw = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         echo "<pre>";
//         print_r($url);
//         echo "
// ";
//         print_r($status);
//         echo "
// ";
//         print_r($raw);
//         echo "
// ";
        curl_close ($ch);
        if ($status == 200) {
            if (file_exists($local_filename)){
                unlink($local_filename);
            }
            $fp = fopen($local_filename,'x');
            $file = $local_filename;
            $type = 'image/'.$extension;
            if (fwrite($fp, $raw) === false) {
                //@TODO throw an Exception
                $file = $blank;
                $type = 'image/png';
            }
            fclose($fp);
        } else {
            $file = $blank;
            $type = 'image/png';
        }
//        echo $file;
        header('Content-Type:'.$type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
    /**
     * Send Location header
     * @param str $destination
     * @return bool Whether or not redirect header was sent
     */
    protected function redirect($destination=null) {
        if (!isset($destination)) {
            $destination = Config::getInstance()->getValue('site_root_path  ');
        }
        $this->redirect_destination = $destination; //for validation
        if ( !headers_sent() ) {
            header('Location: '.$destination);
            return true;
        } else {
            return false;
        }
    }
}

$controller = new ImageProxyCacheController();
echo $controller->control();
