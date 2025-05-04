import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom/client';

// Define the properties to ensure type safetly and improve readability
interface User {
  id: number;
  name: string;
}

function UserList() {

  // Apply strong typing to make more robust
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    try {
      const response = await fetch('https://jsonplaceholder.typicode.com/users');

      // Handle case when there is a server error
      if (!response.ok) {
        throw new Error(`Failed to fetch users: ${response.status}`);
      }
     
      const result: User[] = await response.json();
     
      setUsers(result);

    } catch (error) {
      console.error('Error fetching users:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1>User List</h1>
      {loading ? (
        <p>Loading users...</p>
      ) : (
        <ul>
          {users.map((user: any) => (
            <li key={user.id}>{user.name}</li>
          ))}
        </ul>
      )}
    </div>
  );
}

const root = ReactDOM.createRoot(document.getElementById('wrapper') as HTMLElement);
root.render(
  <UserList />
);