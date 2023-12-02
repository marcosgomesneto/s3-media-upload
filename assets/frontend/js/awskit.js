document.addEventListener("DOMContentLoaded", (event) => {
  const fileUpload = document.querySelector("#awskit-file-upload");
  const galleryButton = document.querySelector(".awskit-gallery-button");
  const cancelButton = document.querySelector(".awskit-cancel-button");
  const submitButton = document.querySelector(".awskit-submit-button");
  const imagePreview = document.querySelector(".awskit-image-preview");
  const searchForm = document.querySelector(".awskit-search-form");

  const selectedMode = document.querySelector(".awskit-selected-mode");
  const uploadMode = document.querySelector(".awskit-upload-mode");
  const loadingMode = document.querySelector(".awskit-loading-mode");

  galleryButton.addEventListener("click", () => {
    fileUpload.click();
  });

  cancelButton.addEventListener("click", () => {
    uploadMode.style.display = "flex";
    selectedMode.style.display = "none";
    fileUpload.value = "";
  });

  searchForm.addEventListener("submit", (event) => {
    event.preventDefault();
    selectedMode.style.display = "none";
    uploadMode.style.display = "none";
    loadingMode.style.display = "block";

    const formData = new FormData(searchForm);

    fetch(woocommerce_params.ajax_url + "?action=awskit_upload_search_image", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        window.location.href = data.redirect_url;
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  fileUpload.addEventListener("change", (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onloadend = () => {
      imagePreview.style.backgroundImage = "url(" + reader.result + ")";
      uploadMode.style.display = "none";
      selectedMode.style.display = "flex";
    };

    if (file) {
      reader.readAsDataURL(file);
    } else {
      imagePreview.style.backgroundImage = "";
    }
  });
});
