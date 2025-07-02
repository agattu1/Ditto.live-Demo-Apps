# Ditto.live-Demo-Apps
Ditto is cloud computing company which does sync w/o internet. They prioritize peer to peer networking rather than having and pulling from a cloud base.

## TODO

### Ditto Custom CRM
I had to build a CUSTOM CRM for Ditto + Andriod App.
The Custom CRM (customer relationship management) tool was build using the MAMP (Mac, Apache, MySQL, PHP—a pre-packaged local server environment for macOS (though there’s also MAMP Pro for advanced users and Windows versions). It’s like a "one-click" way to run a PHP/MySQL server on your computer without complex setup.)
and LAMP (Linux, Apache, MySQL, PHP) Architecture.

Unlike Java or .NET, PHP (which was build as a helper brother to MySQL) doesn’t require complex setup—just a server (like MAMP/XAMPP) and a text editor.

> PHP is a server-side language—it can’t run in a browser like JavaScript.

When you visit a .php file (e.g., index.php), here’s what happens:

1. Apache receives the request.

2. Apache hands the PHP file to the PHP interpreter (installed in MAMP).

3. PHP runs, talks to MySQL/MongoDB/PostgreSQL if needed.

4. Apache sends back the final HTML to your browser.

> Without Apache (or an alternative like Nginx), PHP files wouldn’t execute—they’d just download as text!
But the thing is, Nginx is more efficeint for high traffic apps. Using Node.js/Python/Ruby → They have their own servers (Express, Django, Rails)

Apache is the "bridge" between your browser and PHP/MySQL.
MAMP bundles Apache so you don’t have to install/config it manually.

1. Once MySQL & MAMP is downloaded add the `CRM-System-WebApp-master` AND `index.php` files into **htdocs** folder
2. Configure MAMP & MySQL
3. When editing the MySQL DB, go to the database folder, open the `install` SQL Text File, edit the content, copy/paste it into Query 1 after opening the MySQL Workbench Connections, select ALL & hit ⚡
4. 
   ![image](https://github.com/user-attachments/assets/f83fcf1e-ce67-4f54-92ca-e060e1b2073c)

### Andriod App

I first played around with the [Inventory demo app](https://github.com/getditto/demoapp-inventory) from Ditto which showed me Ditto real-time sync via peer to peer devices in the mesh of food inventory counter. I had to initalize my App ID and Auth URL from the Ditto Portal to connect via SDK (I called it [outreachable](https://portal.ditto.live/app/outreachable/connect)).
![image](https://github.com/user-attachments/assets/122d50b4-af2e-4e79-b22e-cf7ddd022ec3)

I listed there creds in the `secure/debug_creds.properties` file so Android looks at that .env file. When gradle restores packages, it should auto load in the information from the .env file to the BuildConfig. DittoManager then reads these values and uses them to connect to Ditto.

For more information, see the [build.gradle](https://github.com/getditto/demoapp-inventory/blob/main/Android/app/build.gradle#L20) file.



