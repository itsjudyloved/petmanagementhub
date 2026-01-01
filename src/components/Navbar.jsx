import React from 'react';
import { Link, useNavigate } from 'react-router-dom';

function Navbar() {
  const navigate = useNavigate();
  const userId = localStorage.getItem('user_id');

  const handleLogout = async () => {
    try {
      const response = await fetch('/logout.php', {
        method: 'POST',
        credentials: 'include'
      });
      
      if (response.ok) {
        localStorage.removeItem('user_id');
        navigate('/login');
      }
    } catch (error) {
      console.error('Logout failed:', error);
    }
  };

  return (
    <header className="bg-purple-600 fixed top-0 left-0 right-0 z-50">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center h-16">
          <div className="flex items-center space-x-8">
            <Link to="/" className="text-white text-xl font-bold">
              The Lilac Constellation
            </Link>
            <nav className="hidden md:flex space-x-4">
              <Link to="/" className="text-white hover:bg-purple-700 px-3 py-2 rounded-md">
                Dashboard
              </Link>
              <Link to="/about" className="text-white hover:bg-purple-700 px-3 py-2 rounded-md">
                About Us
              </Link>
            </nav>
          </div>
          
          <div className="flex items-center space-x-4">
            <span className="text-white text-sm">
              <span className="opacity-75">User ID:</span>
              <span className="ml-1">{userId}</span>
            </span>
            <button
              onClick={handleLogout}
              className="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-50 transition duration-200"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </header>
  );
}

export default Navbar; 