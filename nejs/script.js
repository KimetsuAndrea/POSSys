async function fetchData() {
    try {
        const response = await fetch('../php/fetchproduct.php');
        const data = await response.json();
        const products = data.products;

        if (Array.isArray(products)) {
            const productContainer = document.getElementById('product-container');
            products.forEach(product => {
                const saleBadge = product.salebadge
                    ? `<div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">${product.salebadge}</div>`
                    : '';

                let reviewStars = '';
                if (product.review && product.review > 0) {
                    const stars = Math.min(product.review, 5);
                    for (let i = 0; i < stars; i++) {
                        reviewStars += `<div class="bi-star-fill"></div>`;
                    }
                }

                const productCard = `
                    <div class="col mb-5">
                        <div class="card h-100">
                            ${saleBadge}
                            <img class="card-img-top" 
                                 src="${product.imageUrl}" 
                                 alt="${product.name}" 
                                 onerror="this.onerror=null;this.src='/img/default.jpg';" 
                                 loading="lazy" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">${product.name}</h5>
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        ${reviewStars}
                                    </div>
                                    <p>₱${product.price}</p>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>`;
                productContainer.innerHTML += productCard;
            });
        } else {
            console.error('Data is not an array:', products);
        }
    } catch (error) {
        console.error('Error fetching the data:', error);
    }
}
fetchData();