function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[0-9\-\s\(\)]{10,}$/;
    return re.test(phone);
}

function validateStudentCode(code) {
    return code.length >= 9 && /^[0-9]+$/.test(code);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateYear(year) {
    const yearStr = year.toString().trim();
    const yearNum = parseInt(year);

    // ตรวจสอบว่าเป็นตัวเลข และความยาวไม่เกิน 4 ตัวอักษร
    return /^[0-9]+$/.test(yearStr) && yearStr.length == 4 && year > 2500;
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const feedback = field.nextElementSibling;
    field.classList.add('is-invalid');
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    }
}

function clearValidation(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    });
}