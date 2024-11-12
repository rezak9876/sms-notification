#!/bin/bash

# نام فایل خروجی README.md
README_FILE="README.md"

# حذف فایل موجود و ساخت فایل README.md جدید
rm -f $README_FILE
touch $README_FILE

# اضافه کردن محتوای فایل README.md
echo "# **rezak/laravel-sms-channel**" >> $README_FILE
echo "" >> $README_FILE
echo "A Laravel package that provides a flexible SMS channel integration supporting multiple SMS services, including Kavenegar, to send SMS notifications." >> $README_FILE
echo "" >> $README_FILE

echo "## **Installation**" >> $README_FILE
echo "" >> $README_FILE
echo "You can install the package via Composer:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`bash" >> $README_FILE
echo "composer require rezak/laravel-sms-channel:dev-master" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "## **Publishing Vendor Files**" >> $README_FILE
echo "" >> $README_FILE
echo "After installing, publish the configuration file by running the following command:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`bash" >> $README_FILE
echo "php artisan vendor:publish --provider=\"Rezak\\\\SMSNotification\\\\Providers\\\\SMSNotificationServiceProvider\"" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE
echo "This command will publish the package's configuration files into your Laravel project, allowing you to customize the SMS service settings as needed." >> $README_FILE
echo "" >> $README_FILE

echo "## **Configuration**" >> $README_FILE
echo "" >> $README_FILE
echo "Add the following to your \`config/services.php\`:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "<?php" >> $README_FILE
echo "" >> $README_FILE
echo "return [" >> $README_FILE
echo "    'default' => env('SMS_DRIVER', 'mock')," >> $README_FILE
echo "    'drivers' => [" >> $README_FILE
echo "        'mock' => [" >> $README_FILE
echo "            'class' => \\\\Rezak\\\\SMSNotification\\\\Services\\\\SMSService\\\\MockSMSService::class," >> $README_FILE
echo "        ]," >> $README_FILE
echo "        'kavenegar' => [" >> $README_FILE
echo "            'class' => \\\\Rezak\\\\SMSNotification\\\\Services\\\\SMSService\\\\KavenegarSMSService::class," >> $README_FILE
echo "            'token' => env('KAVENEGAR_API_TOKEN')," >> $README_FILE
echo "            'sender'=> \"10004346\"" >> $README_FILE
echo "        ]" >> $README_FILE
echo "    ]," >> $README_FILE
echo "];" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "## **Usage**" >> $README_FILE
echo "" >> $README_FILE
echo "### **1. Add the \`routeNotificationForSMS\` method to your User model**" >> $README_FILE
echo "" >> $README_FILE
echo "In order to use the SMS channel for notifications, you need to add the following method to your \`User\` model:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "public function routeNotificationForSMS()" >> $README_FILE
echo "{" >> $README_FILE
echo "    return \$this->phone;" >> $README_FILE
echo "}" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "This method should return the phone number of the user." >> $README_FILE
echo "" >> $README_FILE

echo "### **2. Create a Notification**" >> $README_FILE
echo "" >> $README_FILE
echo "Create a notification class to send an OTP (or any SMS message). Here's an example:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "<?php" >> $README_FILE
echo "" >> $README_FILE
echo "namespace App\\\\Notifications;" >> $README_FILE
echo "" >> $README_FILE
echo "use Illuminate\\\\Notifications\\\\Notification;" >> $README_FILE
echo "use Rezak\\\\SMSNotification\\\\Messages\\\\SMSMessage;" >> $README_FILE
echo "" >> $README_FILE
echo "class SendOtpNotification extends Notification" >> $README_FILE
echo "{" >> $README_FILE
echo "    public function via(object \$notifiable): array" >> $README_FILE
echo "    {" >> $README_FILE
echo "        return ['SMS'];" >> $README_FILE
echo "    }" >> $README_FILE
echo "" >> $README_FILE
echo "    public function toSMS(object \$notifiable): SMSMessage" >> $README_FILE
echo "    {" >> $README_FILE
echo "        \$message = new SMSMessage();" >> $README_FILE
echo "        \$message->setTemplate('test_template')" >> $README_FILE
echo "                ->setData(['param1' => 'value1']);" >> $README_FILE
echo "" >> $README_FILE
echo "        return \$message;" >> $README_FILE
echo "    }" >> $README_FILE
echo "}" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "### **3. Sending the Notification**" >> $README_FILE
echo "" >> $README_FILE
echo "Send the notification to a specific user:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "\$user = User::find(1);" >> $README_FILE
echo "\$otpCode = rand(100000, 999999);" >> $README_FILE
echo "\$user->notify(new SendOtpNotification(\$otpCode));" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "Alternatively, send the notification to multiple phone numbers:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "use Illuminate\\\\Support\\\\Facades\\\\Notification;" >> $README_FILE
echo "" >> $README_FILE
echo "Notification::route('SMS', ['first_phone', 'second_phone'])" >> $README_FILE
echo "    ->notify(new SendOtpNotification('otp code'));" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "### **4. Customizing the SMS Message**" >> $README_FILE
echo "" >> $README_FILE
echo "Use predefined templates (via \`setTemplate()\`) or send custom content (via \`setContent()\`)." >> $README_FILE
echo "" >> $README_FILE
echo "Example:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`php" >> $README_FILE
echo "\$message = new SMSMessage();" >> $README_FILE
echo "\$message->setContent('This is a test message.');" >> $README_FILE
echo "return \$message;" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "## **Supported SMS Services**" >> $README_FILE
echo "" >> $README_FILE
echo "- **Kavenegar**: API integration for sending SMS messages." >> $README_FILE
echo "" >> $README_FILE
echo "You can extend the package to support more SMS services by adding the appropriate driver." >> $README_FILE
echo "" >> $README_FILE

echo "## **Environment Configuration**" >> $README_FILE
echo "" >> $README_FILE
echo "Make sure to add your API key to the \`.env\` file:" >> $README_FILE
echo "" >> $README_FILE
echo "\`\`\`env" >> $README_FILE
echo "KAVENEGAR_API_KEY=your-api-key" >> $README_FILE
echo "\`\`\`" >> $README_FILE
echo "" >> $README_FILE

echo "## **License**" >> $README_FILE
echo "" >> $README_FILE
echo "This package is open-sourced software licensed under the MIT license." >> $README_FILE

echo "README.md file created successfully!"
