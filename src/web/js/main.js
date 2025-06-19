document.addEventListener("DOMContentLoaded", () => {
    const sidebarToggle = document.getElementById("sidebarToggle")
    const sidebar = document.getElementById("sidebar")
    const mainContent = document.querySelector(".main-content")

    // Sidebar toggle functionality
    sidebarToggle.addEventListener("click", () => {
        if (window.innerWidth > 768) {
            sidebar.classList.toggle("collapsed")
            mainContent.classList.toggle("expanded")
        } else {
            sidebar.classList.toggle("show")
        }
    })

    // Close sidebar on mobile when clicking outside
    document.addEventListener("click", (e) => {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove("show")
            }
        }
    })

    // Handle window resize
    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("show")
        } else {
            sidebar.classList.remove("collapsed")
            mainContent.classList.remove("expanded")
        }
    })

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault()
            const target = document.querySelector(this.getAttribute("href"))
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                })
            }
        })
    })

    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll(".alert")
    notifications.forEach((notification) => {
        setTimeout(() => {
            notification.style.opacity = "0"
            setTimeout(() => {
                notification.remove()
            }, 300)
        }, 5000)
    })
})

// Utility functions for Yii2 integration
window.AdminPanel = {
    // Show loading state
    showLoading: (element) => {
        if (element) {
            element.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Загрузка...'
            element.disabled = true
        }
    },

    // Hide loading state
    hideLoading: (element, originalText) => {
        if (element) {
            element.innerHTML = originalText
            element.disabled = false
        }
    },

    // Show notification
    showNotification: (message, type = "success") => {
        const alertDiv = document.createElement("div")
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`
        alertDiv.style.cssText = "top: 20px; right: 20px; z-index: 9999; min-width: 300px;"
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `

        document.body.appendChild(alertDiv)

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove()
        }, 5000)
    },

    // Confirm dialog
    confirm: (message, callback) => {
        if (confirm(message)) {
            callback()
        }
    },
}
