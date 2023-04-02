/* THIS JAVASCRIPT FILE IS RESPONSIBLE FOR THE ITEM PAGE */

/* getting the id of the itemSection element */

let itemSection = document.getElementById("itemSection");

/* receiving the items in the local storage */

let itemInfo = sessionStorage.getItem("item").split(',');
let itemPos = parseInt(sessionStorage.getItem("itemPos"));

/* creating the elemenents that will display the item */

itemElement = document.createElement("div");
itemElement.className = "item";

imageElement = document.createElement("img");
imageElement.src = `${itemInfo[4]}`;
itemElement.appendChild(imageElement);

/* if statement used to properly display the index of item */

let productNumber;
if ( itemPos < 35 ) {
    productNumber = itemPos+1;
}else if( itemPos < 75 ){
    productNumber = itemPos - 33;
}else
    productNumber = itemPos - 73;

/* continuing the alocation of elements for the item */

/* accessing the inner html of the item description to add the remaining info for the item */

itemDescription = document.createElement("div");
itemDescription.className = "description";
itemDescription.innerHTML =
    `
            <h3>${itemInfo[0]} (${productNumber}) - ${itemInfo[1]} </h3>
            <p>${itemInfo[2]}</p>
            <h2>${itemInfo[3]}</h2>
            <button className="button" onclick="AddToCart()">Buy</button>
        `;

/* appending the elements to the main element */

itemElement.appendChild(itemDescription);
itemSection.appendChild(itemElement);

/* function used to add item to cart */

function AddToCart() {
    localStorage.setItem(`p${localStorage.length + 1}`, itemInfo);
    alert("Item added to Cart!!!");
}