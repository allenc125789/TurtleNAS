~ need to create js based timeout function.

~ lots of unused code needs to be trimmed. might need to reuse the hashing system to provide a more unique hash when downloading a compressed folder.

~ re-evaluate permissions method. Consideration for R/W abalities, sudo, and grouping needed.

~ currently, there is no limit for how much data can be uploaded to the server. When creating the admin menu, I'll be adding a function to limit file uploads per user based on disk size. I'll also probably need to add notifications for when disk's are becoming full.

~ Need to create an IP based-blacklist logging and banning system. Probably by a bash or php based function logging into mariadb
