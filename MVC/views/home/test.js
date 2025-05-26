function scrollMovies(direction) {
    const movieList = document.getElementById('movieList');
    const scrollAmount = 300; // Khoảng cách cuộn mỗi lần
    if (direction === 'prev') {
        movieList.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else if (direction === 'next') {
        movieList.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}
