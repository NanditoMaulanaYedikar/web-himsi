<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(__DIR__, 2);
include $root_path . '/config/db.php';

// Fetch contact messages from the database
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Get total messages count
$total_messages = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Admin - Contact Messages</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <div class="flex">
        <?php include __DIR__ . '/../sidebar.php'; ?>
        <main class="flex-1 ml-64 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Contact Messages</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Total Messages: <span class="font-semibold"><?php echo $total_messages; ?></span>
                        </span>
                    </div>
                </div>

                <?php if (mysqli_num_rows($result) === 0): ?>
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-gray-500 mb-4">
                            <i class="fas fa-inbox text-4xl"></i>
                        </div>
                        <p class="text-gray-600">No contact messages found.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subject
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Message
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                                                        <span class="text-primary font-semibold"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($row['name']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="hover:text-primary">
                                                    <?php echo htmlspecialchars($row['email']); ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($row['subject']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('d M Y H:i', strtotime($row['created_at'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="viewMessage(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="text-primary hover:text-primary-dark mr-3">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="deleteMessage(<?php echo $row['id']; ?>)" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Message View Modal -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle"></h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modalMessage"></p>
                </div>
                <div class="items-center px-4 py-3 flex justify-between">
                    <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-4 py-2 bg-primary text-white text-base font-medium rounded-md shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function viewMessage(message) {
        const modal = document.getElementById('messageModal');
        const title = document.getElementById('modalTitle');
        const content = document.getElementById('modalMessage');
        
        title.textContent = message.subject;
        content.innerHTML = `
            <p class="mb-2"><strong>From:</strong> ${message.name} (${message.email})</p>
            <p class="mb-2"><strong>Date:</strong> ${new Date(message.created_at).toLocaleString()}</p>
            <hr class="my-3">
            <p>${message.message.replace(/\n/g, '<br>')}</p>
        `;
        
        modal.classList.remove('hidden');
    }

    function deleteMessage(id) {
        if (confirm('Are you sure you want to delete this message?')) {
            // Show loading state
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            // Send delete request
            const formData = new FormData();
            formData.append('id', id);

            fetch('delete.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    const row = button.closest('tr');
                    row.remove();
                    
                    // Update total count
                    const totalSpan = document.querySelector('.text-sm.text-gray-600 .font-semibold');
                    const currentTotal = parseInt(totalSpan.textContent);
                    totalSpan.textContent = currentTotal - 1;

                    // Show success message
                    alert('Message deleted successfully');
                } else {
                    throw new Error(data.message || 'Failed to delete message');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Failed to delete message. Please try again.');
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
    }

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('messageModal').classList.add('hidden');
    });
    </script>
</body>
</html>
