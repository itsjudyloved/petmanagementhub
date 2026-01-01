import React, { useState } from 'react';

function AddPet() {
    const [name, setName] = useState('');
    const [breed, setBreed] = useState('');
    const [age, setAge] = useState('');
    const [price, setPrice] = useState('');
    const [description, setDescription] = useState('');
    const [imageUrl, setImageUrl] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();

        const petData = {
            name,
            breed,
            age,
            price,
            description,
            image_url: imageUrl,
        };

        // Send the POST request to add_pet.php
        const response = await fetch('http://localhost/Cortes_FinalProject/api/add_pet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(petData),
        });

        const data = await response.json();

        if (data.message) {
            alert(data.message);  // Pet added successfully
            // Optionally, redirect to the pet list page or reset the form
        } else if (data.error) {
            alert(data.error);  // Show error message
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <input type="text" value={name} onChange={(e) => setName(e.target.value)} placeholder="Pet Name" required />
            <input type="text" value={breed} onChange={(e) => setBreed(e.target.value)} placeholder="Breed" required />
            <input type="number" value={age} onChange={(e) => setAge(e.target.value)} placeholder="Age" required />
            <input type="number" value={price} onChange={(e) => setPrice(e.target.value)} placeholder="Price" required />
            <textarea value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Description" required />
            <input type="text" value={imageUrl} onChange={(e) => setImageUrl(e.target.value)} placeholder="Image URL" required />
            <button type="submit">Add Pet</button>
        </form>
    );
}

export default AddPet;
