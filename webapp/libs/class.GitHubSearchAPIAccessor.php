<?php

class GitHubSearchAPIAccessor {
    /**
     * Search GitHub for repositories https://developer.github.com/v3/search/#search-repositories
     * @param  str       $search_term
     * @return array
     */
    public function searchRepos($search_term) {
        $endpoint = 'https://api.github.com/search/repositories?q='.urlencode($search_term);
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        $payload = self::getURLContents($endpoint);
        if (isset($payload)) {
            $apps = $this->parseJSONRepos($payload);
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
        $cfg = Config::getInstance();
        // Use a basic authentication personal token for less rate-limiting
        // https://developer.github.com/v3/search/#rate-limit
        $username = $cfg->getValue('github_username');
        $password = $cfg->getValue('github_personal_access_token');
        // GitHub API requires a user agent
        $chrome_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) '.
            'Chrome/36.0.1944.0 Safari/537.36';

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        curl_setopt($c, CURLOPT_USERAGENT, $chrome_user_agent);
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, $username.":".$password);
        curl_setopt($c, CURLOPT_TIMEOUT, 1); // don't wait more than 1 second
        $contents = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        // echo "URL: ".$URL."\n";
        // echo "AUTH: ".$username.":".$password."\n";
        // echo $contents;
        // echo "STATUS: ".$status."\n";
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
    public function parseJSONRepos($data) {
        $json = JSONDecoder::decode($data);
        $parsed_payload = array();

        //If it's a list of repos, set the cursor
        $total = 3;
        $counter = 0;
        if (isset($json->items)) {
            foreach ($json->items as $item) {
                if ($counter < $total) {
                    $parsed_payload[] = self::convertJSONtoAppArray($item);
                    $counter++;
                }
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
     * @param str $json_repo
     * @return array App values
     */
    private function convertJSONtoAppArray($json_repo) {
        $result = array(
            'user_name'       => (string)$json_repo->name,
            'full_name'       => (string)$json_repo->name,
            'avatar'          => 'https://github.com/fluidicon.png',
            'description'     => substr((string)$json_repo->description, 0, 140),
            'url'             => (string)$json_repo->url,
            'network'         => 'github'
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