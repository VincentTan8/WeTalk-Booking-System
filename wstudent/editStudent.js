// editStudent.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fnameField = document.querySelector('input[name="fname"]');
    const lnameField = document.querySelector('input[name="lname"]');
    const emailField = document.querySelector('input[name="email"]');
    const phoneField = document.querySelector('input[name="phone"]');
    const genderField = document.querySelector('input[name="gender"]');
    const cityField = document.querySelector('input[name="city"]');
    const birthdayField = document.querySelector('input[name="birthday"]');
    
    form.addEventListener('submit', function(e) {
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
