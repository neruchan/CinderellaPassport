<?php
/**
 * PHP Wrapper for Openfire's User Service Plugin
 *
 * @copyright 2009 Brave Gamer LLC
 * @license   GNU General Public License
 * @version   Release 1.0.0
 * @link      http://www.bravegamer.com/tools/ Package Home
 * @since     Class available since release 1.0.0
 *
 * @example   See below
 * <code>
 * <?php
 * $userService = new UserServicePHP('curl', 'http://example.com', 'bigsecret');
 * $response = $userService->query('add', 'kafka', 'drowssap', 'franz', 'franz@kafka.com');
 * ?>
 * </code>
 */
 class UserServicePHP {
    /**
     * The mode to retrieve data. cURL or fopen()
     * @var string
     */
    private $mode = '';

    /**
     * The host location
     * @var string
     */
    public $host = '';

    /**
     * The port OpenFire is Listening on
     * @var int
     */
    public $port = '';

    /**
     * The secret key provided by the User Service Plugin
     * @var string
     */
    public $secret = '';

    /**
     * Initiates the class and sets the varaibles we need to acccess
     * the Openfire server.
     *
     * @param string $host   The Openfire host location
     * @param int    $port   The port OpenFire is listening on
     * @param string $secret The secret key provided by the User Service Plugin
     */
    public function __construct($mode, $host, $secret, $port='9090') {
        $this->mode = $mode;
        $this->host = $host;
        $this->secret = $secret;
        $this->port = $port;
    }

    /**
     * Sends a request to the Openfire server with the set parameters
     *
     * @param string $type     The type of request you are trying to make.
     *                         Possible values: add, delete, or update
     * @param string $username The username you wish to lookup or create for the request
     * @param string $password The password you wish to set for the user
     * @param string $name     The name you wish to set for the user
     * @param string $email    The email you wish to set for the user
     * @param string $groups   The list of groups you wish to set for the user.
     *                         Groups should be comma delimited.
     *
     * @return string $result  The XML response by the Openfire server.
     */
    public function query($type, $username, $password = null, $name = null, $email = null, $groups = null) {
        $url = $this->host . ':' . $this->port
               . '/plugins/userService/userservice?'
               . 'secret=' . $this->secret
               . '&type='. $type
               . '&username=' . $username
               . '&password=' . $password
               . '&name=' . $name
               . '&email=' . $email
               . '&groups=' . $groups;

        if($this->mode == 'curl') {
            $result = $this->mode_curl($url);
        }

        if($this->mode == 'fopen') {
            $result = $this->mode_fopen($url);
        }

        return $result;
    }
    /**
     * A basic cURL helper function
     *
     * @param string $url The url you wish to execute
     * @return $data The data from the cURL execution
     */
    public function mode_curl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function mode_fopen($url) {
        $fopen = fopen($url, 'r');
        $data = fread($fopen, 1024);
        fclose($fopen);

        return $data;
    }

 }

?>