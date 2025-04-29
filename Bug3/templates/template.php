<!DOCTYPE html>
<html>
<head>
  <title>Discussion Board</title>
</head>
<body>
  <h2>Users:</h2>
  <?php echo "<pre>" . $users . "<pre/>"; ?>
  <h2>Topics:</h2>
  <?php echo "<pre>" . $topics . "<pre/>"; ?>
  <h2>Posts in 'PHP Programming' Topic:</h2>
  <?php foreach($posts as $post): ?>
    <div><?php echo $post['user'] . ": " . $post['content'] . "\n"; ?></div>
  <?php endforeach; ?>
</body>
</html>