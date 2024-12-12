const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

   
      // Redirect to email verification page after sign up
      const signUpSubmitBtn = document.getElementById('sign-up-submit');
      
      signUpSubmitBtn.addEventListener('click', function(event) {
       
        // Redirect to the email verification page
        window.location.href = "verification.html";
      });