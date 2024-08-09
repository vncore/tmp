<p align="center">
    <img src="https://vncore.net/logo.png?v=4" width="150">
</p>
<p align="center">Laravel admin | core backend for all systems (ecommerce, cms, pmo...)<br>
    <code><b>composer require vncore/core</b></code></p>
<p align="center">
 <a href="https://vncore.net">Installation and usage documentation</a>
</p>

<p align="center">
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/license.svg" alt="License"></a>
</p>

## About Vncore
Vncore is a compact source code built with Laravel, helping users quickly build a powerful admin website. Whether your system is simple or complex, Vncore will help you operate and scale it easily.

**What can Vncore do?**

- Provides a powerful and flexible role management and user group solution.
- Offers a synchronous authentication API, enhancing API security with additional layers.
- Build and manage Plugins/Templates that work in the system
- Comprehensive access log monitoring system.
- Continuously updates security vulnerabilities.
- Supports multiple languages, easy management.
- Vncore is FREE

**And more:**

- Vncore builds a large, open ecosystem (plugin, template), helping users quickly build CMS, PMO, eCommerce, etc., according to your needs.

## Laravel core:

Vncore 1.x

> Core laravel framework 11.x 


## Website structure using Vncore

    Website-folder/
    ├── app
    │     └── Vncore
    │           ├── Helpers(+)
    │           └── Plugins(+)
    ├── public
    │     └── Vncore
    │           ├── Admin(+)
    │           ├── Templates(+)
    │           └── Plugins(+)
    ├── resources
    │     └── views
    │           └── Vncore
    │                 ├── Templates(+)
    │                 └── Plugins(+)
    ├── vendor
    │     └── vncore/core
    ├── .env
    └──...

## Support the project
Support this project :stuck_out_tongue_winking_eye: :pray:
<p align="center">
    <a href="https://www.paypal.me/LeLanh" target="_blank"><img src="https://img.shields.io/badge/Donate-PayPal-green.svg" data-origin="https://img.shields.io/badge/Donate-PayPal-green.svg" alt="PayPal Me"></a>
</p>

## Quick Installation Guide
- **Step 1**: Prepare the Laravel source

  Refer to the command: `composer create-project laravel/laravel website-folder`

- **Step 2**: Install the vncore/core package

  Navigate to the newly installed Laravel directory, in this example, `website-folder`

  Run the command: `composer require vncore/core`

- **Step 3**: Check the configuration in the .env file

  Ensure that the database configuration and APP_KEY information in the .env file are complete.

  If the APP_KEY is not set, use the following command to generate it: `php artisan key:generate`

- **Step 4**: Initialize vncore

  Run the command: `php artisan vncore:init`

  If an error occurs during the initialization, you can manually copy the folders from `vendor/vncore/core/src/public` to `website-folder/public`.

- **Step 5**: Install vncore

  Access the URL `your-domain/vncore-install.php` to proceed with the installation.

  Note: Once the installation is complete, the file `website-folder/public/vncore-install.php` will be renamed to `website-folder/public/vncore-install.vncore`.

  If the renaming process fails, you can manually rename (or delete) this file.

## Update Vncore
- Update the package using the command: `composer update vncore/core`
- Then, run the command: `php artisan vncore:update` to apply internal updates

## Quickly disable Vncore and plugins
Just add the variable `VNCORE_ACTIVE=0` to the `.env` file
