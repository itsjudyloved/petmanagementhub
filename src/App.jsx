import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import Dashboard from './pages/Dashboard';
import AboutUs from './pages/AboutUs';
import AddPet from './pages/AddPet';
import EditPet from './pages/EditPet';

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-purple-50">
        <Navbar />
        <div className="container mx-auto px-4 py-8">
          <Routes>
            <Route path="/" element={<Dashboard />} />
            <Route path="/about" element={<AboutUs />} />
            <Route path="/add-pet" element={<AddPet />} />
            <Route path="/edit-pet/:id" element={<EditPet />} />
          </Routes>
        </div>
      </div>
    </Router>
  );
}

export default App; 