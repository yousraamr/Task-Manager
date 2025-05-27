// Sample navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            const views = {
                'Dashboard': document.querySelector('.dashboard-view'),
                'Team': document.querySelector('.team-view'),
                'Marketing Campaign': document.querySelector('.project-detail-view')
            };
            
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Show corresponding view
                    const viewName = this.textContent.trim();
                    
                    // Hide all views
                    Object.values(views).forEach(view => {
                        if (view) view.style.display = 'none';
                    });
                    
                    // Show selected view
                    if (views[viewName]) {
                        views[viewName].style.display = 'block';
                    }
                });
            });
            
            // Add User Popup
            const addUserBtn = document.querySelector('.add-user-btn');
            const addUserPopup = document.getElementById('addUserPopup');
            const closePopupBtns = document.querySelectorAll('.close-popup, .cancel-btn');
            
            addUserBtn.addEventListener('click', function() {
                addUserPopup.style.display = 'flex';
            });
            
            closePopupBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    addUserPopup.style.display = 'none';
                });
            });
            
            // Form submission (sample)
            const addUserForm = document.getElementById('addUserForm');
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Here you can add your code to submit the form data to the server
                console.log('New user data:', new FormData(addUserForm));
                addUserPopup.style.display = 'none';
            });
        });
// assets/js/dashboard.js

document.addEventListener('DOMContentLoaded', () => {
  const teamNavItem = document.querySelector('.nav-item:nth-child(4)');
  const teamView    = document.querySelector('.team-view');
  
  teamNavItem.addEventListener('click', () => {
    // Hide other views, show team
    document.querySelectorAll('.dashboard-view, .project-detail-view').forEach(v => v.style.display = 'none');
    teamView.style.display = 'block';

    // Fetch members from the API
    fetch('/Principles_Project/public/index.php?route=getMembers')
      .then(res => res.json())
      .then(data => {
        if (!data.success) throw new Error('Failed to load members');
        const tbody = document.getElementById('team-body');
        tbody.innerHTML = '';
        
        // Update the team members fetch code
        data.members.forEach(member => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="member-info">
                        <div class="member-avatar">${member.fullname.split(' ').map(n=>n[0]).join('')}</div>
                        <div>
                            <div class="member-name">${member.fullname}</div>
                            <div class="member-email">${member.email}</div>
                        </div>
                    </div>
                </td>
                <td><span class="role-badge status-badge-${member.role}">${member.role}</span></td>
                <td><span class="status-badge status-active">Active</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="edit-user-btn" data-id="${member.id}" data-name="${member.fullname}" data-email="${member.email}" data-role="${member.role}">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="delete-user-btn" data-id="${member.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
      })
      .catch(err => console.error(err));
  });
});

// Replace multiple DOMContentLoaded listeners with a single one
document.addEventListener('DOMContentLoaded', function() {
    // Get all required elements
    const addUserForm = document.getElementById('addUserForm');
    const editUserForm = document.getElementById('editUserForm');
    const addUserPopup = document.getElementById('addUserPopup');
    const editUserPopup = document.getElementById('editUserPopup');
    const addUserBtn = document.querySelector('.add-user-btn');
    const closePopupBtns = document.querySelectorAll('.close-popup');
    const cancelBtns = document.querySelectorAll('.cancel-btn');

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        
        notificationMessage.textContent = message;
        notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
        notification.style.display = 'block';
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.style.display = 'none';
                notification.style.animation = '';
            }, 300);
        }, 10000);
    }

    // Function to hide all popups
    function hidePopups() {
        addUserPopup.style.display = 'none';
        editUserPopup.style.display = 'none';
        addUserForm?.reset();
        editUserForm?.reset();
    }

    // Add click handlers for close and cancel buttons
    closePopupBtns.forEach(btn => btn.addEventListener('click', hidePopups));
    cancelBtns.forEach(btn => btn.addEventListener('click', hidePopups));

    // Show add user popup
    addUserBtn?.addEventListener('click', () => {
        addUserPopup.style.display = 'flex';
    });

    // Handle edit user button clicks
    document.addEventListener('click', async (e) => {
        if (e.target.closest('.edit-user-btn')) {
            const btn = e.target.closest('.edit-user-btn');
            
            // Populate edit form
            document.getElementById('editUserId').value = btn.dataset.id;
            document.getElementById('editFullName').value = btn.dataset.name;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editRole').value = btn.dataset.role;
            
            // Show edit popup
            editUserPopup.style.display = 'flex';
        }

        if (e.target.closest('.delete-user-btn')) {
            const btn = e.target.closest('.delete-user-btn');
            const deleteConfirmPopup = document.getElementById('deleteConfirmPopup');
            const deleteConfirmBtn = deleteConfirmPopup.querySelector('.delete-confirm-btn');
            const closePopupBtn = deleteConfirmPopup.querySelector('.close-popup');
            const cancelBtn = deleteConfirmPopup.querySelector('.cancel-btn');

            // Show delete confirmation popup
            deleteConfirmPopup.style.display = 'flex';

            // Handle delete confirmation
            const handleDelete = async () => {
                try {
                    const response = await fetch('/Principles_Project/public/index.php?route=users/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `userId=${btn.dataset.id}`
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        btn.closest('tr').remove();
                        showNotification('User deleted successfully');
                    } else {
                        showNotification(data.message || 'Failed to delete user', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('An error occurred while deleting the user', 'error');
                } finally {
                    deleteConfirmPopup.style.display = 'none';
                }
            };

            // Add event listeners
            deleteConfirmBtn.addEventListener('click', handleDelete, { once: true });
            closePopupBtn.addEventListener('click', () => {
                deleteConfirmPopup.style.display = 'none';
            });
            cancelBtn.addEventListener('click', () => {
                deleteConfirmPopup.style.display = 'none';
            });
        }
    });

    // Handle edit form submission
    editUserForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('/Principles_Project/public/index.php?route=users/update', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                hidePopups();
                showNotification('User updated successfully');
                window.location.reload();
            } else {
                showNotification(data.message || 'Failed to update user', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while updating the user', 'error');
        }
    });

    // Handle add form submission
    addUserForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('/Principles_Project/public/index.php?route=users/add', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                // Hide add user form
                hidePopups();
                
                // Show confirmation popup with password
                const confirmPopup = document.getElementById('addUserConfirmPopup');
                const tempPasswordEl = document.getElementById('tempPassword');
                const copyBtn = document.getElementById('copyPassword');
                const okButton = confirmPopup.querySelector('.submit-btn');
                const closeBtn = confirmPopup.querySelector('.close-popup');
                
                // Set password in popup
                tempPasswordEl.textContent = data.password;
                
                // Show popup
                confirmPopup.style.display = 'flex';
                
                // Handle copy button
                copyBtn.addEventListener('click', () => {
                    navigator.clipboard.writeText(data.password);
                    copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="fa-solid fa-copy"></i> Copy';
                    }, 2000);
                });
                
                // Handle close button
                const closeConfirmPopup = () => {
                    confirmPopup.style.display = 'none';
                    window.location.reload();
                };
                
                okButton.addEventListener('click', closeConfirmPopup);
                closeBtn.addEventListener('click', closeConfirmPopup);
                
            } else {
                showNotification(data.message || 'Failed to create user', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while adding the user', 'error');
        }
    });
});

