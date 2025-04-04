document.addEventListener("DOMContentLoaded", function() {
    const editButton = document.getElementById('edit-button'); // Select the edit button
    editButton.addEventListener("click", function() {
        const inputs = document.querySelectorAll('.editinfocontainer input'); // Select all input boxes
        const isEditing = inputs[0].hasAttribute('readonly'); // Check if currently in edit mode

        inputs.forEach(input => {
            input.readOnly = !isEditing; // Toggle readonly attribute
        });

        // Debugging statement to check button text change
        console.log(`Button clicked. Current state: ${isEditing ? 'Editing' : 'Saving'}`);
        
        editButton.textContent = isEditing ? 'Save' : 'Edit'; // Change button text
    });
});