<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 32px; }
        .checkmark { font-size: 60px; margin-bottom: 10px; }
        .content { padding: 40px 30px; }
        .highlight-box { background: #f0f8ff; border-left: 4px solid #4CAF50; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .cta-button { background: #FE7E00; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0; font-weight: bold; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 13px; }
        .footer a { color: #FE7E00; text-decoration: none; }
        .social-links { margin: 15px 0; }
        .social-links a { color: white; margin: 0 10px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="checkmark">✅</div>
            <h1>Thank You!</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px; opacity: 0.9;">We've received your message</p>
        </div>
        
        <div class="content">
            <h2 style="color: #333;">Dear <?php echo htmlspecialchars($name); ?>,</h2>
            
            <p style="font-size: 16px; line-height: 1.8; color: #555;">
                Thank you for reaching out to <strong>Altaf Catering</strong>! We appreciate your interest in our services.
            </p>
            
            <div class="highlight-box">
                <p style="margin: 0; font-size: 15px; line-height: 1.6;">
                    <strong>📬 What happens next?</strong><br>
                    Our team will review your message and get back to you within <strong>24 hours</strong>. 
                    We're excited to help make your event memorable!
                </p>
            </div>
            
            <p style="font-size: 16px; line-height: 1.8; color: #555;">
                In the meantime, feel free to explore our services:
            </p>
            
            <ul style="font-size: 15px; line-height: 2; color: #555;">
                <li>🍽️ <strong>Menu Options</strong> - Delicious Pakistani & Continental cuisine</li>
                <li>🎉 <strong>Event Packages</strong> - Weddings, Corporate, Parties</li>
                <li>📸 <strong>Gallery</strong> - See our previous events</li>
                <li>⭐ <strong>Testimonials</strong> - What our clients say</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://altafcatering.com" class="cta-button">
                    Visit Our Website
                </a>
            </div>
            
            <div style="background: #fff8f0; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px; color: #666;">
                    <strong>💡 Quick Tip:</strong> Have specific questions? Feel free to call us directly at 
                    <a href="tel:+923039907296" style="color: #FE7E00; text-decoration: none;">+923039907296</a> 
                    or chat with us on WhatsApp!
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Altaf Catering Company</strong></p>
            <p>Professional Catering Services in Pakistan</p>
            <p>MM Farm House Sharif Medical Jati Umrah Road, Karachi</p>
            
            <div class="social-links">
                <a href="https://web.facebook.com/AltafCateringCompany">📘 Facebook</a>
                <a href="https://www.instagram.com/altafcateringcompany/">📷 Instagram</a>
                <a href="https://www.youtube.com/@Altafcateringcompanyy">📺 YouTube</a>
            </div>
            
            <p>📞 <a href="tel:+923039907296">+923039907296</a> | 📧 <a href="mailto:altafcatering@gmail.com">altafcatering@gmail.com</a></p>
            <p style="margin-top: 15px; opacity: 0.7;">&copy; <?php echo date('Y'); ?> Altaf Catering. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
