Quick-Zap-Admin
===============
**April 2014**
This is a quick admin dashboard for use on small projects. This version has page management and blog management. As well as menu location management. The mySQL ORM is handled through Zap by Shay Anderson. shayanderson.com/projects/zap.htm

### lib/config.php
- set your folder url

### Page Variables
You will see variables to be set on page files. 
- "page\_class"= dynamic simply means I've setup the update function and the database structure to where all thats needed is you change the "page\_var"
- you should keep the database structure for pages the same for each new page when using dynamic class

**Markdown is built in**
In the database, the "\*cache" row holds the HTML

**A File Uploader is also built**
It is set to upload files to "uploads" in your root not in the admin folder so you can block access to the admin folder and leave the public uploads alone. 

**Ready for jQuery UI**
Change the jQuery UI folder to one of your own

**Code Kit was used to Minify Assets**

