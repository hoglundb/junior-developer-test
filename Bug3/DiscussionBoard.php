<?php

final class DiscussionBoard {
  private $renderer;
  private array $users = [];
  private array $topics = [];
  private array $posts = [];

  public function registerUser(string $username, string $email) : bool
  {
    $username = trim($username);
    $email = trim($email);

    if (empty($username) || empty($email)) {
      echo "Error: Username and email cannot be empty.\n";
      return false;
    }

    // Verify username is not taken.
    if (isset($this->users[$username])) {
      echo "Error: Username '$username' is already taken. Don't be a copycat.\n";
      return false;
    }

    // Varify email not taken
    foreach ($this->users as $existingUsername => $existingEmail) {
        if ($existingEmail === $email) {
          echo "Error: Email '$email' is already registered to another user.\n";
          return false;
        }
      }

    $this->users[$username] = $email;
    return true;
  }

 // Create a new topic, updated to use an associative array for topics
 public function createTopic(string $topicName) : bool
 {
    $topicName = trim($topicName);

    if (empty($topicName)) {
      echo "Error: Topic name cannot be empty.\n";
      return false;
   }
   $this->topics[$topicName] = true;
   return true;
 }

  // Function to create a post
  public function createPost(string $username, string $topic, string $content) : bool
  {
    $username = trim($username);
    $topic = trim($topic);
    $content = trim($content);

    if (!isset($this->users[$username])) {
      echo "Error: User does not exist.\n";
      return false;
    }
    if (empty($content) || !isset($this->topics[$topic])) {
      echo "Error: Invalid topic or content.\n";
      return false;
    }

    $this->posts[] = ['user' => $username, 'topic' => $topic, 'content' => $content];
    return true;
  }

  // Function to get posts under a topic.
  public function getPostsByTopic(string $topic) : array
  {
    $topic = trim($topic);

    if (!isset($this->topics[$topic])) { 
      echo "Error: Topic not found.\n";
      return []; 
    }

    $topicPosts = [];
    foreach ($this->posts as $post) {
      if ($post['topic'] == $topic) {
        $topicPosts[] = $post;
      }
    }
    return $topicPosts;
  }

  // Function to view all users
  public function getAllUsers() : array
  {
    return $this->users;
  }
}

?>