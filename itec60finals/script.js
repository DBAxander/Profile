document.getElementById('studentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Reset errors
    document.querySelectorAll('.error').forEach(el => el.textContent = '');
    
    // Validate fields
    let isValid = true;
    
    // Name validation
    const name = document.getElementById('fullName').value.trim();
    if (name === '') {
        document.getElementById('nameError').textContent = 'Name is required';
        isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(name)) {
        document.getElementById('nameError').textContent = 'Name should only contain letters';
        isValid = false;
    }
    
    // Student ID validation
    const studentId = document.getElementById('studentId').value.trim();
    if (studentId === '') {
        document.getElementById('idError').textContent = 'Student ID is required';
        isValid = false;
    } else if (!/^\d+$/.test(studentId)) {
        document.getElementById('idError').textContent = 'Student ID should only contain numbers';
        isValid = false;
    }
    
    // Email validation
    const email = document.getElementById('email').value.trim();
    if (email === '') {
        document.getElementById('emailError').textContent = 'Email is required';
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email';
        isValid = false;
    }
    
    // Phone validation
    const phone = document.getElementById('phone').value.trim();
    if (phone === '') {
        document.getElementById('phoneError').textContent = 'Phone number is required';
        isValid = false;
    } else if (!/^[\d\s\-()+]+$/.test(phone)) {
        document.getElementById('phoneError').textContent = 'Please enter a valid phone number';
        isValid = false;
    }
    
    // If all valid, submit the form
    if (isValid) {
        this.submit();
    }
});

// Add input masking for phone number
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^\d\s\-()+]/g, '');
});