<script text="text/javascript">
function toggleMenu() {
    const menu = document.querySelector('#toogleMenu');
    menu.classList.toggle('hidden');
    // toogle content from 5/6 to 6/6
    const content = document.querySelector('#content');
    content.classList.toggle('w-5/6');
    content.classList.toggle('w-full');

}

function toogleAccountMenu() {
    const accountMenu = document.querySelector('#accountMenu');
    accountMenu.classList.toggle('hidden');
}

function toggleDropdown(id) {
    const dropdownMenu = document.querySelector(`#dropdown-menu-${id}`);
    const dropdownIcon = document.querySelector(`#dropdown-icon-${id}`);
    dropdownMenu.classList.toggle('hidden');
    dropdownIcon.classList.toggle('rotate-180');
}
</script>