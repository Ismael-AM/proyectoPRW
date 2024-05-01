document.addEventListener('DOMContentLoaded', () => {
    var container = document.getElementById("carouselContainer");
    var scrollAmount = 1;
    var scrollDelay = 20;
    
    function scrollContainer() {
        if (container.scrollWidth > container.clientWidth) {
            container.scrollLeft += scrollAmount;
        }
    }
    
    var scrollInterval = setInterval(scrollContainer, scrollDelay);
    
    container.addEventListener("mouseenter", function() {
        clearInterval(scrollInterval);
    });
    
    container.addEventListener("mouseleave", function() {
        scrollInterval = setInterval(scrollContainer, scrollDelay);
    });
})