# Bug 2 - Web form not submitting properly

## Summary of the problem:
The web page was not being properly populated with the list of users. The crux of the issue was that their was a mistmatch in property names between the API response and what the application was expecting when displaying the data. Additionally the error handling had some room for improvement, as did the loosely typed nature of the TypeScript code which I refactored. Finally I also fixed an issue where the API was being called continuously, instead of simply being called when a `Get` request was made.

<br>

## Fix #1: External API call Redundancy

### Problem: 
First I verified that the API being consumed by the application was working by pasting the URL into my browser. The API was functioning just fine, but then after logging the response to 
the broswer console, I saw that the API was being called continously. 

### Solution:
Being fairly new to REACT (I am mostly familiar with Vue), I first ran it through ChatGPT to get a high level overview of what the code did. I noticed after some brief research that our `fetchUsers()` method was being called continously. I introduced an empty dependancy array, ensuring it only runs when the component mounts (i.e. when the `GET` request is made).

```tsx
  useEffect(() => {
    fetchUsers();
  }, []);

```
<br>

## Fix #2: Data not being displayed to the user.

### Problem:
The list of users was not being rendered, even though the API response data was fully populated.

### Solution: 
I logged the data returned by the API to the browser's console to inspect it. Looking at the data properties I noticed that our application expected a field called `fullName`, while the data
contained a `name` property. I fixed the JSX expression to use the correct property.

```tsx
 <ul>
    {users.map((user: any) => (
      // Use the correct properties from the API response.
       <li key={user.id}>{user.name}</li>
      ))}
</ul>
```

<br>

## Fix #3: Making appliation more strongly typed.

### Problem:
Having fixed the bug dealing with the data properties mistmatch, it was apparent that we needed to make the code a bit more strongly typed so that things are error prone in the future.

### Solution
I improved readability and type safety by defining the `User` properties. I then ensured state variables were strongly typed to ensure data consistancy. 
This is after all, the benifit of using `TypeScript`.

```tsx
interface User {
  id: number;
  name: string;
}

...


// Apply strong typing to make more robust
const [users, setUsers] = useState<User[]>([]);
const [loading, setLoading] = useState<boolean>(true);
```

<br>


## Fix #4: API Response Error Handling

### Problem:
We needed a more robust way to handle potential errors in fetching data from the external API.

### Solution
I added a check on the response code where we fetch the data. Here we log a usefull error message that can help us in the future.
```tsx
if (!response.ok) {
   throw new Error(`Failed to fetch users: ${response.status}`);
}
```

<b>

## Conclusion
I resolved this bug was resolved by preventing redundant API calls, correcting mismatched property names in the UI, enforcing strong typing for better reliability, and adding error handling to make the application more robust and easier to debug in the future.
