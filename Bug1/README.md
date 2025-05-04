# Bug 1 - Web form not submitting properly

## Summary of the problem.
The form was not submitting to the server via the AJAX request. Additionally, there was a syntax error in the `index.php` file. I also performed some refactoring and improved server-side validation, while ensuring that
the message recieved by the user was green if the form was successfully submitted and red otherwise.

<br>

## Fix #1: Form not Submitting
### Problem:
The first thing I noticed was that the form wasn't submitting. I set a breakpoint on the `POST` route handler, and since the breakpoint never got triggered, it was apparent that something was wrong on the client-side. Upon inspection of the HTML form and the AJAX request, it was immidiately clear that the form element ID was not being properly referenced.
```js
const formData = new FormData(document.getElementById('form'));
```
### Solution:
I fixed the form data initialization to properly reference the form ID.
```js
const formElement = document.getElementById('contactForm');
```

<br>

## Fix #2: Compile Error with PHP array parsing.

### Problem:
In the original `index.php` file, the route handler for the `POST` request was not propery parsing the response body array. Here I leveraged the error that php logged to the gitbash terminal to
find the file & line number of the issue.
```php
$response->getBody()->write("Message sent! Thank you, $data['name'].");
```
### Solution:
I fixed the syntax to correctly parse the array.
```php
$response->getBody()->write("Message sent! Thank you, {$data['name']}.");
```


## Fix #3: Code Refactor.

### Problem: 
The current AJAX request used the JavaScript promise syntax, which is not easy to read and can lead to callback hell.

### Solution
I refactored the AJAX request to use async/await syntax instead to improve readability.

```js
document.getElementById('contactForm').onsubmit = async function(event) 
{
  event.preventDefault();

  const formElement = document.getElementById('contactForm');
  const formData = new FormData(formElement);
  const responseDiv = document.querySelector('#response');

  try {
      const response = await fetch('/submit', {
          method: 'POST',
          body: formData
      });
...
```


## Fix #4: Improved Server Side Validation

### Problem:
The front-end didn't have a good way to know when server-side validation failed. 

### Solution
I made sure to (in addition to the "Add fields required" message), to return a 400 status code when server side validation failed.
```php
// Return a 400 status code and a message if any field is null
if ($data['name'] == "" || $data['email'] == "" || $data['message'] == "") {
  $response->getBody()->write("All fields required.");
  return $response->withStatus(400)->withHeader('Content-Type', 'text/plain');
}
```

## Fix #5: Fixed error Message display color for client-side.

### Proglem: Error messages and success messages displayed to the user were the same color.

### Solution:
As a courtesy to the user, I used the HTTP response code from the form submission to dynamically update the feedback message's color. I achieved this by defining CSS classes for different message styles (e.g., success, error) and applying the appropriate class in the POST callback.

### CSS

```css
#response {
  margin-top: 20px;
}

.error-message {
  color: red;
}

.success-message {
  color: green;
}
```

### JavaScript

```js
if (!response.ok) {
  // Show the red error text
  responseDiv.className = 'error-message';
  const errorMessage = await response.text();
  responseDiv.innerHTML = `Error: ${errorMessage}`;
  return;
}

// Show the green success text
responseDiv.className = 'success-message';
```


<br>

## Conclusion
These fixes address both client-side and server-side issues that were preventing the form from functioning as intended. The result is a more user-friendly, responsive, and robust submission flow with clear validation feedback and improved code readability.
