# Salla Coding Challenge

This is a challenge project within our technical recruiting process.

Please provide your solution within 7 calendar days. If you need any clarification you can send
an email with your questions.

Make sure your results are not being shared publicly (e.g. no GitHub, no public blog post...).

## Overview

You will be challenged within the following areas of PHP development:

* Basic understanding of PHP's OOP implementation, including interfaces and design patterns
* Namespaces, closures/anonymous functions
* JSON as data format
* MySQL
* ReSTful API Integration
* How to process workloads fast

## About this Project

Attached to this task is a `products.csv` file which contains a list of products to be imported into a table in the database. The current structure of the **products** table is

```sql
CREATE TABLE `products` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) DEFAULT NULL,
`sku` varchar(255) DEFAULT NULL UNIQUE,
`status` varchar(255) DEFAULT NULL,
`variations` text DEFAULT NULL,
`price` decimal(7,2) DEFAULT NULL,
`currency` varchar(20) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

The import process is done using a command attached in the ImportProductsfile.


However, we need to improve the code, database structure, and importing process to tackle these challenges:

**### Task 1. Refactor!**

Restructure the code in the way you think it should be structured. The current code may have some bugs or not be suitable to do the process
Restructure might include splitting code into different files, validating data, writing unit tests, using framework features, or more.


**### Task 2. Delete outdated products!**

The command should soft delete any product which no longer exists in the file (either not in the file or has a flag of deleted). a hint should be added to the deleted record to indicate that product was deleted because of the synchronization process.


**### Task 3: Restructure the data!**

Currently, some products have many variations based on some options like ( color, size, .. ) and these variations ( Ex. small - blue, large - blue, small - black, ... ) are stored without any indication of the quantity or availability of them.
We need to restructure the database to support adding the quantity and availability of each variation.


**### Task 4: Get data from another Source!**

As we extend our services, it’s required to update our products data from a 3rd party supplier
using an API. The endpoint to get product information is:

**https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products**

A daily synchronization process should be done at 12am. You need to develop a solution to this process.


**### Task 5: Speed it up!**

Assume that updating any product will fire many events such as :

- send an email notification to the warehouse about the new quantity
- send emails to some customers who requested notifications when the out-of-stock
    products are available.
- Sending a request to a third-party application to update product data


>**_You do not need to implement these notifications_**

However, assume this process will take around 2 seconds per product. to simulate this scenario, pause the script's execution for these 2 seconds before processing the next product.

Now write a concept to process the products from the feed faster - assume there would be a few hundred thousand rows (but you can try your code on a patch of 200 records for testing)!


Think about query optimization, parallelization and caching.

**General Hints**

- Tasks 1 & 2 are preparation for task 3. So, the expected final database structure is
    required from task 3.
- Using the best code practices, principles, and design patterns is highly recommended.
- It is a pulse if the code is easily extendable. For example, there may be another 3rd
    party API service to be used in the future, we’ll be happy if you can reuse the existing
    code with minimal updates to support that service in the future.
- We may require you to explain and review your code and justify your decisions later.



>All the best
