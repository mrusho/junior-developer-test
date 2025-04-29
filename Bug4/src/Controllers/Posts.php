<?php

namespace App\Controllers;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Database\DBConnection;

final class Posts {
  private $renderer;
  private $posts = [];

  public function __construct()
  {
    $this->renderer = new PhpRenderer('../templates');
  }

  public function landingPage(Request $request, Response $response): Response
  {
    return $this->renderer->render($response, 'template.php');
  }

  public function fetchPosts(Request $request, Response $response): Response
  {
    try {
      $pdo = new DBConnection();
      $pdo = $pdo->dbConnect();

      // Fetch posts with comments
      $query = "SELECT posts.id AS post_id, posts.content AS post_content, posts.image_url, posts.created_at AS post_created_at,
      users.username AS post_username, comments.id AS comment_id, comments.comment_text, comments.created_at AS comment_created_at,
      comment_users.username AS comment_username
      FROM posts
      LEFT JOIN users ON posts.user_id = users.id
      LEFT JOIN comments ON posts.id = comments.post_id
      LEFT JOIN users AS comment_users ON comments.user_id = comment_users.id
      ORDER BY posts.created_at DESC";

      $statement = $pdo->prepare($query);
      $statement->execute();
      $posts = $statement->fetchAll(\PDO::FETCH_ASSOC);

      // Return payload
      $payload = json_encode($posts);
      $response->getBody()->write($payload);
      
      return $response
        ->withHeader('Content-type','application/json')
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:9000');
      
    } catch (PDOException $e){
      $response->getBody()->write($e->getMessage());
    }
  }
}


?>