<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #FE7E00 0%, #FF6B00 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .info-box { background: #f9f9f9; border-left: 4px solid #FE7E00; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .info-box strong { color: #FE7E00; display: block; margin-bottom: 5px; }
        .message-box { background: #fff8f0; border: 1px solid #ffe0b2; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 13px; }
        .footer a { color: #FE7E00; text-decoration: none; }
        .badge { background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Contact Form</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Someone wants to get in touch!</p>
        </div>
        
        <div class="content">
            <div class="badge">NEW INQUIRY</div>
            
            <h2 style="color: #333; margin-top: 0;">Contact Details</h2>
            
            <div class="info-box">
                <strong>👤 Name:</strong>
                <?php echo htmlspecialchars($name); ?>
            </div>
            
            <div class="info-box">
                <strong>📧 Email:</strong>
                <a href="mailto:<?php echo htmlspecialchars($email); ?>" style="color: #FE7E00;">
                    <?php echo htmlspecialchars($email); ?>
                </a>
            </div>
            
            <div class="info-box">
                <strong>📱 Phone:</strong>
                <a href="tel:<?php echo htmlspecialchars($phone); ?>" style="color: #FE7E00;">
                    <?php echo htmlspecialchars($phone); ?>
                </a>
            </div>
            
            <div class="message-box">
                <strong style="color: #FE7E00; display: block; margin-bottom: 10px;">💬 Message:</strong>
                <?php echo nl2br(htmlspecialchars($message)); ?>
            </div>
            
            <div class="info-box">
                <strong>🕐 Received:</strong>
                <?php echo $date; ?>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="mailto:<?php echo htmlspecialchars($email); ?>" 
                   style="background: #FE7E00; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    Reply to Customer
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Altaf Catering Company</strong></p>
            <p>MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan</p>
            <p>📞 <a href="tel:+923039907296">+923039907296</a> | 📧 <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
            <p style="margin-top: 15px; opacity: 0.7;">&copy; <?php echo date('Y'); ?> Altaf Catering. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
