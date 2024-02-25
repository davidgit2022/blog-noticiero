document.addEventListener('DOMContentLoaded', function () {
    let pageLinks = document.querySelectorAll('.pagination .page-link');

    pageLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            let page = this.getAttribute('data-page');
            window.location.href = "{{ route('news.page') }}/" + page;
        });
    });
});