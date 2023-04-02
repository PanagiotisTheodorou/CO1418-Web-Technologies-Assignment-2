/* THIS JAVASCRIPT FILE IS RESPONSIBLE FOR THE CART PAGE */

/* Getting the element ids for the cart and price elements */

let cartSection = document.getElementById("cartSection");
let priceElement = document.getElementById("price");

/* variables will be used later on in the file */

let prices = [];
let total = 0;
let price = 0;

/* The following for loop receives everything in the local storage and creates its elemets */

/* The functionality of the for loop is allmost identical to the product page, the only difference 
is that in this case the foor loop  goes through every element in the local storage and it creates
ids for each item in the local storage */
let productIds = "";

let j = 1;
for (let key in localStorage) {
  if (key[0] === "p") {
    let itemId = key[1];
    let itemInfo = localStorage.getItem(key).split(",");
    productIds += `,${itemInfo[3]}`;
    /* Here the sections for each item are made, they are done in a different manner than the other
    pages because all elements are appended in the itemElement which makes CSS and generall displayability
    more efficient */

    itemElement = document.createElement("div");
    itemElement.className = "product";

    /* product number (in order of being stored in the cart) */

    let itemNum = document.createElement("h3");
    itemNum.innerText = j;
    itemElement.appendChild(itemNum);

    /* product image */

    imageElement = document.createElement("img");
    imageElement.src = `${itemInfo[1]}`;
    itemElement.appendChild(imageElement);

    /* product name */

    itemName = document.createElement("h3");
    itemName.innerText = `${itemInfo[0]}`;
    itemElement.appendChild(itemName);

    /* product price */

    itemPrice = document.createElement("h2");
    itemPrice.innerText = itemInfo[2];
    itemElement.appendChild(itemPrice);

    /* remove button */

    itemButton = document.createElement("p");
    itemButton.innerHTML = `<button class="removeButton" onclick= "removeItem(${itemId})">Remove</button>`;
    itemElement.appendChild(itemButton);
    cartSection.appendChild(itemElement);

    /* checking the price of each item to then allocate the correct amount in the prices array */

    if (itemInfo[2].includes("39")) {
      price = 39.99;
      prices.push(price);
    } else if (itemInfo[2].includes("29")) {
      price = 29.99;
      prices.push(price);
    } else if (itemInfo[2].includes("19")) {
      price = 19.99;
      prices.push(price);
    }

    j++;
  }
}

/* calculating total amount */
if (!location.href.includes("ids=,") && productIds !== "")
  window.location.href = `cart.php?ids=${productIds}`;

for (let i = 0; i < prices.length; i++) {
  total = total + prices[i];
}

let totalSection = document.getElementById("total");

/* if the local storage is empty then approriate h1 is displayed, if not then the total and buttons are showed */

priceElement.className = "priceAmount";
if (localStorage.length > 0) {
  priceElement.innerHTML = `
    <h1>The total amout is: £${total.toFixed(2)}</h1>
    `;
} else if (localStorage.length === 0) {
  priceElement.innerHTML = `
    <h1>The cart is empty</h1>
    `;
}

/* creating the buttons for the clear and buy */

clearElement = document.createElement("div");
clearElement.innerHTML = `
    <button class="clearButton" onclick="clearCart()">Clear Cart</button>
`;

buyElement = document.createElement("div");
buyElement.innerHTML = `
<form method="POST" account=""><button class="BuyCart" id="BuyCart" type="submit" name="buySubmit" onclick="return validateBuy()">Buy</button></form>
`;

/* appending the buttons in the totalSection */

totalSection.appendChild(clearElement);
totalSection.appendChild(buyElement);

/* displaying them depending on if the local storage is empty or not */

if (productIds === "") {
  totalSection.style.display = "none";
  document.getElementById("BuyCart").style.display = "none";
} else {
  totalSection.style.display = "grid";
  document.getElementById("BuyCart").style.display = "block";
}

/* functions used to clear,remove and buy items */

function clearCart() {
  localStorage.clear();
  window.location.href = "cart.php?ids=";
}

function removeItem(itemId) {
  localStorage.removeItem(`p${itemId}`);
  window.location.href = "cart.php?ids=";
}

function buyCart() {
  clearCart();
  alert("Thanks for purchasing, your order will be dispached soon...");
}

function validateBuy(){
  if(document.getElementById('signin-signup').style.display == 'block') {
    alert('You are not signed in!!');
    return false;
  }
}