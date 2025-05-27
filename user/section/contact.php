<!-- Contact Section -->
<section id="contact" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-primary mb-4">Contact Us</h2>
            <div class="w-20 h-1 bg-secondary mx-auto"></div>
        </div>
        <div id="formMessage"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <div>
                <form id="contactForm" class="space-y-6">
                    <div class="form-group">
                        <label class="block text-gray-700 mb-2" for="name">
                            <i class="fas fa-user text-secondary mr-2"></i>Name
                        </label>
                        <input type="text" name="name" id="name" class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-secondary" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 mb-2" for="email">
                            <i class="fas fa-envelope text-secondary mr-2"></i>Email
                        </label>
                        <input type="email" name="email" id="email" class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-secondary" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 mb-2" for="subject">
                            <i class="fas fa-tag text-secondary mr-2"></i>Subject
                        </label>
                        <input type="text" name="subject" id="subject" class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-secondary" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 mb-2" for="message">
                            <i class="fas fa-comment text-secondary mr-2"></i>Message
                        </label>
                        <textarea name="message" id="message" rows="4" class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-secondary" placeholder="Your message..." required></textarea>
                    </div>
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center justify-center w-full md:w-auto">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>
            <div>
                <div class="bg-gray-100 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-primary mb-4">Get in Touch</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-secondary mt-1 mr-3"></i>
                            <p class="text-gray-600">
                                West Minangkabau Street No.50, RT.1/RW.1,<br>
                                Manggis Village, Setiabudi District,<br>
                                South Jakarta City, DKI Jakarta 12970
                            </p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-secondary mr-3"></i>
                            <a href="mailto:contact@himsi.org" class="text-gray-600 hover:text-secondary">contact@himsi.org</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-secondary mr-3"></i>
                            <a href="tel:+628123456789" class="text-gray-600 hover:text-secondary">+62 812 3456 7890</a>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-primary mb-3">Follow Us</h4>
                        <div class="flex space-x-6">
                            <a href="https://www.instagram.com/himsi_telujkt?igsh=MTZ4NmE4ajZ3MTdqeQ==" class="social-icon text-gray-600 hover:text-secondary transform transition-all duration-300">
                                <i class="fab fa-instagram text-2xl"></i>
                            </a>
                            <a href="https://www.tiktok.com/@himsitelu_jkt?is_from_webapp=1&sender_device=pc" class="social-icon text-gray-600 hover:text-secondary transform transition-all duration-300">
                                <i class="fab fa-tiktok text-2xl"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/himpunan-mahasiwa-sistem-informasi-jakarta/" class="social-icon text-gray-600 hover:text-secondary transform transition-all duration-300">
                                <i class="fab fa-linkedin text-2xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
    submitButton.disabled = true;
    
    fetch('contact_submit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Reset button
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
        
        // Show success message
        const messageDiv = document.getElementById('formMessage');
        messageDiv.className = 'mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg';
        messageDiv.innerHTML = '<p>Your message has been sent successfully! We\'ll get back to you soon.</p>';
        
        // Reset form
        this.reset();
    })
    .catch(error => {
        // Reset button
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
        
        // Show error message
        const messageDiv = document.getElementById('formMessage');
        messageDiv.className = 'mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg';
        messageDiv.innerHTML = '<p>Sorry, there was an error sending your message. Please try again.</p>';
        
        console.error('Error:', error);
    });
});
</script>
