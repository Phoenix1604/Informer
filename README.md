
# Informer Plugin

A wordpress plugin to send email to admin daily about the published posts along with the post title, meta title and description, and post page speed for assitance in SEO. WebPage Test API is used for measuring the page speed.




## Installation & Configuration

Install plugin with wordpress adnmin panel

```bash
  Step 1: Fork the repo and download the forked repo as ZIP
  Step 2: From Wordpress admin panel upload the zip file in add new plugin option and activate the plugin
```
### SetUp System Corn 
    To use system cron follow the below mentioned steps.
    Step 1: Add following code to wp-config.php file

```bash
    define('DISABLE_WP_CRON', true);
```
    Step 2: Create a crontab file 
```bash 
    "crontab -u {user} -e"
```
    Step 3: Add the following code in crontab file
```bash
     0 0 * * * wget -q -O - http://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1
```
    
