document.addEventListener("DOMContentLoaded", function () {
    const newsFeed = document.getElementById("newsFeed");
    const newsContainer = document.createElement("div");
    
    if(!newsFeed) {
        return false;
    }

    newsContainer.classList.add("news-container");

    while (newsFeed.firstChild) {
        newsContainer.appendChild(newsFeed.firstChild);
    }

    newsFeed.appendChild(newsContainer);

    function resetScroll() {
        newsContainer.style.animation = "none";
        newsContainer.style.transform = "translateY(0)";
        void newsContainer.offsetWidth; // Trigger reflow
        newsContainer.style.animation = "scrollUp 6s linear infinite";
    }

    newsContainer.addEventListener("animationiteration", resetScroll);
});

// CanvasBG.init({
//     Loc: {
//         x: window.innerWidth / 2,
//         y: window.innerHeight / 3.3
//     },
// });


function zoomIn(imageId) {
    const image = document.getElementById("zoomImage_"+imageId);
    //image.classList.remove("image-zoom-out"); // Remove zoom-out class if present
    //image.classList.add("image-zoom-in"); // Add zoom-in class on mouse hover
  }

function zoomOut(imageId) {
    const image = document.getElementById("zoomImage_"+imageId);
    //image.classList.remove("image-zoom-in"); // Remove zoom-in class when mouse leaves
    //image.classList.add("image-zoom-out"); // Add zoom-out class when mouse leaves
}

 function setAmount(amount) {
            document.getElementById('custom-amount').value = amount;
}

