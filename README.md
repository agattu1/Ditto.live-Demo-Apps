# Ditto.live-Demo-Apps
Ditto is cloud computing company which does sync w/o internet. They prioritize peer to peer networking rather than having and pulling from a cloud base.

## TODO
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

