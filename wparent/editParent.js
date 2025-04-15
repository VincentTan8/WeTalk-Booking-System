document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

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
                    document.querySelector('.myprofile-name .bio-text').innerText = data.bio;
                    document.querySelector('.profile-info-text2.email').innerText = data.email;
                    document.querySelector('.profile-info-text2.username').innerText = data.username;
                    document.querySelector('.profile-info-text2.phone').innerText = data.phone;
                    document.querySelector('.profile-info-text2.gender').innerText = data.gender;
                    document.querySelector('.profile-info-text2.city').innerText = data.city;
                    document.querySelector('.profile-info-text2.birthday').innerText = data.birthday;
                    alert('Profile updated successfully!');
                } else {
                    alert('Error updating profile.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
