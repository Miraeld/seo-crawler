## Problem Statement

The problem at hand is to create a WordPress plugin that allows an administrator to manually initiate a crawl of their website's homepage and view the internal hyperlinks. The goal is to help the administrator understand the interlinking structure of their website and to better improve their SEO ranking in the future. The plugin is expected to schedule regular crawls on an hourly basis when it's being triggered manually, provide a visual sitemap, and offer a replicated homepage HTML file.

## Technical Spec

To meet the technical requirements and expectations for the plugin, the following aspects are considered:

### Code Implementation

-   The plugin is built using modern object-oriented programming (OOP) principles with PSR standards. Procedural programming is used where appropriate.
-   The code follows the established package template and structure.
-   It adheres to WordPress coding standards and best practices.
-   The plugin is designed to be compatible with multiple PHP versions: 5.6, 7.0, 7.1, 7.2, 7.3, and 7.4.
-   The plugin has been built to work on Wordpress 5.0 and above.
-   The code does not generate errors, warnings, or notices, ensuring a clean execution environment.
-   It does not create new global variables to prevent conflicts with other plugins or themes.

### Data Storage

-   The plugin can be used with a MariaDB or MySQL database for storing temporary crawl results. (*It has been developed using MariaDB 10.5*)
-   The database interactions are handled using appropriate WordPress APIs.

### Plugin Functionality

-   The plugin provides a back-end admin page (settings page) where the administrator can manually trigger a crawl or view latest results.
-   Upon triggering a crawl, the plugin schedules an immediate task and sets it to run automatically every hour.
-   The crawl task performs the following actions:
    -   Deletes the results from the previous crawl, if any, from temporary storage.
    -   Deletes the sitemap.html file, if it exists.
    -   Initiates the crawl process starting from the website's root URL (home page).
    -   Extracts all internal hyperlinks (results) encountered during the crawl.
    -   Temporarily stores the crawl results in the database.
    -   Displays the crawl results on the admin page for the administrator to view.
    -   Saves the home page's .php file as a .html file for replication.
    -   Generates a sitemap.html file that presents the crawl results as a sitemap list structure.
-   When the administrator requests to view the crawl results, the plugin retrieves the results from storage and displays them on the admin page.
-   In the event of an error, the plugin displays an error notice to inform the administrator of the issue and provides guidance on resolving it.
-   On the front-end, the plugin allows visitors to view the sitemap.html page, offering an overview of the website's internal linking structure.

By addressing these technical aspects, the plugin fulfills the specified requirements and ensures a reliable, error-free, and user-friendly experience for the administrator and visitors.


## Technical Decisions

Object-Oriented Programming: The use of classes and objects allows for better organization and readability of the code. This approach also simplifies future code management and updates.

WordPress Cron API: This API was used for scheduling as it provides a simple and efficient way to schedule tasks within WordPress.

## Code Explanation

The code consists of multiple files and components that collectively form a WordPress plugin called "SEO Crawler." The plugin's purpose is to assist administrators in understanding their website's interlinking structure and improving SEO rankings. Let's dive into the different aspects of the code and how they contribute to the functionality.

1.  **Initialization and Administration:** The main entry point of the plugin is the `seo-crawler.php` file. This file serves as the initialization point for the plugin, where necessary dependencies are loaded and hooks are registered with WordPress.
    
    Additionally, the `seo-crawler-admin.php` file handles the administrative functionality of the plugin. It provides settings, UI, and interaction with the WordPress admin interface. This allows the admin to trigger crawls and view results (`seo-crawler-form-handler.php`).
    
2.  **Crawling Functionality:** The crawling functionality is implemented in the `Crawler.php` file within the `Crawl` directory. The `Crawler` class utilizes WordPress's `wp_remote_get` function to fetch the homepage content. It then uses `DOMDocument` to parse the HTML and extract internal links.
    
    During the crawl process, the extracted links are temporarily stored in the database using the `DbTable.php` file in the `Db` directory. This allows the results to be accessed and displayed later.
    
3.  **Sitemap Generation and Homepage Replication:** As part of the crawling process, a `sitemap.html` file is generated. This file provides a visual representation of the website's interlinking structure.
    
    Additionally, the HTML content of the homepage is duplicated into a `homepage.html` file. This file can be accessed separately and serves as a replicated version of the homepage.
    
4.  **Scheduling and Automation:** The plugin utilizes WordPress's Cron API to schedule regular crawls. When an admin triggers a crawl manually, a task is set to run immediately, and subsequent crawls are scheduled to run hourly. This automation ensures that the website's interlinking structure is regularly analyzed and updated.
    
5.  **Displaying Results and User Interface:** The plugin includes an admin page (`results.php`) that allows the admin to view the results of the crawl. The `results.php` file, located in the `views/admin/crawl` directory, fetches the crawl results from the database and presents them in a user-friendly format.
    
6.  **Error Handling and Notifications:** The code includes custom exception classes, such as `CrawlException.php`, `InvalidParameterTypeException.php`, and `UnexpectedStatusCodeException.php`, located in the `Exceptions` directory. These exceptions are thrown and caught when errors or unexpected situations occur during the crawling process. They allow for proper error handling and provide informative error messages to the admin.
    
7. **Utilities**
To improve code organization and promote reusability, the plugin incorporates a set of utility classes and functions located in the `Utils` directory. These utility classes provide common functionalities that are utilized across different parts of the plugin, contributing to a modular and well-structured codebase. They handle tasks such as rendering views, managing notices, and performing other frequently needed operations. By encapsulating these functionalities within dedicated utility classes, the codebase becomes more maintainable, less prone to code duplication, and exhibits cleaner architecture. Although their primary focus is not on directly enhancing the user interface, these utility classes indirectly contribute to overall code quality and facilitate easier code maintenance.


Overall, the code follows an object-oriented approach, utilizing classes, objects, and modular components for better organization, maintainability, and code reuse. The use of WordPress's built-in functions and APIs ensures compatibility with the WordPress ecosystem.

The technical decisions, such as the use of WordPress Cron API for scheduling and the choice of DOMDocument for HTML parsing, were made to leverage existing functionality and optimize performance.

Through this implementation, the SEO Crawler plugin empowers administrators to initiate crawls, view results, and gain insights into their website's interlinking structure. It provides a visual sitemap, replicates the homepage's HTML, and offers automation to ensure regular updates.

This approach not only addresses the problem statement and user story but also adheres to best practices, such as code modularity, error handling, and compatibility with multiple PHP and WordPress versions.  

## Testing Approach

To ensure the reliability and functionality of the SEO Crawler plugin, a comprehensive testing approach was followed. Unit tests were conducted using popular PHP testing frameworks like PHPUnit to verify the individual components' behavior. Mocking and dependency injection techniques were employed to isolate dependencies and simulate different scenarios. Additionally, manual testing was carried out to assess the plugin's usability and ensure a seamless user experience. Through rigorous testing, potential bugs and edge cases were identified and addressed, ensuring a robust and stable plugin implementation.

## Achieving the Desired Outcome

With this solution, the admin user can initiate a crawl, view the latest results, and know that the plugin will automatically perform a crawl every hour. The sitemap and homepage replication features provide additional SEO value. This solution effectively fulfills the user story and equips the admin with a powerful tool for improving their website's SEO.

## Final Thoughts

This approach was chosen for its effectiveness, efficiency, and utilization of WordPress's built-in functions and APIs. By breaking the problem down into smaller tasks and addressing each one with a specific portion of the code, a comprehensive solution was created that is easy to understand, manage, and update. This solution provides the desired functionality while also being robust and maintainable​.