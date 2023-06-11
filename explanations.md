# Explanation.md

## Problem Statement

The problem at hand is to develop a SEO Crawler plugin for WordPress websites. This plugin should be capable of crawling the website homepage, identifying and storing its internal links. It should also create a sitemap, replicate the homepage HTML, schedule regular crawls on an hourly basis, and display the results of the crawl to the admin user.

## Technical Spec

To solve this problem, the following steps are undertaken:

1. **Crawling**: A PHP class `SeoCrawlerCrawl` is implemented which handles the crawling operations. It uses WordPress's `wp_remote_get` function to fetch web page content and `DOMDocument` to parse the HTML and extract the links.

2. **Database Operations**: The `SeoCrawlerDb` class communicates with the WordPress database, storing the results of the crawl.

3. **Sitemap Generation**: As part of the crawling process, a `sitemap.html` file is generated.

4. **Homepage Replication**: The HTML content of the homepage is copied into a `homepage.html` file.

5. **Scheduling**: WordPress's Cron API is used to schedule crawls to occur hourly.

6. **Displaying Results**: An admin page is created where the results of the crawl can be viewed by the admin user.

## Technical Decisions

1. **Object-Oriented Programming**: Using classes and objects allows for better organization and readability of the code. It also makes it easier to manage and update the code in the future.

2. **WordPress Cron API**: This was used for scheduling because it provides a simple and easy way to schedule tasks in WordPress.

## Code Explanation

The code for this plugin is organized into multiple PHP files, each with a specific purpose.

1. `seo-crawler.php`: This is the main plugin file. It loads the necessary files and initializes the plugin.

2. `init.php`: This file handles the initialization of the plugin, including loading the Composer autoloader and rendering views.

3. `SeoCrawlerCrawl.php`: This class handles the crawling operations. It fetches web pages, parses the HTML, and extracts links.

4. `SeoCrawlerDb.php`: This class handles communication with the database, including storing the results of the crawl.

5. `seo-crawler-admin.php`: This file handles the admin functions of the plugin, including creating the admin menu and rendering the settings page.

6. `./src/Utils/SeoCrawlerView.php`: This file handles everything related to views, including rendering views. 

## Achieving the Desired Outcome

With this solution, the admin user can trigger a crawl, view the latest results, and know that the plugin will automatically perform a crawl every hour. The sitemap and homepage replication features provide additional SEO value. This solution effectively fulfills the user story and provides the admin with a powerful tool for improving their website's SEO.

## Final Thoughts

This approach was chosen because it is effective, efficient, and leverages WordPress's built-in functions and APIs. By breaking the problem down into smaller tasks and addressing each one with a specific portion of the code, it was possible to create a comprehensive solution that is easy to understand, manage, and update. This direction is a better solution because it provides the desired functionality while also being robust and maintainable.
