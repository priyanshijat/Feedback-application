<?php
/**
 * User Feedback Application
 * Main application file
 */

require_once 'config/config.php';

// Initialize messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        // Prepare and execute insert query
        $stmt = $mysqli->prepare("INSERT INTO feedback (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        
        if ($stmt === false) {
            $error_message = "Prepare failed: " . $mysqli->error;
        } else {
            $stmt->bind_param("sss", $name, $email, $message);
            
            if ($stmt->execute()) {
                $success_message = "Thank you! Your feedback has been submitted successfully.";
                // Clear form fields
                $name = $email = $message = '';
            } else {
                $error_message = "Error submitting feedback: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Fetch all feedback from database
$feedbacks = [];
$result = $mysqli->query("SELECT id, name, email, message, created_at FROM feedback ORDER BY created_at DESC LIMIT 10");

if ($result) {
    $feedbacks = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1em;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .feedback-list {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .feedback-list h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .feedback-item {
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .feedback-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .feedback-item-name {
            font-weight: 600;
            color: #333;
        }
        
        .feedback-item-date {
            font-size: 0.9em;
            color: #999;
        }
        
        .feedback-item-email {
            color: #667eea;
            font-size: 0.95em;
            margin-bottom: 10px;
        }
        
        .feedback-item-message {
            color: #555;
            line-height: 1.6;
        }
        
        .no-feedback {
            text-align: center;
            color: #999;
            padding: 20px;
        }
        
        .status-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 User Feedback System</h1>
            <p>Share your valuable feedback with us</p>
        </div>
        
        <div class="form-container">
            <h2 style="margin-bottom: 20px; color: #333;">Submit Your Feedback</h2>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">✓ <?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">✗ <?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Feedback</label>
                    <textarea id="message" name="message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                </div>
                
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
        
        <div class="feedback-list">
            <h2>Recent Feedback <span class="status-badge"><?php echo count($feedbacks); ?> Submitted</span></h2>
            
            <?php if (empty($feedbacks)): ?>
                <div class="no-feedback">
                    <p>No feedback submitted yet. Be the first to share your thoughts!</p>
                </div>
            <?php else: ?>
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-item">
                        <div class="feedback-item-header">
                            <span class="feedback-item-name"><?php echo htmlspecialchars($feedback['name']); ?></span>
                            <span class="feedback-item-date"><?php echo date('M d, Y h:i A', strtotime($feedback['created_at'])); ?></span>
                        </div>
                        <div class="feedback-item-email">📧 <?php echo htmlspecialchars($feedback['email']); ?></div>
                        <div class="feedback-item-message"><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
