let header = document.querySelector("header");
let menu = document.querySelector("#menu-icon");
let navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
  header.classList.toggle("shadow", window.scrollY > 0);
});

menu.onclick = () => {
  menu.classList.toggle("bx-x");
  navbar.classList.toggle("active");
};
window.onscroll = () => {
  menu.classList.remove("bx-x");
  navbar.classList.remove("active");
};

var swiper = new Swiper(".home", {
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

var swiper = new Swiper(".coming-container", {
  spaceBetween: 20,
  loop: true,
  autoplay: {
    delay: 10500,
    disableOnInteraction: false,
  },
  centeredSlides: true,
  breakpoints: {
    0: {
      slidesPerView: 2,
    },
    568: {
      slidesPerView: 3,
    },
    768: {
      slidesPerView: 4,
    },
    968: {
      slidesPerView: 5,
    },
  },
});

// const container = document.querySelector(".container");
// const loginForm = document.querySelector(".login-form");
// const RegisterForm = document.querySelector(".Register-form");
// const RegiBtn = document.querySelector(".RegisteBtn.RegiBtn");
// const LoginBtn = document.querySelector(".LoginBtn");
// const signUpButton = document.querySelector("#signUpButton"); // Ensure the ID matches the button's ID
// const signUpForm = document.querySelector("form"); // Ensure this matches your form

// // Handle the Register button click
// if (RegiBtn) {
//   RegiBtn.addEventListener("click", (e) => {
//     e.preventDefault(); // Prevent default link behavior
//     console.log("Register button clicked");
//     RegisterForm.classList.add("active");
//     loginForm.classList.add("active");
//   });
// } else {
//   console.error("Register button not found in the DOM.");
// }

// // Handle the Login button click
// if (LoginBtn) {
//   LoginBtn.addEventListener("click", () => {
//     RegisterForm.classList.remove("active");
//     loginForm.classList.remove("active");
//   });
// } else {
//   console.error("Login button not found in the DOM.");
// }

// // Handle the Sign Up button click
// if (signUpButton) {
//   signUpButton.addEventListener("click", (e) => {
//     e.preventDefault(); // Prevent form submission

//     const formData = new FormData(signUpForm);

//     fetch(signUpForm.action, {
//       method: "POST",
//       body: formData,
//     })
//       .then((response) => response.text())
//       .then((data) => {
//         console.log(data); // Handle the response from the server
//         if (data.includes("User registered successfully!")) {
//           alert("User registered successfully!");
//           // Optionally, redirect or update the UI
//         } else {
//           alert("There was an error registering the user.");
//         }
//       })
//       .catch((error) => {
//         console.error("Error:", error);
//       });
//   });
// } else {
//   console.error("Sign Up button not found in the DOM.");
// }
