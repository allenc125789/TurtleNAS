## 12.24.2024
+Added pages to the admin menu:
  + Updates
  + Interfaces

## 12.08.2024
+Added/fixed feature, group authorization: Pages and features will be locked depending on the group they belong to. eg; the average linux user will not be able to be accessed by TurtleNAS, unless it's within the www-data user group. Admin pages will not be available unless the user acessing it belongs to the admin group.

## 09.05.2024
+Added a page selection menu for speific admin pages

## 09.04.2024
+Added view changing buttons, which allows users to switch between the File Browser and Admin pages, if said user is in the 'admin' group.

~~+Created a group verifcation function, for group specific validation.~~ (Was not fully functioning, fixed on 12.08.2024)

## 08.29.2024
+Cleaned and removed unused code.

+Added comments to give a better description for each function and other specific/complex sections of the code.

## 05.22.2024
+multiple files can be uploaded.

+files with spaces and special chars can be uploaded and downloaded and browseed

+files and empty folders can be deleted

+able to create empty folders

+files without extensions can be uploaded/downloaded

+added checkbox to uncheck/check all other chekboxes

+folders with files inside can be deleted

+compatible with Window's name formatting.

+can download files without an extension

+added a button with a dropdown menu to download the current folder as a zip, and another for encrypted zip, with tar alternatives.

+program runs effeiciently enough with recent JS code implimentation. Changing directories is seamless, and multiple gigs of files can be uploaded without major impact on performance. Need to test with uploads from multiple users.

+added changelogs to keep track of my progress better, starting today.
