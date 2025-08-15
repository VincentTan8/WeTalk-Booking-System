document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-profile-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form from refreshing the page

        const formData = new FormData(form);

        // Send AJAX request to PHP script
        fetch('updateProfile.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Dynamically update the profile information without reloading the page
                    document.querySelector('.myprofile-name .name-text').innerText = data.fname + " " + data.lname;
                    document.querySelector('.profile-info-text2.email').innerText = data.email;
                    document.querySelector('.profile-info-text2.username').innerText = data.username;
                    alert('Profile updated successfully!');
                } else {
                    alert('Error updating profile. ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
