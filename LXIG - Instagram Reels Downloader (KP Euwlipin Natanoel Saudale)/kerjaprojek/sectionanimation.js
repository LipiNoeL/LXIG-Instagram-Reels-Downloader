let sections = document.querySelectorAll('section');
let navlinks = document.querySelectorAll('header nav a');

// Fungsi untuk mengatur scroll position agar section berada di tengah layar
function scrollToSection(sectionId) {
    let section = document.getElementById(sectionId);
    if (section) {
        let sectionTop = section.offsetTop;
        let windowHeight = window.innerHeight;
        let scrollPosition = sectionTop - (windowHeight / 3);
        window.scrollTo({
            top: scrollPosition,
            behavior: 'smooth'
        });
    }
}

navlinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        let sectionId = link.getAttribute('href').substring(1); // Mengambil ID section dari href
        scrollToSection(sectionId);
    });
});

window.onscroll = () => {
    sections.forEach(sec => {
        let top = window.scrollY;
        let offset = sec.offsetTop;
        let height = sec.offsetHeight;
        let id = sec.getAttribute('id');
    });
};