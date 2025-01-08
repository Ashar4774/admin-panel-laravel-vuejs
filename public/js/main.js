document.getElementById('edit-image-button').addEventListener('click', function () {
    document.getElementById('profile-image-input').click();
});

document.getElementById('profile-image-input').addEventListener('change', function () {
    // Display a preview of the selected image (optional)
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.querySelector('.avatar img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
