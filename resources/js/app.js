import './bootstrap';

window.openDrawer = function () {
    document.getElementById('mobile-drawer').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};
window.closeDrawer = function () {
    document.getElementById('mobile-drawer').classList.add('hidden');
    document.body.style.overflow = '';
};