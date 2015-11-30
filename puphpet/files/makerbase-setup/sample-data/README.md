# Sample Data for Development

To update sample data from production:

1. Go to PHPMyAdmin on the production web server and navigate to the Makerbase database.
2. Export DATA ONLY of all tables EXCEPT network_friends, waitlist, waitlist_with_follows.
3. Zip up the resulting makerbase_web.sql file and replace the zip file here in this folder.

To test the data update locally:

1. In Vagrant's Adminer installation, run the SQL teardown/truncate-all.sql to remove all the existing data.
2. SSH into the VM and run the following (also found in makerbase.sh):
```
unzip -o /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql.zip -d /var/www/puphpet/files/makerbase-setup/sample-data/
mysql -u makerbase -pnice2bnice -D makerbase_web < /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql
rm -f /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql
```

3. Make sure none of those commands (especially the second) generated any errors. Fix if it did and repeat.