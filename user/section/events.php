<!-- Events Section -->
<section id="events" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-primary mb-4">Upcoming Events</h2>
            <div class="w-20 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            // Get upcoming events
            $query = mysqli_query($conn, "SELECT * FROM event WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 3");

            if ($query && mysqli_num_rows($query) > 0) {
                while ($event = mysqli_fetch_assoc($query)) {
                    $date = date('M d', strtotime($event['tanggal']));
                    ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        <div class="relative">
                            <?php if (!empty($event['gambar'])): ?>
                                <img src="../img/event_img/<?php echo htmlspecialchars($event['gambar']); ?>" 
                                     alt="<?php echo htmlspecialchars($event['nama']); ?>" 
                                     class="w-full h-64 object-cover">
                            <?php else: ?>
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-4 right-4 bg-secondary text-primary px-3 py-1 rounded-full text-sm font-semibold">
                                <?php echo $date; ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-primary mb-2"><?php echo htmlspecialchars($event['nama']); ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['deskripsi']); ?></p>
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2 text-secondary"></i>
                                    <span><?php echo htmlspecialchars($event['tempat']); ?></span>
                                </div>
                                <?php if ($event['status'] == 'open'): ?>
                                    <button onclick="openRegistrationModal(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars($event['nama']); ?>')" 
                                            class="w-full bg-secondary text-primary py-2 rounded-full font-semibold hover:bg-opacity-90 transition-colors duration-300 flex items-center justify-center">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Daftar Sekarang
                                    </button>
                                <?php else: ?>
                                    <div class="w-full bg-red-500 text-white py-2 rounded-full font-semibold text-center">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Pendaftaran Ditutup
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-span-3 text-center py-8">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-primary mb-2">No Upcoming Events</h3>
                        <p class="text-gray-600">Check back later for new events!</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- Registration Modal -->
<div id="registrationModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative">
            <button onclick="closeRegistrationModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="p-6">
                <h2 id="modalEventTitle" class="text-2xl font-bold text-primary mb-4"></h2>
                <form id="registrationForm" onsubmit="submitRegistration(event)" class="space-y-4">
                    <input type="hidden" id="eventId" name="event_id">
                    <div id="formFields" class="space-y-4">
                        <!-- Dynamic form fields will be inserted here -->
                    </div>
                    <button type="submit" class="w-full bg-secondary text-primary py-2 rounded-full font-semibold hover:bg-opacity-90 transition-colors duration-300">
                        Submit Registration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
async function openRegistrationModal(eventId, eventName) {
    const modal = document.getElementById('registrationModal');
    const formFields = document.getElementById('formFields');
    const modalTitle = document.getElementById('modalEventTitle');
    document.getElementById('eventId').value = eventId;
    modalTitle.textContent = 'Register for ' + eventName;
    
    // Fetch form fields
    try {
        const response = await fetch(`get_form_fields.php?event_id=${eventId}`);
        const fields = await response.json();

        // Check for error in response
        if (fields.error) {
            alert('Error: ' + fields.error);
            return;
        }
        
        // Clear existing fields
        formFields.innerHTML = '';
        
        // Add fields
        fields.forEach(field => {
            const fieldDiv = document.createElement('div');
            fieldDiv.innerHTML = `
                <label class="block text-gray-700 mb-2">
                    ${field.nama_field}${field.wajib ? ' <span class="text-red-500">*</span>' : ''}
                </label>
                <input type="${field.tipe_field}" 
                       name="field[${field.id}]" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-secondary"
                       ${field.wajib ? 'required' : ''}>
            `;
            formFields.appendChild(fieldDiv);
        });
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        console.log('Registration form loaded successfully for event ID:', eventId);
    } catch (error) {
        console.error('Error fetching form fields:', error);
        alert('Error loading registration form. Please try again.');
    }
}

function closeRegistrationModal() {
    const modal = document.getElementById('registrationModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

async function submitRegistration(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch('proses_pendaftaran.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Registration successful!');
            closeRegistrationModal();
        } else {
            alert(result.message || 'Registration failed. Please try again.');
        }
    } catch (error) {
        console.error('Error submitting registration:', error);
        alert('Error submitting registration. Please try again.');
    }
}

// Close modal when clicking outside
document.getElementById('registrationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegistrationModal();
    }
});
</script>
