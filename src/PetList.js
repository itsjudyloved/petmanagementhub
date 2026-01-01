import React, { useState, useEffect } from 'react';

function PetList() {
    const [pets, setPets] = useState([]);

    // Fetch pets when the component mounts
    useEffect(() => {
        fetch('http://localhost/Cortes_FinalProject/api/get_pets.php')
            .then(response => response.json())
            .then(data => setPets(data));
    }, []);

    return (
        <div>
            <h2>Pet Shop</h2>
            <div>
                {pets.length === 0 ? (
                    <p>No pets available.</p>
                ) : (
                    pets.map((pet) => (
                        <div key={pet.id}>
                            <img src={pet.image_url} alt={pet.name} />
                            <h3>{pet.name}</h3>
                            <p>{pet.breed}</p>
                            <p>{pet.age} years old</p>
                            <p>${pet.price}</p>
                            <p>{pet.description}</p>
                            <button onClick={() => handleDelete(pet.id)}>Delete Pet</button>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}

export default PetList;
