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


### **Key Characteristics of Your Custom CRM**
Based on your code and database structure, your CRM appears to be designed better than off the shelf CRM b/c it is tailored software solution designed specifically to manage Ditto organization's interactions w current & potential customers!

1. **Sales Pipeline Management**
   - Tracks leads, opportunities, and won customers
   - Manures tasks and notes for sales reps
   - 
2. **Contact Management**
   - Stores detailed contact info (names, emails, companies)
   - Tracks communication history (notes table)
   - Manures referral sources and background info

3. **User Roles & Permissions**
   - Distinguishes between sales reps, managers, and admins
   - Controls access to features (e.g., only managers see "Customers/Won")


Not fully synced with the ditto.live experience just yet but I was planning to knock that out b4 I came across doing DemoApp

Small Peer setup is completed (embedded local-first database w/o the Ditto SDK)
No JSON DB , I provide a SYSTEMATIC Schema upfront w MySQL!

custom CRM allows:

Searching Pillsbury → Finds Linda DeCastro

Viewing her record → Shows project details

Checking linked notes → Reveals call history!
