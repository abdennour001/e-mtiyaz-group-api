<?php

    $path = $_SERVER['DOCUMENT_ROOT'];

    include_once $path . '/API/Database/ConnexionDB.php';

    class Article {

        public $id_article;
        public $title;
        public $body;
        public $img_path;
        public $date;

        public function __construct($id_article = null, $title = null, $body = null, $img_path = null, $date = null) {
            $this->id_article = $id_article;
            $this->title = $title;
            $this->body = $body;
            $this->img_path = $img_path;
            $this->date = $date;
        }

        /**
         * 
         * Get all the articles in the database, return empty array [] if there are no articles.
         * 
         * @return array[Article]
         */
        public static function all() {
            $conn = ConnexionDB::get_connexion();

            $sql = 'SELECT id_article, title, body, img_path, date FROM article';
            mysqli_select_db($conn, 'e_mtiyaz_group');
            $retval = mysqli_query( $conn, $sql );
            
            if(! $retval ) {
               die('Could not get data: ' . mysqli_connect_error());
            }

            $result = array();
        
            while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
                $row_array['id_article'] = $row['id_article'];
                $row_array['title'] = $row['title'];
                $row_array['body'] = $row['body'];
                $row_array['img_path'] = $row['img_path'];
                $row_array['date'] = $row['date'];

                $article = new Article($row_array['id_article'], $row_array['title'], $row_array['body'], $row_array['img_path'], $row_array['date']);
                //push the values in the array  
                array_push($result,$article);
             } 

            mysqli_free_result($retval);
            ConnexionDB::close_connexion();

            return $result;
        }

        /**
         * 
         * Find an article by his id_article, null is returned if the article does not exist in the database.
         * 
         * @param int $id_article
         * 
         * @return Article
         */
        public static function find($id_article) {
            $conn = ConnexionDB::get_connexion();

            $sql = 'SELECT id_article, title, body, img_path, date FROM article WHERE id_article = ' . $id_article;
            mysqli_select_db($conn, 'e_mtiyaz_group');
            $retval = mysqli_query( $conn, $sql );
            
            if(! $retval ) {
               die('Could not get data: ' . mysqli_connect_error());
            }

            $article = null;
        
            if($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
                $row_array['id_article'] = $row['id_article'];
                $row_array['title'] = $row['title'];
                $row_array['body'] = $row['body'];
                $row_array['img_path'] = $row['img_path'];
                $row_array['date'] = $row['date'];

                $article = new Article($row_array['id_article'], $row_array['title'], $row_array['body'], $row_array['img_path'], $row_array['date']);
             } 

            mysqli_free_result($retval);
            ConnexionDB::close_connexion();

            return $article ? $article : null;
        }

        /**
         * 
         * Save this article to the database.
         * 
         */
        public function save() {
            $conn = ConnexionDB::get_connexion();

            $sql = "INSERT INTO article ".
            "(title, body, img_path, date) "."VALUES ".
            "('".$this->title."','".$this->body."','".$this->img_path."', '".$this->date."')";

            mysqli_select_db($conn, 'e_mtiyaz_group');
            $retval = mysqli_query( $conn, $sql );
            
            if(! $retval ) {
                die('Could not enter data: ' . mysqli_errno($conn));
                return false;
            }

            ConnexionDB::close_connexion(); 
            return true;  
        }

        /**
         * 
         * Delete this article from the database.
         * 
         */
        public function delete() {
            $conn = ConnexionDB::get_connexion();

            $sql = 'DELETE FROM article WHERE id_article = ' . $this->id_article;

            mysqli_select_db($conn, 'e_mtiyaz_group');
            $retval = mysqli_query( $conn, $sql );

            if(! $retval ) {
                die('Could not delete data: ' . mysqli_connect_error());
                return false;
            }

            ConnexionDB::close_connexion();  
            return true;
        }

        /**
         * 
         * Update this article in the database.
         * 
         */
        public function update() {
            $conn = ConnexionDB::get_connexion();

            $sql = "UPDATE article
            SET title='".$this->title."', body='".$this->body."', img_path='".$this->img_path."', date='".$this->date."'
            WHERE id_article = " . $this->id_article;

            mysqli_select_db($conn, 'e_mtiyaz_group');
            $retval = mysqli_query( $conn, $sql );

            if(! $retval ) {
                die('Could not update data: ' . mysqli_connect_error());
                return false;
            }

            ConnexionDB::close_connexion();  
            return true;
        }
    }
    
?>