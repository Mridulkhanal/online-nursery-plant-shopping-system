<?php
session_start();
include 'db_connect.php';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Server-side validation (aligned with contact.js)
    if (empty($name) || strlen($name) < 2) {
        $_SESSION['error_message'] = "Name must be at least 2 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
    } elseif (empty($subject) || strlen($subject) < 2) {
        $_SESSION['error_message'] = "Subject must be at least 2 characters.";
    } elseif (empty($message) || strlen($message) < 10) {
        $_SESSION['error_message'] = "Message must be at least 10 characters.";
    } else {
        // Insert into database
        $db_success = false;
        $sql = "INSERT INTO inquiries (name, email, subject, message, date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $_SESSION['error_message'] = "Database error: Unable to prepare statement.";
            error_log("Prepare failed: " . $conn->error);
        } else {
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            if ($stmt->execute()) {
                $db_success = true;
            } else {
                $_SESSION['error_message'] = "Failed to store message in database.";
                error_log("Insert failed: " . $stmt->error);
            }
            $stmt->close();
        }

        // Send to Web3Forms
        $web3_success = false;
        $access_key = "6f0ba53d-89a7-4579-bb90-14fca47ea766"; // TODO: Store in .env
        $payload = [
            'access_key' => $access_key,
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'redirect' => 'http://localhost/OnlineNurserySystem/contact.php'
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.web3forms.com/submit",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        // Log Web3Forms response
        file_put_contents('web3forms.log', "HTTP Code: $http_code\nResponse: $response\nError: $curl_error\n", FILE_APPEND);

        if ($response && $http_code == 200) {
            $response_data = json_decode($response, true);
            if (isset($response_data['success']) && $response_data['success']) {
                $web3_success = true;
            } else {
                $_SESSION['error_message'] = "Web3Forms submission failed: " . ($response_data['message'] ?? 'Unknown error');
                error_log("Web3Forms failed: " . $response);
            }
        } else {
            $_SESSION['error_message'] = "Web3Forms request failed: " . ($curl_error ?: "HTTP $http_code");
            error_log("Web3Forms cURL error: $curl_error, HTTP: $http_code");
        }

        // Set success message if at least one succeeded
        if ($db_success || $web3_success) {
            $_SESSION['success_message'] = "Message sent successfully! We'll get back to you soon.";
            if (!$db_success) {
                $_SESSION['error_message'] = "Message sent to Web3Forms but not stored in database.";
            }elseif (!$web3_success) {
                $_SESSION['error_message'] = "Message stored in database but not sent to Web3Forms.";
            }
        }
    }
    $conn->close();
    
    // Redirect to contact.php
    header("Location: ../contact.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: ../contact.php");
    exit();
}
?>
</xArtifact>

**Changes**:
- Added cURL request to `https://api.web3forms.com/submit` with `access_key`, form fields, and redirect URL.
- Logged Web3Forms responses to `web3forms.log` for debugging.
- Tracked success/failure for both database and Web3Forms submissions.
- Set success message if either succeeds, with partial error messages if one fails.
- Kept validation aligned with `contact.js` (name ≥ 2, valid email, subject ≥ 2, message ≥ 10).
- Hardcoded `access_key` (recommend moving to `.env` in production).

**Save Location**: `C:\xampp\htdocs\OnlineNurserySystem\php\send_contact.php`

#### Step 4: Ensure `admin_inquiries.php` and `respond_inquiry.php`
These files (from the prior response) are already updated to display inquiries and handle responses. Verify they’re unchanged to maintain functionality:
- **admin_inquiries.php** (artifact ID `5ff3e2a5-b452-4e01-a363-c044a2cf440f`, version ID `951a93ab-847f-4a55-be60-485aeb8dcf66`):
  - Displays inquiries with “Status” (Pending/Responded).
  - Links to `respond_inquiry.php`.
- **respond_inquiry.php** (artifact ID `b2f955db-c135-4c70-a37f-e9521b74a752`, version ID `e1c52a1b-8792-407f-862c-9a382bc861f7`):
  - Shows inquiry details and allows response updates.
- **Verification**:
  - Ensure files are at:
    - `C:\xampp\htdocs\OnlineNurserySystem\admin_inquiries.php`
    - `C:\xampp\htdocs\OnlineNurserySystem\respond_inquiry.php`
  - Check contents match prior artifacts (e.g., “Status” column in `admin_inquiries.php`).

#### Step 5: Update `contact.js`
Ensure client-side validation matches `send_contact.php` and Web3Forms requirements.

<xaiArtifact artifact_id="11f76534-534e-4333-9913-6a0b4c65dcf2" artifact_version_id="b614f37d-3007-45b1-b4e2-a9a2809fda1a" title="contact.js" contentType="text/javascript">
function validateContactForm() {
    let name = document.getElementById('name');
    let email = document.getElementById('email');
    let subject = document.getElementById('subject');
    let message = document.getElementById('message');
    let isValid = true;

    clearError(name);
    clearError(email);
    clearError(subject);
    clearError(message);

    if (!name.value.trim()) {
        showError(name, 'Name is required.');
        isValid = false;
    } else if (name.value.trim().length < 2) {
        showError(name, 'Name must be at least 2 characters.');
        isValid = false;
    }

    if (!email.value.trim()) {
        showError(email, 'Email is required.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        showError(email, 'Please enter a valid email address.');
        isValid = false;
    }

    if (!subject.value.trim()) {
        showError(subject, 'Subject is required.');
        isValid = false;
    } else if (subject.value.trim().length < 2) {
        showError(subject, 'Subject must be at least 2 characters.');
        isValid = false;
    }

    if (!message.value.trim()) {
        showError(message, 'Message is required.');
        isValid = false;
    } else if (message.value.trim().length < 10) {
        showError(message, 'Message must be at least 10 characters.');
        isValid = false;
    }

    return isValid;
}

function showError(input, message) {
    clearError(input);
    const error = document.createElement('div');
    error.className = 'error';
    error.textContent = message;
    error.style.color = '#dc3545';
    error.style.marginTop = '0.5em';
    error.style.fontSize = '0.9em';
    input.parentElement.appendChild(error);
    input.style.borderColor = '#dc3545';
}

function clearError(input) {
    const error = input.parentElement.querySelector('.error');
    if (error) {
        error.remove();
    }
    input.style.borderColor = '#ddd';
}
</xArtifact>

**Changes**:
- Unchanged from prior update, as validation (name ≥ 2, valid email, subject ≥ 2, message ≥ 10) aligns with `send_contact.php` and Web3Forms.
- Confirmed self-contained `showError` and `clearError` functions.

**Save Location**: `C:\xampp\htdocs\OnlineNurserySystem\js\contact.js`

#### Step 6: Verify `thank_you.php` (Optional)
Web3Forms allows a custom redirect (`thank_you.php`), but we’re redirecting to `contact.php` in `send_contact.php` for consistency. If `thank_you.php` is required:
1. Create `thank_you.php`:
<xaiArtifact artifact_id="13d29477-65e3-4970-88d6-ae4881bdebf4" artifact_version_id="27a8cc5b-e32a-46e8-935c-ddbaf7644f59" title="thank_you.php" contentType="text/php">
<?php
session_start();
$_SESSION['success_message'] = "Message sent successfully! We'll get back to you soon.";
header("Location: contact.php");
exit();
?>