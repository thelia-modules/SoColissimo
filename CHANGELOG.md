# 1.4.7
- Changing a relay point Free Shipping for a specific area does not add the price for every other area anymore. 

# 1.4.6
- Displays an arror message instead of crashing when "mail_socolissimo" isn't created, thus allowing to not send any confirmation email.

# 1.4.5
- Fixed crash when using getCartAmount while cartAmount is null

# 1.4.4
- create loops: AreaFreeshippingDom and AreaFreeshippingPr
- update template for to add these loops 
- update version module

# 1.4.3
- fix freeshipping.php
- add translate: l18n/backOffice/default/fr_FR.php
- update version module

# 1.4.2
Objective: Add a minimum cart amount to get the free shipping cost for "So Colissimo Domicile" by Area
- Add table socolissimo_area_freeshipping
- Update Routing.xml, Freeshipping.php, SoColissimo.php and Template BO
- Update version module.xml & CHANGELOG.md

# 1.4.1
- Fix bug area delivery

# 1.4.0
- Add price slices by cart price and weight

# 1.3.0
- International support of Relay Points

# 1.2.7
- Fix relais query on address with accent

# 1.2.6
- Fix delivery amount not recalculated correctly (ex when using a coupon)

# 1.2.5
- added franco per area and weight

# 1.2.4
- Feature/order date

# 1.2.3
- Bump version 1.2.3

# 1.2.2
- Bump version 1.2.2

# 1.2.1

- Change expeditor filename

# 1.1.4

- Remove duplicated address edition controls on delivery page
- Fix unopened HTML elements

# 1.1.3

- Fix support for relay search by Thelia address

# 1.1.2

- Set relay id to an empty string when exporting non-relay orders

# 1.1.1

- Fix routing for the csv export

# 1.1.0

- Add import csv for update the orders status