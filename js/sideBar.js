
var sideBarIsOpen = true;

// Add event listener for sidebar toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('#toggleBtn'); // Replace with the actual ID or class of the toggle button
    const sidebar = document.querySelector('#sidebar'); // Replace with the actual ID of the sidebar
    const navigationContainer = document.querySelector('#navigationcontainer'); // Replace with actual ID
    const headerLogo = document.querySelector('#headerlogo'); // Replace with actual ID
    const sidebarTitle = document.querySelector('#sidebartitle'); // Replace with actual ID
    const username = document.querySelector('#username'); // Replace with actual ID

    toggleBtn.addEventListener('click', (event) => {
        event.preventDefault();

        if (sideBarIsOpen) {
            sidebar.style.width = '100px';
            sidebar.style.transition = '0.3s all';
            navigationContainer.style.left = '8%';
            headerLogo.style.width = '70px';
            sidebarTitle.style.fontSize = '14px';
            username.style.fontSize = '12px';

            const buttonTexts = document.getElementsByClassName('buttontext');
            for (var i = 0; i < buttonTexts.length; i++) {
                buttonTexts[i].style.display = 'none';
            }
            sideBarIsOpen = false;
        } else {
            sidebar.style.width = '250px';
            navigationContainer.style.left = '15%';
            headerLogo.style.width = '100px';
            sidebarTitle.style.fontSize = '24px';
            username.style.fontSize = '18px';

            const buttonTexts = document.getElementsByClassName('buttontext');
            for (var i = 0; i < buttonTexts.length; i++) {
                buttonTexts[i].style.display = 'inline-block';
            }
            sideBarIsOpen = true;
        }
    });
});





