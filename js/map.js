document.addEventListener("DOMContentLoaded", function () {
  const mapContainer = document.getElementById("custom-places");

  if (mapContainer) {
      mapContainer.innerHTML = `
          <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3504.195210259066!2d77.21747777416553!3d28.563900287196457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce268198fc4f3%3A0xab8ae3cd6579449e!2sAIRCONNECT%20INFOSYSTEMS%20Pvt%20Ltd!5e0!3m2!1sen!2sin!4v1744098518113!5m2!1sen!2sin" 
              width="100%" 
              height="350" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
          </iframe>
      `;
  }
});
