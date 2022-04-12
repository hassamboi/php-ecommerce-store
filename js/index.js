document.addEventListener("DOMContentLoaded", function () {
  new Splide(".splide", {
    type: "fade",
    rewind: true,
    autoplay: true,
    height: 700,
    pauseOnFocus: false,
    pauseOnHover: false,
    resetProgress: false,
    cover: true,
  }).mount();
});

const filterBtns = document.querySelector(".filter-btns");
const filterItems = document.querySelectorAll(".item-box");
const noItem = document.querySelector(".no-item");

filterBtns.addEventListener("click", (e) => {
  //event listener on filter-btns of click
  if (e.target.classList.contains("filter-btn")) {
    //check whether the clicked btn was actually a btn
    let itemsPresent = false;

    filterBtns
      .querySelector(".filter-btn-active")
      .classList.remove("filter-btn-active"); //remove the active class from the previously active btn
    e.target.classList.add("filter-btn-active"); //add the active class to now selected btn

    let filterName = e.target.getAttribute("data-name"); //get the data-name value of the selected btn
    filterItems.forEach((item) => {
      //traverse the product items
      let isPartOfCategory = item.classList.contains(filterName); //for each item check if it lies in the category of filter selected
      if (isPartOfCategory || filterName === "all") {
        item.classList.add("item-box-show"); //show the items
        item.classList.remove("item-box-hide");
        noItem.classList.add("no-item-hide");
        itemsPresent = true;
      } else {
        item.classList.remove("item-box-show"); //hide the items
        item.classList.add("item-box-hide");
      }
    });
    if (!itemsPresent) {
      //if no items are present show a paragraph stating that
      noItem.classList.add("no-item-show");
      noItem.classList.remove("no-item-hide");
    }
  } else {
    console.log("ghalat click bsdk");
  }
});
