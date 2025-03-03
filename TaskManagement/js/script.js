document.addEventListener("DOMContentLoaded", function () {
    // Sidebar Toggle
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.querySelector(".sidebar");
    if (menuToggle) {
        menuToggle.addEventListener("change", function () {
            sidebar.classList.toggle("active");
        });
    }

    // Form Validation
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            const inputs = form.querySelectorAll("input[required], select[required]");
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add("error");
                } else {
                    input.classList.remove("error");
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert("Please fill in all required fields.");
            }
        });
    });

    // Task Assignment (Admin)
    const assignTaskButtons = document.querySelectorAll(".assign-task-btn");
    assignTaskButtons.forEach(button => {
        button.addEventListener("click", function () {
            const taskId = this.dataset.taskId;
            const userId = document.getElementById(`assign-user-${taskId}`).value;
            
            fetch("assign_task.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ task_id: taskId, user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
        });
    });

    // Leave Group (User)
    const leaveGroupButtons = document.querySelectorAll(".leave-group-btn");
    leaveGroupButtons.forEach(button => {
        button.addEventListener("click", function () {
            const groupId = this.dataset.groupId;
            if (confirm("Are you sure you want to leave this group?")) {
                fetch("leave_group.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ group_id: groupId })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
