SyliusAffiliateBundle
======================

Affiliate bundle for Sylius.



## Installation

  1. require the bundle with Composer:

  ```bash
  $ composer require pentarim/sylius-affiliate-bundle
  ```

  2. enable the bundle in `app/AppKernel.php`:

  ```php
  public function registerBundles()
  {
    $bundles = array(
      // ...
      new \Pentarim\SyliusAffiliateBundle\SyliusAffiliateBundle(),
      // ...
    );
  }
  ```

  3. register routes in `app/config/routing.yml`

  ```yaml
  sylius_affiliate:
    resource: "@SyliusAffiliateBundle/Resources/config/routing/main.yml"
  ```
  4. To create database tables and RBAC permissions run the
following command:

  ```bash
  $ app/console sylius:affiliate:install
  ```
  There are "--skip-permissions" and "--skip-database" options for this command please check the help page for details
  
  5. Create menu entries for frontend and backend
  
  TBC
  
Configuration
----------
TBC, for now please check Pentarim/SyliusAffiliateBundle/DependencyInjection/Configuration.php


Versioning
----------

Releases will be numbered with the format `major.minor.patch`.

And constructed with the following guidelines.

* Breaking backwards compatibility bumps the major.
* New additions without breaking backwards compatibility bumps the minor.
* Bug fixes and misc changes bump the patch.

For more information on SemVer, please visit [semver.org website](http://semver.org/).  
This versioning method is same for all **Sylius** bundles and applications.

MIT License
-----------

License can be found [here](https://github.com/Sylius/SyliusAffiliateBundle/blob/master/Resources/meta/LICENSE).

Authors
-------

The bundle was created by [Laszlo Horvath](https://github.com/pentarim), its based on prior work of [Joseph Bielawski](https://github.com/stloyd).

The work on this bundle was proudly sponsored by [Locastic](http://www.locastic.com).
