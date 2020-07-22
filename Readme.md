# Acceptacard Opencart shopping cart module

##### Installation Instructions
- Download the latest version of the module from the [OpenCart Extensions page](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=16072&filter_search=paya%20card&filter_category_id=3&filter_license=0)
- Within OpenCart 3 admin area browse to: Extensions > Installer
- Upload the .ocmod.zip file downloaded form the extensions page
- Browse to Extensions > Extensions within your admin area
- Select "Payments" from the dropdown
- Scroll to "PayaCardServices"
- Click green "Install" button
- Edit the payment method

Enter all your relevent information here and save.

Field|Value
---|---
Payment Account ID|Your Account ID from PayaCardServices
Gateway URL|Either Live or Test - latest URL's can be found in the Payment Integration guide
Payment Trader Name|What you would like the customer to see on the payment pages
Order Status|The status you want the order to be put into after successful payment

After entering thse values, and saving, your shopping cart should now be setup to use PayaCardServices' payment gateway.