<?php

    $path = $_SERVER['DOCUMENT_ROOT'];

    include_once $path . "/API/Models/Article.php";
    
    class ArticlesController {

        public function __construct() {

        }

        /**
         * 
         * Get all the articles in the database.
         * 
         */
        public static function index() {
            $articles = Article::all();
            $actual_link = "http://$_SERVER[HTTP_HOST]";
            foreach($articles as $article) {
                $article->img_path = $actual_link . $article->img_path;
            }
            echo json_encode($articles);
        }

        /**
         * 
         * Create a new article in the database.
         * 
         * @param $request the request that contains the article informations.
         * 
         */
        public static function create($request) {
            $article_info = $request->getBody();
            $article = new Article();
            $article->title = $article_info['title'];
            $article->body = $article_info['body'];
            $article->date = $article_info['date'];
            
            // get the image
            $image = $article_info['image'];
            $path = $_SERVER['DOCUMENT_ROOT'];
            $upload_dir = $path . "/API/uploads/";
            $upload_image = $upload_dir . "article-" . $article_info['date'] . '-' . hash('md5', $article->body . '**' . $article->title) . '-' . $image['name'];
            // upload the image
            if (move_uploaded_file($image['tmp_name'], $upload_image)) {
                $article->img_path = '/API/uploads/article-' . $article_info['date'] . '-' . hash('md5', $article->body . '**' . $article->title) . '-'. $image['name'];
                $article->save(); 
                echo '[OK]';
            } else {
                echo '[NOT OK]';
            }
        }

        /**
         * 
         * Display an article.
         * 
         */
        public static function show($id_article) {
            $article = Article::find($id_article);
            echo json_encode($article);
        }

        /**
         * 
         * Update the article.
         * 
         */
        public static function update($request) {
            $article_info = $request->getBody();
            $article = Article::find($article_info['id_article']);
            $article->title = $article_info['title'];
            $article->body = $article_info['body'];
            if (isset($article_info['image'])) {
                // remove the previous image
                $path = $_SERVER['DOCUMENT_ROOT'];
                unlink($path . $article->img_path);
                
                // get the image
                $image = $article_info['image'];
                $path = $_SERVER['DOCUMENT_ROOT'];
                $upload_dir = $path . "/API/uploads/";
                $upload_image = $upload_dir . "article-" . $article_info['date'] . '-' . hash('md5', $article->body . '**' . $article->title) . '-' . $image['name'];
                // upload the image
                if (move_uploaded_file($image['tmp_name'], $upload_image)) {
                    $article->img_path = '/API/uploads/article-' . $article_info['date'] . '-' . hash('md5', $article->body . '**' . $article->title) . '-' . $image['name'];
                } else {
                    die('[NOT OK]');
                }
            }
            $article->date = $article_info['date'];
            $article->update();
            echo '[OK]';
        }

        /**
         * 
         * Delete mthe specific article from the database.
         * 
         */
        public static function destroy($id_article) {
            $article = Article::find($id_article);
            $path = $_SERVER['DOCUMENT_ROOT'];
            if ($article->delete()) {
                unlink($path . $article->img_path);
                echo "[OK]";
                return;
            }
            echo "[NOT OK]";
        }
    }

?>