// Use Async/Await for this AJAX form submission, which is cleaner than the JS Promises syntax
document.getElementById('contactForm').onsubmit = async function(event) 
{
  event.preventDefault();

  // use the correct id to get the form element
  const formElement = document.getElementById('contactForm');
  const formData = new FormData(formElement);
  const responseDiv = document.querySelector('#response');

  try {
      const response = await fetch('/submit', {
          method: 'POST',
          body: formData
      });
    
      if (!response.ok) {      
          // Show the red error text 
          responseDiv.className = 'error-message'; 
          const errorMessage = await response.text();
          responseDiv.innerHTML = `Error: ${errorMessage}`;
          return;
      }

      // Show the green success text
      responseDiv.className = 'success-message';

      const data = await response.text();
      responseDiv.innerHTML = data;

  } catch (error) {
      console.error("Fetch error:", error);
      responseDiv.innerHTML = 'An error occurred.';
  }
};