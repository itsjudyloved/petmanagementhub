import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { usePetApi } from '../hooks/usePetApi';

function Dashboard() {
  const [pets, setPets] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('');
  const { loading, error, getAllPets, deletePet } = usePetApi();

  useEffect(() => {
    loadPets();
  }, []);

  const loadPets = async () => {
    try {
      const data = await getAllPets();
      setPets(data);
    } catch (err) {
      console.error('Error loading pets:', err);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this pet?')) {
      try {
        await deletePet(id);
        loadPets(); // Reload the list after deletion
      } catch (err) {
        console.error('Error deleting pet:', err);
      }
    }
  };

  const filteredPets = pets
    .filter(pet => 
      pet.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      pet.breed.toLowerCase().includes(searchTerm.toLowerCase())
    )
    .sort((a, b) => {
      if (!sortBy) return 0;
      if (sortBy === 'name') return a.name.localeCompare(b.name);
      if (sortBy === 'age') return a.age - b.age;
      return 0;
    });

  if (loading) return <div className="text-center py-8">Loading...</div>;
  if (error) return <div className="text-red-600 text-center py-8">{error}</div>;

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex justify-between items-center mb-8">
        <h1 className="text-3xl font-bold text-purple-800">Pet Dashboard</h1>
        <Link
          to="/add-pet"
          className="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-200"
        >
          Add New Pet
        </Link>
      </div>

      <div className="bg-white rounded-xl p-6 mb-8 shadow-md">
        <div className="flex flex-wrap gap-4 items-center justify-between">
          <input
            type="text"
            placeholder="Search pets..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="flex-1 min-w-[200px] px-4 py-2 rounded-lg border border-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
          />
          <select
            value={sortBy}
            onChange={(e) => setSortBy(e.target.value)}
            className="px-4 py-2 rounded-lg border border-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
          >
            <option value="">Sort By</option>
            <option value="name">Name</option>
            <option value="age">Age</option>
          </select>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredPets.length > 0 ? (
          filteredPets.map(pet => (
            <div key={pet.id} className="bg-white rounded-xl shadow-md overflow-hidden">
              <img
                src={pet.image_url || 'https://via.placeholder.com/300x200'}
                alt={pet.name}
                className="w-full h-48 object-cover"
              />
              <div className="p-6">
                <h3 className="text-xl font-semibold text-purple-800 mb-2">{pet.name}</h3>
                <p className="text-purple-600">Breed: {pet.breed}</p>
                <p className="text-purple-600 mb-4">Age: {pet.age} years</p>
                <p className="text-gray-600 mb-4">{pet.description}</p>
                
                <div className="grid grid-cols-2 gap-3">
                  <Link
                    to={`/edit-pet/${pet.id}`}
                    className="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 transition duration-200 text-center"
                  >
                    Edit
                  </Link>
                  <button
                    onClick={() => handleDelete(pet.id)}
                    className="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="col-span-3 text-center py-8">
            <div className="bg-white rounded-xl p-8 shadow-sm">
              <h3 className="text-xl font-semibold text-purple-800 mb-2">No Pets Found</h3>
              <p className="text-gray-600 mb-4">Try adjusting your search or add a new pet.</p>
              <Link
                to="/add-pet"
                className="inline-block bg-purple-600 text-white py-2 px-6 rounded-lg hover:bg-purple-700 transition duration-200"
              >
                Add Your First Pet
              </Link>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default Dashboard; 