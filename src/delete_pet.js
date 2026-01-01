const handleDelete = async (petId) => {
    // Confirm the delete action
    if (window.confirm("Are you sure you want to delete this pet?")) {
        // Send DELETE request to API
        const response = await fetch(`http://localhost/Cortes_FinalProject/api/delete_pet.php?id=${petId}`, {
            method: 'GET', // Use GET for deleting (as per your API setup)
        });

        const data = await response.json();
        if (data.message) {
            alert(data.message);  // Show success message
            // Refresh the pet list after deletion
            setPets(pets.filter(pet => pet.id !== petId));
        } else if (data.error) {
            alert(data.error);  // Show error message
        }
    }
};
