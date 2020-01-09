<?php

    class ConnexionDB {

        private static $dbhost = 'localhost:8889'; // database host:port
        private static $dbuser = 'root'; // username
        private static $dbpass = 'root'; // user password
        private static $conn = null; // connexion handler
        
        /**
         * Connect to the mysql server, if the connexion has established, then we just return the connexion handler.
         * 
         * 
         * @return self::$conn|null The established connexion, or null if the connexion failed 
         */
        public static function get_connexion() {

            self::$conn = mysqli_connect(self::$dbhost, self::$dbuser, self::$dbpass);
            if(! self::$conn ) {
                die('Could not connect: ' .  mysqli_connect_error());
            }

            return self::$conn;
        }

        /**
         * Close the established connexion
         * 
         */
        public static function close_connexion() {
            mysqli_close(self::$conn);
        }

    }

?>