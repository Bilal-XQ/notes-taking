// Confirm delete actions
document.addEventListener("DOMContentLoaded", () => {
  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert")
  if (alerts.length > 0) {
    setTimeout(() => {
      alerts.forEach((alert) => {
        alert.style.display = "none"
      })
    }, 5000)
  }

  // Password confirmation validation
  const passwordForm = document.querySelector("form:has(#password, #confirm_password)")
  if (passwordForm) {
    passwordForm.addEventListener("submit", (e) => {
      const password = document.getElementById("password")
      const confirmPassword = document.getElementById("confirm_password")

      if (password && confirmPassword && password.value !== confirmPassword.value) {
        e.preventDefault()
        alert("Passwords do not match!")
      }
    })
  }
})

// Show/hide modal
function toggleModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.style.display = modal.style.display === "block" ? "none" : "block"
  }
}

// Close modal when clicking outside
window.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal")) {
    e.target.style.display = "none"
  }
})
