# Bug 3 - Improved Discussion Board Error Handling and Type Safety

## Summary of the problem:
The code here had a number of issues that I addressed. The return types weren't consistant and function params types were not being explicitally specified. I also ensured that  `\n` characters were being 
interpreted and that we checked for already existing emails and usernames and sanitized input by trimming whitespace. Finally, including the use of an associative array for `topics` is a place where performance can be improved. 

<br>

## Fix #1: User registration validation.

### Problem:
There was no check for existing username or email prior to registering a user, leading to the possability of inadvertently overriding an existing user.

### Solution: 
Verify that the username and email of the user being registered doesn't already exist in the `registerUser()` function.

```php
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
```

<br>

## Fix #2: Input validation

### Problem
While the existing code checked for empty strings, it didn't prevent a user from inputing a string that contained only whitespaces.

### Solution
Sanatize the input by removing all leading and trailing whitespaces. For example, I added the following line of code to the `registerUser()` function.
```php
$username = trim($username);
$email = trim($email);
```
<br>

## Fix #3: Return types and type declarations.

### Problem
The code contains no type hinting or explicit type declarations making it error prone and lacking in clarity.

### Solution
I ensured that all funtions declared their parameter types and return type. For example...
```php
public function registerUser(string $username, string $email) : bool
  {
...
```

Additionally, I ensured that `getPostsByTopic()` had a consistant return type and returned an empty array as its falure condition.

```php
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
```

<br>

## Fix #4: Performance improvement arrays.

### Problem. 
The `topics[]` array was indexed, meaning we would have to iterate over it to find a topic.

### Solution.
Chainging it to an associative array allows us to lookup topics in (nearly) constant time. For example I refactored the `createPost()` function to take advantage of this.

```php
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
```

<br>

## Fix #5: Logging error messages with newline.

### Problem
The `echo` statements used single quotes, resulting in `\n` being interperated as a string literal.

### Solution
I fixed all the echo statements to use double quotes to properly interperate the new line. For example...

```php
echo "Error: User does not exist.\n";
```

<br>

## Conclusion
This set of fixes addresses several critical issues in the discussion board code, significantly improving its robustness, reliability, and performance. By implementing user registration validation, sanitizing inputs, enforcing type safety, optimizing array lookups, and ensuring proper error message formatting, the code is now more maintainable, less prone to errors, and better equipped to handle real-world usage scenarios.
