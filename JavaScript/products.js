/* getting the element ids for the navigation and section */
if (location.href.includes("products.php")) {
  navSection = document.getElementById("productsNav");

  sectionElement = document.getElementById("productsSection");

  /* adding the links to the navSection */

  navElement = document.createElement("div");
  navElement.id = "navElement";
  navElement.innerHTML = `
    <p>products> <a href="#tshirts">t-shirts</a> <a href="#hoodies">hoodies</a> <a href="#jumpers">jumpers</a> </p>
`;

  /* appending the navigation element to the navigation */

  navSection.appendChild(navElement);
}

function AddToCart(productTitle, productImage, productPrice) {
  localStorage.setItem(`p${localStorage.length + 1}`, [
    productTitle,
    productImage,
    productPrice,
  ]);
  alert("Item added to cart!");
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}
