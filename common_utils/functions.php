<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// A Salt value for hashing
define("salt", "li:1or9_em8ip(s6um");

/**
 * Utility class for common functionality amongst classes.
 *
 * @author Woody Romelus
 */
class Utils {

    /**
     * Logs messages.
     * The default location of the log file is: "romelus_debug.log"
     * If the file does not already exist, the file will be created.
     *
     * @param $message the message to log
     * @param string $logFilePath location where log file should exist
     */
    public static function logMessage($message, $logFilePath = "romelus_debug.log") {
        if (!file_exists($logFilePath)) {
            fopen($logFilePath, "a");
            fclose($logFilePath);
        }
        error_log(date('Y/m/d h:i:s A') . " :: $message" . PHP_EOL, 3, $logFilePath);
    }

    /**
     * Redirects the HTTP header to another location.
     *
     * @param $address the address to redirect to
     */
    public static function redirect($address) {
        header("Location: $address");
        exit();
    }

    /**
     * Prints out code content, in a pretty format.
     *
     * @param $content the text to print
     */
    public static function printCode($content) {
        echo "<pre>$content</pre>";
    }

    /**
     * Hashes a password.
     *
     * @param $pass the password to hash
     * @return string the hashed password
     */
    public static function hashPassword($pass) {
        return hash("sha1", strtolower($pass) . salt);
    }

    /**
     *  Generates a unique key.
     *
     * @param $content the text used to create a unique key with
     * @return string the unique key
     */
    public static function generateUniqueKey($content) {
        return md5($content . "_" . uniqid() . "_" . salt);
    }

    /**
     * Normalizes string types into a standard format.
     *
     * @param $text the string to normalize
     * @return string the normalized string
     */
    public static function normalize($text) {
        return htmlspecialchars(trim(strtolower($text)));
    }

    /**
     * Compares two strings for equality, ignoring case.
     *
     * @param $str1 string to compare
     * @param $str2 string to compare
     * @return bool flag indicating equality check
     */
    public static function equalIgnoreCase($str1, $str2) {
        return strcasecmp($str1, $str2) == 0;
    }

    /**
     * Retrieves the parameters from a URL Request.
     *
     * @return mixed the additional info part of the requested URL
     */
    public static function retrieveRequestInfo() {
        parse_str(urldecode(parse_url($_SERVER["REQUEST_URI"])["query"]), $result);
        $result["method"] = $_SERVER['REQUEST_METHOD'];
        $result["uri"] = $_SERVER["REQUEST_URI"];
        return $result;
    }

    /**
     * Creates an HTML error for display.
     * @param $msg the error message to display
     * @return string the error string
     */
    public static function generateUIError($msg) {
        $retVal = "";
        if ($msg !== null && strlen($msg) > 0) {
            $retVal = "<h2 class='message clear'>Please fix the below error:
                <span class='errmsg'>$msg</span><span class='msg_indicator'>
                <i class='icon-li icon-minus-sign errorNotice'></i></span></h2>";
        }
        return $retVal;
    }

    /**
     * Replaces all occurrences of a token string with a replacement string.
     * @param $token the token being searched for
     * @param $replace the replacement string array
     * @param $subject the string being searched on
     */
    public static function replaceTokens($token, $replace, $subject) {
        $retVal = $subject;
        for($i = 0; $i < count($replace); $i++) {
            $retVal = preg_replace("/$token/", $replace[$i], $retVal, 1);
        }
        return $retVal;
    }
}