<?php

class iOSAppStoreAPIAccessor {
    /**
     * Search App Store for apps.
     * @param  str       $search_term
     * @return array
     */
    public function searchApps($search_term) {
        $endpoint = 'https://itunes.apple.com/search?entity=software&limit=3&term='.urlencode($search_term);
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        $payload = self::getURLContents($endpoint);
        if (isset($payload)) {
            $apps = $this->parseJSONApps($payload);
            return $apps;
        } else {
            return null;
        }
    }
    /**
     * Get the contents of a URL via GET
     * @param str $URL
     * @return str contents
     */
    public static function getURLContents($URL) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        //echo "URL: ".$URL."\n";
        //echo $contents;
        //echo "STATUS: ".$status."\n";
        if (isset($contents)) {
            return $contents;
        } else {
            return null;
        }
    }
    /**
     * Parse JSON list of users
     * @param str $data JSON user info.
     * @return array user data
     */
    public function parseJSONApps($data) {
        $json = JSONDecoder::decode($data);
        $parsed_payload = array();
        //print_r($json);

        //If it's a list of users, set the cursor
        if (isset($json->results)) {
            foreach ($json->results as $result) {
                $parsed_payload[] = self::convertJSONtoAppArray($result);
            }
        }
        return $parsed_payload;
    }
    /**
     * Parse app JSON.
     * @param str $data JSON app info.
     * @return array user data
     */
    public function parseJSONApp($data) {
        $json = JSONDecoder::decode($data);
        //print_r($json);
        if (isset($json)) {
            return self::convertJSONtoAppArray($json);
        }
        return null;
    }
    /**
     * Convert JSON representation of a app to an array.
     * @param str $json_app
     * @return array App values
     */
    private function convertJSONtoAppArray($json_app) {
        $result = array(
            'user_name'       => (string)$json_app->trackName,
            'full_name'       => (string)$json_app->trackName,
            'avatar'          => (string)$json_app->artworkUrl512,
            'description'     => substr((string)$json_app->description, 0, 140)."...",
            'url'             => (string)$json_app->trackViewUrl,
            'network'         => 'ios'
        );
        return $result;
    }
    /**
     * Convert a var whose value is true to 1, else 0.
     * @param bool $bool_val
     * @return int 1 or 0
     */
    private static function boolToInt($bool_val) {
        return ($bool_val) ?1:0;
    }
}