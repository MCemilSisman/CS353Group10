# Info

In "src" there is the java file, in "cs353" there are php files

Java file is needed to initialize the database and insert tuples. You don't need to do anything with that file if you don't want to insert new tuples. If you do, then you need to download jdbc driver (https://dev.mysql.com/downloads/connector/j/) and add it to your java project as an external library before you can run this java file. It modifies emin_kaplan database on emin.kaplan bilkent dijkstra server

The php part is basically the UI part which is what we will show in the demo, to run it, first download XAMPP (https://www.apachefriends.org/tr/download.html) then add everything inside the folder to xampp/htdocs, run apache on xampp and go to localhost on your browser.
