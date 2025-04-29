<?php

namespace App\Controllers;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DiscussionBoard {
  private $renderer;
  private $users = [];
  private $topics = [];
  private $posts = [];

  public function __construct()
  {
    $this->renderer = new PhpRenderer('../templates');
  }

  // Function to register users
  public function registerUser($username, $email) 
  {
    if (empty($username) || empty($email)) {
      echo 'Error: Username and email cannot be empty.\n';
      return false;
    }
    $this->users[$username] = $email;
    return true;
  }

  // Function to create a new topic
  public function createTopic($topicName) 
  {
    if (empty($topicName)) {
      echo 'Error: Topic name cannot be empty.\n';
      return false;
    }
    $this->topics[] = $topicName;
    return true;
  }

  // Function to create a post
  public function createPost($username, $topic, $content) 
  {
    if (!isset($this->users[$username])) {
      echo 'Error: User does not exist.\n';
      return false;
    }
    if (empty($content) || !in_array($topic, $this->topics)) {
      echo 'Error: Invalid topic or content.\n';
      return false;
    }

    $this->posts[] = ['user' => $username, 'topic' => $topic, 'content' => $content];
    return true;
  }

  // Function to get posts under a topic
  public function getPostsByTopic($topic) 
  {
    if (!in_array($topic, $this->topics)) {
      echo 'Error: Topic not found.\n';
      return false;
    }

    $topicPosts = [];
    foreach ($this->posts as $post) {
      if($post['topic'] == $topic) {
        $topicPosts[] = $post;
      }
    }
    return $topicPosts;
  }

  // Function to view all users
  public function getAllUsers() 
  {
    return $this->users;
  }

  // Example workflow
  public function testExample(Request $request, Response $response): Response
  {
    $this->registerUser('john_doe', 'john@example.com');
    $this->createTopic('PHP Programming');
    $this->createPost('john_doe', 'PHP Programming', 'This is my first post about PHP!');
    $this->createPost('john_doe', 'JavaScript Programming", "This is about JS, but no such topic exists.');
    $posts = $this->getPostsByTopic('PHP Programming');
    
    $viewData = [
      'users' => json_encode($this->users, JSON_PRETTY_PRINT),
      'topics' => json_encode($this->topics, JSON_PRETTY_PRINT),
      'posts' => $posts,
    ];
  
    return $this->renderer->render($response, 'template.php', $viewData);
  }
}


?>