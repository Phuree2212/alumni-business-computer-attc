    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.querySelector('.sidebar-toggle');

      if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
          sidebar.classList.remove('show');
        }
      }
    });

    // Handle menu item clicks
    document.querySelectorAll('.menu-link').forEach(link => {
      link.addEventListener('click', function(e) {
        // Remove active class from all links
        document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));

        // Add active class to clicked link (if it's not a toggle)
        if (!this.hasAttribute('data-bs-toggle')) {
          this.classList.add('active');
        }
      });
    });