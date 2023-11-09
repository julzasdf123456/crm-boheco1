<h1 align="center"><img src="https://assets.infyom.com/open-source/infyom-logo.png" alt="InfyOm"></h1>

# Laravel Boilerplate for AdminLTE Theme

Laravel Boilerplate with [AdminLTE](https://adminlte.io/) Theme with [InfyOm Laravel Generator](https://github.com/InfyOmLabs/laravel-generator).
Following things are ready to be used directly with AdminLTE Theme.

- Signup
- Login
- Forgot Password
- Password Reset
- Home Layout with Sidebar

## Packages Installed

- InfyOm Laravel Generator
- AdminLTE Templates
- Laravel UI
- Laravel Collective

## Usage

1. Clone/Download a repo.
2. Copy `.env.example` file to `.env` & Setup your environment variables
3. Run `composer install`
4. Generate application key by running `php artisan key:generate`

Once everything is installed, you are ready to go with generator.

## Support Us

We have created [14+ Laravel packages](https://github.com/InfyOmLabs) and invested a lot of resources into creating these all packages and maintaining them.

You can support us by either sponsoring us or buying one of our paid products. Or help us by spreading the word about us on social platforms via tweets and posts.

### Sponsors

[Become a sponsor](https://opencollective.com/infyomlabs#sponsor) and get your logo on our README on Github with a link to your site.

<a href="https://opencollective.com/infyomlabs#sponsor"><img src="https://opencollective.com/infyomlabs/sponsors.svg?width=890"></a>

### Backers

[Become a backer](https://opencollective.com/infyomlabs#backer) and get your image on our README on Github with a link to your site.

<a href="https://opencollective.com/infyomlabs#backer"><img src="https://opencollective.com/infyomlabs/backers.svg?width=890"></a>

### Buy our Paid Products

[![InfyHMS](https://assets.infyom.com/open-source/infyhms-banner.png)](https://1.envato.market/6by5EQ)

You can also check out our other paid products on [CodeCanyon](https://codecanyon.net/user/infyomlabs/portfolio).

### Follow Us

- [Twitter](https://twitter.com/infyom)
- [Facebook](https://www.facebook.com/infyom)
- [LinkedIn](https://in.linkedin.com/company/infyom-technologies)
- [Youtube](https://www.youtube.com/channel/UC8IvwfChD6i7Wp4yZp3tNsQ)
- [Contact Us](https://infyom.com/contact-us)

- to Run: php artisan serve --host 192.168.0.124 --port 80

### Project Config
- In table CRM_MemberConsumerImages Manually "HexImage - Text" to database;

### Tickets
- In the creation of Tickets, replace the array of METER-RELATED mother tickets to capture all the tickets pertaining to meter complains
    (found in TicketsController.store)
- Disconnection Delinquency Ticket ID - in Tickets, configure the ID of the Disconnection Delinquency inside Tickets.getDisconnectionDelinquencyId() function

### Billing - Rates
- In the "Rates Template", make sure to add the Real Property Tax (RPT) to the overall rate during deployment (optional)
- Make Sure to use the FOR UPLOAD Sheet or FILE
- Also, make sure that the arrangement of Districts on the For Upload Template is not interchanged

### User - Special Authentication
- In UsersController.authenticate(), update the permissions

### Additional Roles
- Finance (ok)

### Additional Permissions
- sc transformer ammortization (ok)

### Additional Columns
- `CRM_ServiceConnections` 
    1. ExistingAccountNumber  (ok)

- `CRM_ServiceConnectionTotalPayments`
    1.  MaterialCost  (ok)
    2.  LaborCost  (ok)
    3.  ContingencyCost  (ok)
    4.  MaterialsVAT  (ok)
    5.  TransformerCost  (ok)
    6.  TransformerVAT  (ok)
    7.  TransformerDownpaymentPercentage  (ok)
    8.  BillOfMaterialsTotal  (ok)
    9.  InstallationFeeCanBePaid  (ok)
    10.  InstallationFeeORNumber  (ok)
    11.  InstallationFeeORDate  (ok)
    12.  TransformerReceivablesTotal  (ok)
    13.  TransformerAmmortizationTerms  (ok)
    14.  TransformerAmmortizationStart  (ok)
    15.  TransformerORDate  (ok)
    16.  TransformerORNumber  (ok)
    17.  TransformerInterestPercentage  (ok)
    18.  WithholdingTwoPercent  (ok)
    19.  WithholdingFivePercent  (ok)
    20.  InstallationFeeDownPaymentPercentage  (ok)
    21.  InstallationFeeBalance  (ok)
    22.  InstallationFeeTerms  (ok)
    23.  InstallationFeeTermAmountPerMonth  (ok)
    24.  RemittanceForwarded  (ok)
    25.  InstallationForwarded  (ok)
    26.  TransformerForwarded  (ok)
    27.  TransformerTwoPercentWT  (ok)
    28.  TransformerFivePercentWT  (ok)

- `CRM_ServiceConnectionMeterAndTransformer`
    1.  CoreLoss (ok)
    2.  Item1 (ok)
    3.  Item2 (ok)
    4.  Item3 (ok)
    5.  Item4 (ok)

### New .env Config Variables
- TRANSFORMER_INTEREST_PERCENTAGE (Decimal)
- TRANSFORMER_DP_PERCENTAGE (Decimal)
- OSD_ACCOUNTANT
- OSD_CHIEF
- OSD_MANAGER