<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - CMC Clinic</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #1a3a52 0%, #3498db 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 20px;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #1a3a52;
        }
        
        .message {
            font-size: 14px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .otp-container {
            background-color: #f9f9f9;
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #3498db;
            font-family: 'Courier New', monospace;
            letter-spacing: 4px;
            margin: 10px 0;
        }
        
        .expiry-info {
            font-size: 12px;
            color: #f39c12;
            margin-top: 15px;
        }
        
        .instructions {
            background-color: #e7f3ff;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #0066cc;
        }
        
        .security-note {
            background-color: #fee2e2;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #c0392b;
        }
        
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        
        .footer p {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Email Verification</h1>
            <p>CMC School Clinic Management System</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">Hello <?php echo e($name); ?>,</p>
            
            <p class="message">
                Welcome to CMC School Clinic Management System! Please verify your email address to complete your registration and activate your account.
            </p>
            
            <!-- OTP Display -->
            <div class="otp-container">
                <div class="otp-label">Your One-Time Password (OTP)</div>
                <div class="otp-code"><?php echo e($otp); ?></div>
                <div class="expiry-info">
                    ⏱️ This code expires in <?php echo e($expiresInMinutes); ?> minutes
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <strong>📋 What to do next:</strong><br>
                1. Go to the OTP verification page<br>
                2. Enter this 6-digit code<br>
                3. Complete your registration<br>
                4. Wait for admin approval to log in
            </div>
            
            <!-- Security Note -->
            <div class="security-note">
                <strong>🔒 Security Notice:</strong><br>
                Never share this OTP with anyone. CMC Clinic staff will never ask for your OTP via email or phone. If you did not request this email, please ignore it.
            </div>
            
            <p class="message">
                If you have any questions or need assistance, please contact the CMC Clinic directly.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Carmen Municipal College School Clinic. All rights reserved.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\emails\send-otp.blade.php ENDPATH**/ ?>