=== Woo CRM Integration for Zoho ===
Contributors: Nebulastores
Tags: integrate zoho, crm, zoho woocommerce, zoho integration, woocommerce zoho integration
Requires at least: 5.5.0
Tested up to:  6.7.1
WC requires at least: 5.5.0
WC tested up to: 9.6.0
Stable tag: 3.3.0
Requires PHP: 7.2 or Higher
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html


Install this plugin to integrate your Zoho CRM with the WooCommerce store.

**SYNC YOUR WOOCOMMERCE STORE DATA OVER ZOHO CRM TO TRACK, MANAGE ANALYZE IT WITHOUT ANY HASSLE**

== Description ==

✅ Easily sync Contact Form 7 Submission with Zoho CRM, Customize form fields, and map them to Zoho Object for smooth integration.
✅ With features like historical batch data sync, one-click sync, manual sync, and instant sync, effortlessly transfer data from WooCommerce to Zoho CRM for enhanced efficiency.
✅ Establish connections between Zoho CRM and WooCommerce by mapping Zoho fields to WooCommerce fields.
✅ View the syncing log detailing the complete data transfer process to Zoho CRM, ensuring transparency and accountability in your synchronization process.

**Note:** Zoho CRM Integration handles CRM functionalities. For books and inventory, try our [**Zoho Books or Inventory Integration**](https://woo.com/products/integration-with-zoho-books-inventory/)

== Features Of WooCommerce Zoho Integration ==

* **Feeds To sync WooCommerce Data -** The Woo Zoho CRM integration plugin comes with feeds you can use to sync the data of your WooCommerce objects to Zoho CRM.

By default, you get four feeds that sync the data of these objects. 

Products 
Contacts 
Deals 
Orders 

**Note:** Besides the predefined feeds, you can also generate custom feeds to seamlessly synchronize data for additional objects like Invoices, Accounts, and more.

== Synchronize ==

You can sync all your existing (historical) data using the Woo Zoho CRM. 

* **One-click sync -** Sync newly added data or data that didn’t sync in the last process Click on the Choose WC Object Type dropdown to choose the object, select the feed, and start the syncing process.

* **Synchronize Large Datasets Efficiently -** Sync all data of the WooCommerce object. Select the object and related feed to start sending your existing data over Zoho CRM.

* ** Date Range For Bulk Data Sync -** Easily bulk sync data within a custom date range by selecting your desired start (From Date) and end (To Date) dates. 

* **Instant Sync -** The instant sync feature, if enabled, synchronizes your WooCommerce data to Zoho CRM instantly. (This depends on the event you select in your feed settings. 
 
* **Two-Way Sync for Products Stock Quantity and Orders -** This feature allows you to sync the product stock quantity and sales order status from Zoho CRM to WooCommerce. Whenever a Product’s stock or an Order’s status is changed in the Zoho CRM, it will sync and reflect over your WooCommerce store as well.

* **Two-Way Deletion for Products -** If you move a product to the trash in your WooCommerce store, it becomes inactive in Zoho CRM. Likewise, if a product is marked inactive in Zoho CRM, it gets moved to the trash in WooCommerce. The same goes for restoration if you restore a product from the trash the product will be reactive in Zoho CRM and vice versa. The product will be completely deleted if you delete it from Zoho CRM or WooCommerce.

* **Two-Way Deletion for Simple and Variable Products -** If you move a simple or a variable product to the trash in your WooCommerce store, it becomes inactive in Zoho CRM. Likewise, if a product is marked inactive in Zoho CRM, it gets moved to the trash in WooCommerce. The same goes for restoration if you restore a product from the trash the product will be reactive in Zoho CRM and vice versa. The product will be completely deleted if you delete it from Zoho CRM or WooCommerce

**Note:** Product will not be deleted in Zoho CRM if it’s already included in an Order.

* **Two Way Product Syncing -** With two-way syncing, any changes made to products in WooCommerce by admins will also be updated in Zoho CRM. Similarly, if a new product is created manually in Zoho CRM, it will automatically appear in WooCommerce and Vice Versa.

**Note:** Product will not be deleted in Zoho CRM if it’s already included in an Order.

* **Sync User Browser info and IP address -** You can create fields in the contact feed and map them to Zoho CRM to Sync the User's IP address and Browser info. This can help enhance tracking and segmentation capabilities for personalized engagement and analysis.

* **Abandoned Cart Synchronization -** A new tab has been added that allows you to sync your guests and logged-in users' abandoned cart data with Zoho CRM. You can enable or disable this feature as needed, set the cart abandonment time, delete abandoned cart data from your store after a specified period, and store the data in an HTML-encoded format. 

**Note:** An abandoned cart feed will be created automatically when you set up the plugin, including all the respective fields.

* **Background Sync -** With the background sync feature on, you can sync your existing data in the background over Zoho CRM every 5 minutes using the Bulk Sync feature. This feature helps you when you don’t want to initially sync the data while setting up the integration with Zoho CRM.

* ** Multi-Currency Support -** With multi-currency support, you can activate the multi-currency setting in Zoho. Once enabled, a new currency field will appear in your sales order feed from here you can sync orders in multiple currencies.

== Comprehensive Logging ==

The Log tab shows you a complete overview of all your WooCommerce data that was successfully synced to Zoho CRM or failed to sync.

* **Enable/Disable Logging -** You can enable/disable sync logging at your convenience using this Zoho WooCommerce integration plugin. Along with that, you can also set the time you want to store logs. The logs will automatically be deleted after the set number of days.

* **Sync Log Data Section -** The Sync Log Data section displays details like Feed, WooCommerce Object, Woo Object ID, Zoho Object, and more. All these details make it easy to identify & resolve errors that occur in synchronization.

* **Zoho Data Log -** The Zoho Data log displays the details whenever a Product’s Stock or Sales Order is updated over Zoho CRM and it is synchronized in WooCommerce (provided the respective options are enabled in the settings tab).

* **Woo Sync Logging -** You can easily enable or disable your log creation for the WooCommerce-based synced data over your Zoho CRM. Once you want to check your synchronized WooCommerce data over Zoho CRM for your log maintenance, you can utilize the data from here.

== Mapping ==

* **Sequential Events For Feeds -** With the Zoho CRM Integration plugin, you can create sequential events that trigger based on your defined feeds. For example, if you setup a sequential event "After Default Deals Feed" and link it to the Default Sales Feed, the Default Sales Feed will automatically sync right after the Default Deals Feed is synced.

* **Send the Total Amount Spent by Users -** Create feeds for Contacts and Orders Zoho objects and map fields to capture the total of completed orders by any customer and send it over to Zoho CRM. This will help you keep track of the conversion history of users.

* **Update Product Stock When The Order is Synced -** Zoho CRM integration will automatically update the product stock when the order is synced. If any user has purchased a product from the shop, the total quantity of the product will be reduced and automatically get synced to the Zoho CRM.

* **Send Product Tags to Zoho Products -** The Woo Zoho CRM integration plugin allows you to map fields for syncing product tag data from your WooCommerce store to your Zoho CRM products. So, any product tags you create in WooCommerce are sent to the Products object in Zoho.

* **Sync User Roles (Customer) -** The Zoho WooCommerce Integration plugin syncs WordPress user roles that are assigned by website administrators. For efficient Website management, it is important to have individuals oversee specific sections using the special privileges provided by the admin.

* **Sync Coupon Details -** Zoho CRM integration allows you to sync coupon details like Coupon Amount, Coupon Type, and Coupon Code. Syncing coupon details enhances your customer relationship management by tracking discounts effectively.

* **Sync Coupon Discounts in line Items -** You can enable the Discount line item settings to show the coupon amount details parallel to each line item. Otherwise discount will sync in parallel to the total and subtotal fields.

* **  Sync Order Notes by Users -** Woo Zoho Integration makes it easy to send Order Notes to Zoho CRM that are mentioned by customers while placing the order.  This helps keep all the important info in one place, so businesses can give better service without wasting time typing stuff in manually.

* **  Sync Tax Details On inline items -** Woo Zoho Integration allows you to sync Tax details on in line items through product mapping. This could be beneficial in ensuring accurate tax reporting and compliance.

**Note:** Tax will only sync on in line items and it will be associated with the Product, so firstly we have to do mapping of tax setup in the product feed and then sync the product. After that tax will be synced along with the product and will reflect in sales order line items.

* **Sync Coupon Discount in Both (In line items and Subtotal field) -** WooCommerce Zoho integration gives you the option to activate the "Sync Coupon Discount in Both" setting, which will display the coupon discount information in both, inline items as well as parallel to the subtotal field.

== Conditional Filters ==

Applying conditional filters on the feeds will make sure that the data will sync over Zoho CRM only if the set conditions are met. 

You can select the fields and type of condition and then add the values to determine your condition. Also, you can add multiple AND OR filters in your condition.

== WOOCOMMERCE ZOHO COMPATIBILITY == 

* **Coupon Referral Program**
Our Zoho CRM integration plugin is Compatible with the coupon referral program, this compatibility allows you to sync referral coupons created via CRP plugin.

* **Compatibility With WooCommerce HPOS**
CRM Integration for ZOHO is compatible with WooCommerce HPOS Allowing you to effortlessly manage your high-volume orders. It uses WooCommerce CRUD design to store order data in custom order tables to ensure the smooth workflow of your WooCommerce store when your order volume is high.

* **Compatibility With WooCommerce Subscriptions**
Our WooCommerce Zoho CRM integration solution is compatible with WooCommerce Subscriptions. The compatibility easily transfers subscription details including Subscription Start Date, End Date, Payment Method, Payment Schedule, and more as an individual subscription object from your WooCommerce store to Zoho CRM. 

* **WooCommerce Memberships**
WooCommerce CRM Zoho Integration plugin smoothly integrates with WooCommerce Membership plugin, 2 individual feeds are available for membership creation and updation allowing you to sync key order information including details such as membership plan ID, User ID, Creation time & date, Membership Status, Detailed product information and much more.

* **Zoho Integration With CF7**
ZOHO CRM Integration With Contact Form 7 you can sync CF7 form submission data over to ZoHo CRM. The integration is established after installing and activating the Contact Form 7 plugin. You can tailor your form fields to get the desired responses. Also, you can map your custom fields and connect them with ZoHo objects.

== Benefits of Our WooCommerce Zoho Integration ==

* **Hassle-free setup and interface -** This Woo Zoho CRM Plugin is easy to set up. You don’t need to be a tech nerd to install and use it in your WooCommerce store.

* **Quick and easy data sync -** Sync all your WooCommerce data to Zoho CRM in no time. You can synchronize your existing and upcoming WooCommerce data to Zoho quickly. 

* **Auto Update the Deleted Feed Data -** The plugin’s Reset Zoho Feed ID metabox settings simplify syncing and updating data, even if changes were unintentionally made. It ensures smooth data feed synchronization. 

* **Save time you waste solving complex errors -** The Zoho integration with the WooCommerce plugin provides a detailed error log report that helps you identify the cause of errors. It cuts the time you spend finding and correcting errors. 

* **Get Full Control Over Your Data -** With the Woo CRM Zoho Integration plugin, you gain full control over mapping fields, order statuses, events, and more. Dictate what data syncs over CRM for each object.

== Installation ==

The manual installation method involves downloading our CRM Integration for Zoho and uploading it to your webserver via your favorite FTP application. The WordPress codex contains [**Instructions on how to do this here**](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

== Changelog ==

= 2025-03-10 - Version 1.0.0 =
 * First Release.
 