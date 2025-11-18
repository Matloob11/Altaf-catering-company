<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .booking-card { background: #f9f9f9; border-radius: 10px; padding: 25px; margin: 20px 0; border: 2px solid #9C27B0; }
        .info-row { display: flex; padding: 12px 0; border-bottom: 1px solid #e0e0e0; }
        .info-label { font-weight: bold; color: #9C27B0; min-width: 140px; }
        .info-value { color: #333; flex: 1; }
        .priority-badge { background: #f44336; color: white; padding: 8px 20px; border-radius: 20px; font-size: 13px; display: inline-block; margin-bottom: 20px; font-weight: bold; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 13px; }
        .footer a { color: #9C27B0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 New Event Booking!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Action Required</p>
        </div>
        
        <div class="content">
            <div class="priority-badge">🔥 HIGH PRIORITY</div>
            
            <h2 style="color: #333; margin-top: 0;">Booking Details</h2>
            
            <div class="booking-card">
                <div class="info-row">
                    <div class="info-label">👤 Customer Name:</div>
                    <div class="info-value"><?php echo htmlspecialchars($name); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📧 Email:</div>
                    <div class="info-value">
                        <a href="mailto:<?php echo htmlspecialchars($email); ?>" style="color: #9C27B0;">
                            <?php echo htmlspecialchars($email); ?>
                        </a>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📱 Phone:</div>
                    <div class="info-value">
                        <a href="tel:<?php echo htmlspecialchars($phone); ?>" style="color: #9C27B0;">
                            <?php echo htmlspecialchars($phone); ?>
                        </a>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">🎊 Event Type:</div>
                    <div class="info-value"><strong><?php echo htmlspecialchars($event_type); ?></strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📅 Event Date:</div>
                    <div class="info-value"><strong style="color: #f44336;"><?php echo htmlspecialchars($event_date); ?></strong></div>
                </div>
                
                <div class="info-row" style="border-bottom: none;">
                    <div class="info-label">👥 Number of Guests:</div>
                    <div class="info-value"><strong><?php echo htmlspecialchars($guests); ?></strong></div>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
            <div style="background: #fff8f0; border-left: 4px solid #FFC107; padding: 20px; margin: 20px 0; border-radius: 5px;">
                <strong style="color: #F57C00; display: block; margin-bottom: 10px;">💬 Additional Message:</strong>
                <?php echo nl2br(htmlspecialchars($message)); ?>
            </div>
            <?php endif; ?>
            
            <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong style="color: #2E7D32;">🕐 Received:</strong> <?php echo $date; ?>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="mailto:<?php echo htmlspecialchars($email); ?>" 
                   style="background: #9C27B0; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    📧 Reply via Email
                </a>
                <a href="tel:<?php echo htmlspecialchars($phone); ?>" 
                   style="background: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    📞 Call Customer
                </a>
            </div>
            
            <div style="background: #fff3e0; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: center;">
                <p style="margin: 0; font-size: 14px; color: #E65100;">
                    ⚡ <strong>Quick Action Required:</strong> Contact the customer within 2 hours for best conversion rate!
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Altaf Catering Admin Panel</strong></p>
            <p>MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan</p>
            <p>📞 <a href="tel:+923039907296">+923039907296</a> | 📧 <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
            <p style="margin-top: 15px; opacity: 0.7;">&copy; <?php echo date('Y'); ?> Altaf Catering. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
