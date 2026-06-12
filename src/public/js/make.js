function generatePassword() {
    const password = Math.floor(1000 + Math.random() * 9000);
    document.getElementById("roomNumber").value = password;
}
