
            document.getElementById("contactform").addEventListener("submit", function(event) {
                event.preventDefault();
                
                let formData = new FormData(this);
                let errorFields = document.querySelectorAll(".error-message");
                errorFields.forEach(field => field.innerHTML = ""); // Clear previous errors
            
                fetch("contact.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "error" && data.errors) {
                        let formElements = this.elements;
                        Object.keys(data.errors).forEach(field => {
                            let inputField = document.querySelector(`[name="${field}"]`);
                            if (inputField) {
                                inputField.nextElementSibling.innerHTML = data.errors[field];
                            }
                        });
                    } else if (data.status === "success") {
                        document.getElementById("message").innerHTML = `<p class="success-message text-success">${data.message}</p>`;
                        document.getElementById("contactform").reset();
                    } else {
                        document.getElementById("message").innerHTML = `<p class="error-message text-danger">${data.message}</p>`;
                    }
                })
                .catch(error => console.error("Error:", error));
            });
           