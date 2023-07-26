# Todo List Application - Readme

## Overview

This repository contains the source code for a Todo List application. The application allows users to create, manage, and categorize their to-do lists. It also offers a premium subscription plan through Stripe payment, enabling unlimited todo lists. Without a premium subscription, users are limited to a maximum of 5 todo lists. Additionally, the SQL database file can be downloaded from the `db` folder, and the database name is `todos`.

## Features

- Create, update, and delete todo lists.
- Organize todo lists into categories for better management.
- Mark tasks as completed and view completed tasks separately.
- Responsive design for seamless user experience on different devices.
- Premium Subscription with Stripe Payment: Subscribers get unlimited todo lists.
- Free Tier: Users without a premium subscription can have a maximum of 5 todo lists.

## Prerequisites

Before running the Todo List application, ensure you have the following prerequisites installed:

1. PHP 7.0 or higher
2. MySQL or compatible database server
3. Composer (for installing PHP dependencies)
4. Stripe Account: You need a Stripe account to set up the payment integration.

## Installation

1. Clone the repository to your local machine.

```bash
git clone https://github.com/yourusername/todo-list-app.git

Install PHP dependencies using Composer.
composer install
Import the todos.sql database file from the db folder into your MySQL database server.

