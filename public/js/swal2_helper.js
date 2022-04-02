function swalLoader(title = "Generating export...", text = "Please wait") {
    Swal.fire({
          title: title,
          text: text,
          allowOutsideClick: false,
          showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading()
        },
    });
}

function swalError() {
    Swal.fire({
      title: "Error!",
      text: "Please report to administrator!",
      icon: "error",
    });
}

function swalSuccess(title = "Finished!") {
    Swal.fire({
      title: title,
      icon: "success",
      timer: 1000,
      showConfirmButton: false,
    });
}