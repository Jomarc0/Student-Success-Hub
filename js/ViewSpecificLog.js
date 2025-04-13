function confirmDelete(studentName) {
    if (confirm('Are you sure you want to delete this student record? This action cannot be undone.')) {
        window.location.href = 'delete-form.php?name=' + encodeURIComponent(studentName);
    }
}

function printPage() {
    window.print();
}