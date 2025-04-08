document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("domain-form");
  const domainInput = document.getElementById("domain-khushi");
  const resultDiv = document.getElementById("result");

  if (!form || !domainInput || !resultDiv) {
    console.error("One or more form elements not found.");
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const domain = domainInput.value.trim();
    if (domain === "") {
      resultDiv.innerHTML = `<p style="color: red;">Please enter a domain name.</p>`;
      return;
    }

    const extensions = [];
    ["com", "net", "org", "tv", "info"].forEach((ext) => {
      const checkbox = document.getElementById(`domain${ext}`);
      if (checkbox && checkbox.checked) {
        extensions.push(`.${ext}`);
      }
    });

    if (extensions.length === 0) {
      resultDiv.innerHTML = `<p style="color: red;">Please select at least one extension.</p>`;
      return;
    }

    // Clear previous results
    resultDiv.innerHTML = "Checking availability...";

    try {
      const response = await fetch("domain.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ domain, extensions }),
      });

      const data = await response.json();
      if (data.results && Array.isArray(data.results)) {
        resultDiv.innerHTML = data.results
          .map((item) => {
            const icon = item.available ? "✅" : "❌";
            const msg = item.available
              ? `<span style="color:green">${item.domain} is available ${icon}</span>`
              : `<span style="color:red">${item.domain} is not available ${icon}</span>`;
            return `<p>${msg}</p>`;
          })
          .join("");
      } else {
        resultDiv.innerHTML = `<p style="color:red;">Unexpected server response.</p>`;
        console.error("Unexpected server response", data);
      }
    } catch (err) {
      resultDiv.innerHTML = `<p style="color:red;">Error checking domain. Please try again later.</p>`;
      console.error("Error:", err);
    }
  });
});
