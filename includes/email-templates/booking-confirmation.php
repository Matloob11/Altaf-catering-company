<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 32px; }
        .content { padding: 40px 30px; }
        .booking-summary { background: #f0f8ff; border-radius: 10px; padding: 25px; margin: 20px 0; border: 2px dashed #4CAF50; }
        .summary-row { padding: 10px 0; border-bottom: 1px solid #d0e8ff; }
        .summary-label { font-weight: bold; color: #2E7D32; }
        .next-steps { background: #fff8f0; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 13px; }
        .footer a { color: #4CAF50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 60px; margin-bottom: 10px;">🎊</div>
            <h1>Booking Confirmed!</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px; opacity: 0.9;">We're excited to cater your event</p>
        </div>
        
        <div class="content">
            <h2 style="color: #333;">Dear <?php echo htmlspecialchars($name); ?>,</h2>
            
            <p style="font-size: 16px; line-height: 1.8; color: #555;">
                Great news! Your event booking with <strong>Altaf Catering</strong> has been confirmed. 
                We're thrilled to be part of your special occasion!
            </p>
            
            <div class="booking-summary">
                <h3 style="color: #2E7D32; margin-top: 0;">📋 Your Booking Summary</h3>
                
                <div class="summary-row">
                    <span class="summary-label">Event Type:</span><br>
                    <strong style="font-size: 18px; color: #333;"><?php echo htmlspecialchars($event_type); ?></strong>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Event Date:</span><br>
                    <strong style="font-size: 18px; color: #f44336;"><?php echo htmlspecialchars($event_date); ?></strong>
                </div>
                
                <div class="summary-row" style="border-bottom: none;">
                    <span class="summary-label">Number of Guests:</span><br>
                    <strong style="font-size: 18px; color: #333;"><?php echo htmlspecialchars($guests); ?> people</strong>
                </div>
            </div>
            
            <div class="next-steps">
                <h3 style="color: #F57C00; margin-top: 0;">📞 What Happens Next?</h3>
                <ol style="line-height: 2; color: #555;">
                    <li><strong>Confirmation Call:</strong> Our team will contact you within 24 hours</li>
                    <li><strong>Menu Discussion:</strong> We'll help you customize the perfect menu</li>
                    <li><strong>Final Details:</strong> Confirm venue, timing, and special requirements</li>
                    <li><strong>Event Day:</strong> We'll arrive early to set up everything perfectly</li>
                </ol>
            </div>
            
            <div style="background: #e8f5e9; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0; font-size: 15px; color: #2E7D32;">
                    <strong>💡 Pro Tip:</strong> Have questions or want to discuss menu options? 
                    Feel free to call us anytime at <a href="tel:+923039907296" style="color: #2E7D32; font-weight: bold;">+923039907296</a>
                </p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://altafcatering.com/menu.php" 
                   style="background: #4CAF50; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; margin: 5px;">
                    🍽️ View Our Menu
                </a>
                <a href="https://wa.me/923039907296" 
                   style="background: #25D366; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; margin: 5px;">
                    💬 Chat on WhatsApp
                </a>
            </div>
            
            <p style="font-size: 16px; line-height: 1.8; color: #555; text-align: center;">
                Thank you for choosing Altaf Catering!<br>
                We look forward to making your event unforgettable.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Altaf Catering Company</strong></p>
            <p>Professional Catering Services in Pakistan</p>
            <p>MM Farm House Sharif Medical Jati Umrah Road, Karachi</p>
            <p>📞 <a href="tel:+923039907296">+923039907296</a> | 📧 <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
            <p style="margin-top: 15px; opacity: 0.7;">&copy; <?php echo date('Y'); ?> Altaf Catering. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
