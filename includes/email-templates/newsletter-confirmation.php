<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; padding: 40px 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .benefits { background: #f0f8ff; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .benefit-item { padding: 10px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 13px; }
        .footer a { color: #2196F3; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 60px; margin-bottom: 10px;">📧</div>
            <h1>Welcome to Our Newsletter!</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px; opacity: 0.9;">You're now part of the Altaf Catering family</p>
        </div>
        
        <div class="content">
            <h2 style="color: #333;">Dear <?php echo htmlspecialchars($name); ?>,</h2>
            
            <p style="font-size: 16px; line-height: 1.8; color: #555;">
                Thank you for subscribing to the <strong>Altaf Catering Newsletter</strong>! 
                We're excited to keep you updated with our latest offerings and exclusive deals.
            </p>
            
            <div class="benefits">
                <h3 style="color: #1976D2; margin-top: 0;">🎁 What You'll Receive:</h3>
                
                <div class="benefit-item">
                    ✨ <strong>Exclusive Offers:</strong> Special discounts for newsletter subscribers
                </div>
                <div class="benefit-item">
                    🍽️ <strong>New Menu Items:</strong> Be the first to know about new dishes
                </div>
                <div class="benefit-item">
                    💡 <strong>Catering Tips:</strong> Expert advice for planning perfect events
                </div>
                <div class="benefit-item">
                    📅 <strong>Seasonal Specials:</strong> Limited-time packages and promotions
                </div>
                <div class="benefit-item">
                    🎉 <strong>Event Ideas:</strong> Inspiration for your next celebration
                </div>
            </div>
            
            <div style="background: #fff8f0; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;">
                <p style="margin: 0; font-size: 15px; color: #F57C00;">
                    <strong>🎊 Welcome Bonus:</strong> Get 10% off your first booking! 
                    <br>Use code: <strong style="font-size: 18px; color: #f44336;">WELCOME10</strong>
                </p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://altafcatering.com" 
                   style="background: #2196F3; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                    Explore Our Services
                </a>
            </div>
            
            <p style="font-size: 14px; line-height: 1.6; color: #777; text-align: center; margin-top: 30px;">
                You can unsubscribe at any time by clicking the link at the bottom of our emails.
            </p>
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
